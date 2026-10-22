<script>
/* ════════════════════════════════════════════════════════════════════
   USER FORM MODALS — create + edit, AJAX submit, no page reload
   ════════════════════════════════════════════════════════════════════ */
(function () {
    'use strict';

    /* ────────────────────────────────────────────────────────────
       1) Config (all URLs from Blade)
       ──────────────────────────────────────────────────────────── */
    const CFG = {
        csrf: @json(csrf_token()),
        routes: {
            create:     @json(route('users.store')),
            editTpl:    @json(route('users.edit',   ['user' => '__ID__'])),
            updateTpl:  @json(route('users.update', ['user' => '__ID__'])),
            index:      @json(route('users.index')),
            validateUsername: @json(route('validate.username')),
            validateEmail:    @json(route('validate.email')),
            validatePassword: @json(route('validate.password')),
        },
    };

    const $  = (s, r = document) => r.querySelector(s);
    const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));

    /* ────────────────────────────────────────────────────────────
       2) Toast helper (works with toastr/Swal/console)
       ──────────────────────────────────────────────────────────── */
    function toast(type, message) {
        const helper = {
            success: window.toastSuccess, error: window.toastError,
            warning: window.toastWarning, info: window.toastInfo,
        }[type];
        if (typeof helper === 'function') return helper(message);
        if (typeof window.toast === 'function') return window.toast(type, message);
        if (window.Swal?.fire) {
            return window.Swal.fire({
                toast: true, position: 'top-end', icon: type, title: message,
                showConfirmButton: false, timer: 2600, timerProgressBar: true,
            });
        }
        console.log(`[${type}] ${message}`);
    }

    /* ────────────────────────────────────────────────────────────
       3) Modal open / close / scroll lock
       ──────────────────────────────────────────────────────────── */
    function lockScroll(lock) {
        document.body.classList.toggle('um-scroll-lock', !!lock);
    }

    function openModal(id) {
        const modal = document.getElementById(id);
        if (!modal) { console.warn('[userModal] not found:', id); return; }
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        lockScroll(true);
        setTimeout(() => {
            modal.querySelector('input:not([type=hidden]), select, textarea')?.focus();
        }, 60);
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        // If no other modal is open, unlock scroll
        const anyOpen = $$('[role="dialog"]').some(m =>
            !m.classList.contains('hidden') && m.classList.contains('flex'));
        if (!anyOpen) lockScroll(false);
    }

    /* ────────────────────────────────────────────────────────────
       4) Field error helpers
       ──────────────────────────────────────────────────────────── */
    function clearErrors(form) {
        $$('[data-field-error]', form).forEach(el => {
            el.textContent = '';
            el.classList.add('hidden');
        });
        $$('input, select, textarea', form).forEach(el => {
            el.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500/30');
            el.removeAttribute('aria-invalid');
        });
    }

    function applyErrors(form, errors) {
        let first = null;
        Object.entries(errors || {}).forEach(([field, messages]) => {
            const key    = field.split('.')[0];
            const input  = form.querySelector(`[name="${key}"], [name="${key}[]"]`);
            const errEl  = form.querySelector(`[data-field-error="${key}"]`);
            const msg    = Array.isArray(messages) ? messages[0] : String(messages);

            if (input) {
                input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500/30');
                input.setAttribute('aria-invalid', 'true');
                first = first || input;
            }
            if (errEl) {
                errEl.textContent = msg;
                errEl.classList.remove('hidden');
            }
        });
        first?.focus({ preventScroll: false });
        first?.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    /* ────────────────────────────────────────────────────────────
       5) Status pill
       ──────────────────────────────────────────────────────────── */
    function showStatus(el, message, state = 'neutral') {
        if (!el) return;
        const styles = {
            neutral: 'text-gray-500 dark:text-gray-400',
            loading: 'text-indigo-500',
            success: 'text-emerald-600 dark:text-emerald-400',
            error:   'text-red-600 dark:text-red-400',
            warning: 'text-amber-600 dark:text-amber-400',
        };
        el.className = 'mt-2 text-xs font-medium ' + (styles[state] || styles.neutral);
        el.textContent = message || '';
    }

    /* ────────────────────────────────────────────────────────────
       6) Debounce
       ──────────────────────────────────────────────────────────── */
    function debounce(fn, wait = 400) {
        let t;
        return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), wait); };
    }

    /* ────────────────────────────────────────────────────────────
       7) Select2 (safe init)
       ──────────────────────────────────────────────────────────── */
    function initSelect2(scope) {
        if (!window.jQuery || !jQuery.fn.select2) return;
        jQuery(scope).find('select[data-select2]').each(function () {
            if (jQuery(this).hasClass('select2-hidden-accessible')) {
                jQuery(this).select2('destroy');
            }
            jQuery(this).select2({
                placeholder: this.dataset.placeholder || 'Search…',
                allowClear: true,
                width: '100%',
                closeOnSelect: false,
                dropdownParent: jQuery(this).closest('.user-modal-panel'),
            });
        });
    }

    /* ────────────────────────────────────────────────────────────
       8) Password strength + match (per form)
       ──────────────────────────────────────────────────────────── */
    function wirePasswordTools(form) {
        const pw       = form.querySelector('[name="password"]');
        const pwConf   = form.querySelector('[name="password_confirmation"]');
        const label    = form.querySelector('[data-pw-strength-label]');
        const feedback = form.querySelector('[data-pw-feedback]');
        const bars     = form.querySelectorAll('[data-strength-bar]');
        const statusEl = form.querySelector('[data-pw-confirm-status]');
        if (!pw) return;

        const colors = { 1:'bg-red-500', 2:'bg-orange-500', 3:'bg-amber-500',
                         4:'bg-lime-500', 5:'bg-emerald-500' };
        let reqId = 0;

        function resetBars() {
            bars.forEach(b => b.className = 'h-1.5 rounded-full bg-gray-200 dark:bg-gray-700');
        }

        function updateMatch() {
            if (!pwConf || !statusEl) return;
            const v = pwConf.value;
            if (!v) { statusEl.textContent = ''; return; }
            if (pw.value === v) {
                statusEl.className = 'mt-2 text-xs font-medium text-emerald-600 dark:text-emerald-400';
                statusEl.textContent = '✓ Passwords match.';
            } else {
                statusEl.className = 'mt-2 text-xs font-medium text-red-600 dark:text-red-400';
                statusEl.textContent = 'Passwords do not match.';
            }
        }

        const run = debounce(async () => {
            const value = pw.value;
            if (!value) {
                if (label) label.textContent = 'Not entered';
                if (feedback) feedback.textContent = '';
                resetBars(); updateMatch();
                return;
            }
            const id = ++reqId;
            if (label) label.textContent = 'Checking…';
            try {
                const res = await fetch(CFG.routes.validatePassword, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CFG.csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ password: value }),
                });
                if (!res.ok) throw new Error('pw check failed');
                const data = await res.json();
                if (id !== reqId || pw.value !== value) return;

                if (label) {
                    label.textContent = data.label || '';
                    label.className = 'text-xs font-semibold ' + (data.valid
                        ? 'text-emerald-600 dark:text-emerald-400'
                        : 'text-red-600 dark:text-red-400');
                }
                if (feedback) feedback.textContent = data.message || '';
                bars.forEach((b, i) => {
                    b.className = 'h-1.5 rounded-full ' +
                        (i < data.score ? colors[data.score] : 'bg-gray-200 dark:bg-gray-700');
                });
                updateMatch();
            } catch {
                if (id === reqId && feedback) feedback.textContent = 'Could not check password strength.';
            }
        }, 250);

        pw.addEventListener('input', run);
        pwConf?.addEventListener('input', updateMatch);
    }

    /* ────────────────────────────────────────────────────────────
       9) Username auto-gen + live availability (per form)
       ──────────────────────────────────────────────────────────── */
    function wireUsernameTools(form, userId) {
        const first    = form.querySelector('[name="first_name"]');
        const last     = form.querySelector('[name="last_name"]');
        const username = form.querySelector('[name="username"]');
        const statusEl = form.querySelector('[data-username-status]');
        if (!username || !statusEl) return;

        let manuallyEdited = Boolean(username.value.trim());

        const slug = v => String(v || '')
            .normalize('NFKD').replace(/[\u0300-\u036f]/g, '')
            .toLowerCase().trim()
            .replace(/[^a-z0-9._-]+/g, '.')
            .replace(/^[._-]+|[._-]+$/g, '')
            .slice(0, 30);

        function generate() {
            if (manuallyEdited) return;
            const u = [slug(first?.value), slug(last?.value)].filter(Boolean).join('.').slice(0, 30);
            username.value = u;
            username.dispatchEvent(new Event('input', { bubbles: true }));
        }
        first?.addEventListener('input', generate);
        last?.addEventListener('input', generate);

        const check = debounce(async () => {
            const value = username.value.trim();
            if (value.length < 3) {
                showStatus(statusEl, 'Enter at least 3 characters.', 'neutral');
                return;
            }
            const params = new URLSearchParams({ username: value });
            if (userId) params.set('ignore', userId);
            showStatus(statusEl, 'Checking availability…', 'loading');
            try {
                const res = await fetch(`${CFG.routes.validateUsername}?${params}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                });
                if (!res.ok) throw new Error();
                const data = await res.json();
                if (username.value.trim() !== value) return;
                showStatus(statusEl, data.message, data.valid ? 'success' : 'error');
            } catch { showStatus(statusEl, 'Could not verify availability.', 'warning'); }
        });

        username.addEventListener('input', () => { manuallyEdited = true; check(); });
    }

    /* ────────────────────────────────────────────────────────────
       10) Email availability (per form)
       ──────────────────────────────────────────────────────────── */
    function wireEmailTools(form, userId) {
        const email    = form.querySelector('[name="email"]');
        const statusEl = form.querySelector('[data-email-status]');
        if (!email || !statusEl) return;

        const check = debounce(async () => {
            const value = email.value.trim();
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                showStatus(statusEl, 'Enter a valid email address.', 'neutral');
                return;
            }
            const params = new URLSearchParams({ email: value });
            if (userId) params.set('ignore', userId);
            showStatus(statusEl, 'Checking availability…', 'loading');
            try {
                const res = await fetch(`${CFG.routes.validateEmail}?${params}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                });
                if (!res.ok) throw new Error();
                const data = await res.json();
                if (email.value.trim() !== value) return;
                showStatus(statusEl, data.message, data.valid ? 'success' : 'error');
            } catch { showStatus(statusEl, 'Could not verify availability.', 'warning'); }
        });

        email.addEventListener('input', check);
    }

    /* ────────────────────────────────────────────────────────────
       11) Password visibility toggles (per form)
       ──────────────────────────────────────────────────────────── */
    function wirePasswordToggles(form) {
        $$('[data-toggle-password]', form).forEach(btn => {
            if (btn.dataset.bound === '1') return;
            btn.dataset.bound = '1';
            btn.addEventListener('click', () => {
                const target = form.querySelector(`#${btn.dataset.togglePassword}`)
                            || document.getElementById(btn.dataset.togglePassword);
                if (!target) return;
                const hidden = target.type === 'password';
                target.type = hidden ? 'text' : 'password';
                btn.querySelector('[data-eye-open]')?.classList.toggle('hidden', hidden);
                btn.querySelector('[data-eye-closed]')?.classList.toggle('hidden', !hidden);
            });
        });
    }

    /* ────────────────────────────────────────────────────────────
       12) AJAX submit (per form)
       ──────────────────────────────────────────────────────────── */
    function wireFormSubmit(form, submitBtn) {
        if (form.dataset.ajaxBound === '1') return;
        form.dataset.ajaxBound = '1';

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            e.stopPropagation();

            clearErrors(form);

            const labelEl = submitBtn?.querySelector('[data-submit-label]');
            const original = labelEl?.textContent;
            if (labelEl) labelEl.textContent = 'Saving…';
            if (submitBtn) submitBtn.disabled = true;

            try {
                const fd = new FormData(form);
                // Ensure PUT / PATCH method spoofing
                if (!fd.has('_method') && /\/update\b/.test(form.action)) {
                    fd.set('_method', 'PUT');
                }

                const res = await fetch(form.action, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': CFG.csrf,
                    },
                    body: fd,
                });

                const data = await res.json().catch(() => ({}));

                if (res.status === 422) {
                    applyErrors(form, data.errors || {});
                    const summary = Object.values(data.errors || {}).flat().slice(0, 3).join(' · ');
                    toast('error', summary || data.message || 'Please fix the highlighted fields.');
                    return;
                }

                if (!res.ok || data.success === false) {
                    throw new Error(data.message || `Save failed (HTTP ${res.status}).`);
                }

                /* ── success ── */
                toast('success', data.message || 'Saved successfully.');

                const modalId = form.closest('[role="dialog"]')?.id;
                if (modalId) closeModal(modalId);

                // Reload the listing
                const onIndex = window.location.pathname === new URL(CFG.routes.index, location.origin).pathname;
                if (onIndex) {
                    window.location.reload();
                } else if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    window.location.href = CFG.routes.index;
                }
            } catch (err) {
                console.error('[userForm submit]', err);
                toast('error', err.message || 'Save failed.');
            } finally {
                if (labelEl) labelEl.textContent = original;
                if (submitBtn) submitBtn.disabled = false;
            }
        });
    }

    /* ────────────────────────────────────────────────────────────
       13) Build: Create modal
       ──────────────────────────────────────────────────────────── */
    function initCreateModal() {
        const modal = document.getElementById('createUserModal');
        const form  = document.getElementById('createUserForm');
        if (!modal || !form) return; // silently skip if not on page

        window.openCreateUserModal = function openCreateUserModal() {
            form.reset();
            form.action = CFG.routes.create;
            clearErrors(form);

            // Destroy previous Select2 instances
            if (window.jQuery) {
                jQuery(form).find('select[data-select2]').each(function () {
                    if (jQuery(this).hasClass('select2-hidden-accessible')) jQuery(this).select2('destroy');
                    this.selectedIndex = -1;
                });
            }

            // Reset statuses
            form.querySelector('[data-username-status]')?.replaceChildren();
            form.querySelector('[data-email-status]')?.replaceChildren();
            const pcs = form.querySelector('[data-pw-confirm-status]');
            if (pcs) pcs.textContent = '';
            const lbl = form.querySelector('[data-pw-strength-label]');
            if (lbl) lbl.textContent = 'Not entered';
            const fb = form.querySelector('[data-pw-feedback]');
            if (fb) fb.textContent = '';
            form.querySelectorAll('[data-strength-bar]').forEach(b => {
                b.className = 'h-1.5 rounded-full bg-gray-200 dark:bg-gray-700';
            });

            // Passwords required in create mode
            form.querySelector('[name="password"]')?.setAttribute('required', 'required');
            form.querySelector('[name="password_confirmation"]')?.setAttribute('required', 'required');

            openModal('createUserModal');
            initSelect2(form);
            wirePasswordTools(form);
            wireUsernameTools(form, null);
            wireEmailTools(form, null);
            wirePasswordToggles(form);
            wireFormSubmit(form, document.getElementById('createUserSubmit'));
        };

        // Bind close buttons & backdrop
        $$('[data-user-modal-close="createUserModal"]').forEach(el => {
            el.addEventListener('click', () => closeModal('createUserModal'));
        });
    }

    /* ────────────────────────────────────────────────────────────
       14) Build: Edit modal
       ──────────────────────────────────────────────────────────── */
    function initEditModal() {
        const modal = document.getElementById('editUserModal');
        const form  = document.getElementById('editUserForm');
        if (!modal || !form) return; // silently skip if not on page

        window.openEditUserModal = async function openEditUserModal(userId) {
            form.reset();
            clearErrors(form);

            // Destroy previous Select2
            if (window.jQuery) {
                jQuery(form).find('select[data-select2]').each(function () {
                    if (jQuery(this).hasClass('select2-hidden-accessible')) jQuery(this).select2('destroy');
                    this.selectedIndex = -1;
                });
            }

            // Reset statuses
            form.querySelector('[data-username-status]')?.replaceChildren();
            form.querySelector('[data-email-status]')?.replaceChildren();
            const pcs = form.querySelector('[data-pw-confirm-status]');
            if (pcs) pcs.textContent = '';
            const lbl = form.querySelector('[data-pw-strength-label]');
            if (lbl) lbl.textContent = 'Not entered';
            const fb = form.querySelector('[data-pw-feedback]');
            if (fb) fb.textContent = '';
            form.querySelectorAll('[data-strength-bar]').forEach(b => {
                b.className = 'h-1.5 rounded-full bg-gray-200 dark:bg-gray-700';
            });

            // Password optional in edit
            form.querySelector('[name="password"]')?.removeAttribute('required');
            form.querySelector('[name="password_confirmation"]')?.removeAttribute('required');

            // Wire once
            initSelect2(form);
            wirePasswordTools(form);
            wireUsernameTools(form, userId);
            wireEmailTools(form, userId);
            wirePasswordToggles(form);
            wireFormSubmit(form, document.getElementById('editUserSubmit'));

            // Set update action immediately
            form.action = CFG.routes.updateTpl.replace('__ID__', encodeURIComponent(userId));

            openModal('editUserModal');

            const subtitle = document.getElementById('editUserSubtitle');
            if (subtitle) subtitle.textContent = 'Loading user…';

            try {
                /* ── fetch user as JSON ── */
                const res = await fetch(CFG.routes.editTpl.replace('__ID__', encodeURIComponent(userId)), {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                });
                if (!res.ok) throw new Error(`Failed to load user (HTTP ${res.status}).`);
                const data = await res.json();

                /* Expecting shape:
                   { id, username, email, profile: { first_name, last_name },
                     role_ids: [1,2], update_url? } */
                const user    = data.user || data;
                const profile = user.profile || {};

                form.querySelector('[name="first_name"]').value = profile.first_name || '';
                form.querySelector('[name="last_name"]').value  = profile.last_name  || '';
                form.querySelector('[name="username"]').value   = user.username || '';
                form.querySelector('[name="email"]').value      = user.email || '';

                // Roles
                const roleSelect = form.querySelector('[name="roles[]"]');
                const ids = (user.role_ids || []).map(String);
                if (roleSelect) {
                    Array.from(roleSelect.options).forEach(o => {
                        o.selected = ids.includes(String(o.value));
                    });
                }
                if (window.jQuery && jQuery(roleSelect).hasClass('select2-hidden-accessible')) {
                    jQuery(roleSelect).trigger('change');
                }

                if (user.update_url) form.action = user.update_url;

                if (subtitle) subtitle.textContent = `Edit ${user.username || ''} · #${user.id || userId}`;
            } catch (err) {
                console.error('[openEditUserModal]', err);
                toast('error', err.message || 'Failed to load user.');
                closeModal('editUserModal');
            }
        };

        // Bind close buttons & backdrop
        $$('[data-user-modal-close="editUserModal"]').forEach(el => {
            el.addEventListener('click', () => closeModal('editUserModal'));
        });
    }

    /* ────────────────────────────────────────────────────────────
       15) Global keydown (Escape closes open modals)
       ──────────────────────────────────────────────────────────── */
    function wireGlobalKeys() {
        document.addEventListener('keydown', (e) => {
            if (e.key !== 'Escape') return;
            $$('[role="dialog"]').forEach(m => {
                if (!m.classList.contains('hidden')) {
                    closeModal(m.id);
                }
            });
        });
    }

    /* ────────────────────────────────────────────────────────────
       16) BOOT
       ──────────────────────────────────────────────────────────── */
    document.addEventListener('DOMContentLoaded', () => {
        try {
            initCreateModal();
            initEditModal();
            wireGlobalKeys();
        } catch (err) {
            console.error('[USER MODALS BOOT ERROR]', err);
        }
    });
})();
</script>