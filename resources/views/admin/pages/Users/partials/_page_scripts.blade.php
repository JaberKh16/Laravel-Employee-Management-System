@php
    $openFilterOnLoad = request()->hasAny([
        'name', 'email', 'date', 'dob',
        'status', 'role', 'updated_from', 'updated_to',
    ]);
@endphp

<script>
/* ════════════════════════════════════════════════════════════════════════
   APP — single-file, sectioned, config-driven
   ════════════════════════════════════════════════════════════════════════ */
(function () {
    'use strict';

    /* ────────────────────────────────────────────────────────────────
       1) CONFIGURATION OPTIONS  (SAFE — never throws if route missing)
       ──────────────────────────────────────────────────────────────── */
    function safeRoute(name) {
        try {
            return @json(true) && window.__laravelRoutes?.[name] ? null : null;
        } catch (e) { return null; }
    }

    function configurationOptions() {
        return {
            csrfToken:
                document.querySelector('meta[name="csrf-token"]')?.content
                ?? @json(csrf_token()),

            urls: {
                bulkStatus: @json(\Illuminate\Support\Facades\Route::has('users.bulk-status')
                    ? route('users.bulk-status') : null),
                bulkRole:   @json(\Illuminate\Support\Facades\Route::has('users.bulk-role')
                    ? route('users.bulk-role') : null),
                bulkOp:     @json(\Illuminate\Support\Facades\Route::has('users.bulk-operation')
                    ? route('users.bulk-operation') : null),
                states:     '/api/v1/states',
                cities:     '/api/v1/cities',
                makeEmployeeDataBase: '/admin/users',
                jobProfileCreate:     '/admin/job-profiles/create',
            },

            selectors: {
                selectAll:        '#bulkSelectAll',
                bulkBar:          '#bulkBar',
                bulkCount:        '#bulkCount',
                userCheckbox:     '[data-user-checkbox]',
                userRow:          '[data-user-row]',
                statusWidget:     '[data-status-widget]',
                dropdown:         '[data-dropdown]',
                downloadToggle:   '#downloadToggle',
                downloadMenu:     '#downloadMenu',
                downloadWrap:     '#downloadWrap',
                usersDataEl:      '#usersData',
                profileModal:     '#profileModal',
                makeEmployeeModal:'#makeEmployeeModal',
                makeEmployeeForm: '#makeEmployeeForm',
                makeEmployeeName: '#makeEmployeeName',
                makeEmployeeSubmit: '#makeEmployeeSubmit',

                headerTitle:      '#meModalTitle',
                headerBadge:      '#meHeaderBadge',
                headerBadgeLabel: '#meHeaderBadgeLabel',
                headerBadgeIcon:  '#meHeaderBadgeIcon',

                rightStatusBadge:     '#meRightStatusBadge',
                rightStatusIcon:      '#meRightStatusIcon',
                rightStatusLabel:     '#meRightStatusLabel',
                rightJobProfileBadge: '#meRightJobProfileBadge',
                rightJobProfileIcon:  '#meRightJobProfileIcon',
                rightJobProfileLabel: '#meRightJobProfileLabel',
                rightJobProfileBtn:   '#meRightJobProfileBtn',

                jobProfileSection:   '#meJobProfileSection',
                noJobProfileBox:     '#meNoJobProfile',
                createJobProfileBtn: '#me_create_job_profile_btn',

                meFields: {
                    name:       '#me_name',
                    dob:        '#me_dob',
                    age:        '#me_age',
                    gender:     '#me_gender',
                    zip:        '#me_zip',
                    department: '#me_department',
                    branch:     '#me_branch',
                    country:    '#me_country',
                    state:      '#me_state',
                    city:       '#me_city',
                    address:    '#me_address',
                    error:      '#meError',
                },
                filterPanel:    '#filterPanel',
                toggleFilterBtn:'#toggleFilterBtn',
                closeFilterBtn: '#closeFilterBtn',
                filterChevron:  '#filterChevron',
                searchForm:     '#mainSearchForm',
                criteriaRadio:  '.criteria-radio',
                criteriaField:  '.criteria-field',
                roleBtns:       '[data-bulk-role]',
                downloadItem:   '[data-download]',
            },

            opsWarnings: {
                'delete':         'Deleted users are soft-deleted. You can restore them from the trash if needed.',
                'force-delete':   'Force delete permanently removes users and their data. This CANNOT be undone.',
                'deactivate':     'Deactivated users cannot log in until reactivated.',
                'make-employee':  'Users who are already employees will be skipped.',
                'activate':       '',
                'export':         'This will download a CSV with the selected users.',
                'view-employees': 'You will be redirected to a filtered employee view.',
            },

            defaults: {
                makeEmployeeActionBase: '/admin/users',
            },

            openFilterOnLoad: {{ \Illuminate\Support\Js::from($openFilterOnLoad) }},
        };
    }

    /* ────────────────────────────────────────────────────────────────
       2) UTILITIES
       ──────────────────────────────────────────────────────────────── */
    const $  = (sel, root = document) => root.querySelector(sel);
    const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

    function notify(type, message, options = {}) {
        const helper = {
            success: window.toastSuccess, error: window.toastError,
            warning: window.toastWarning, info: window.toastInfo,
        }[type];

        if (typeof helper === 'function') return helper(message, options);
        if (typeof window.toast === 'function') return window.toast(type, message, options);

        if (window.Swal && typeof window.Swal.fire === 'function') {
            return window.Swal.fire({
                toast: true, position: 'top-end', icon: type, title: message,
                showConfirmButton: false, timer: options.timer || 2600, timerProgressBar: true,
            });
        }
        console.log(`[${type}] ${message}`);
    }

    function jsonHeaders(csrfToken) {
        return {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
        };
    }

    async function parseJson(res) {
        const ct = res.headers.get('content-type') || '';
        if (ct.indexOf('application/json') === -1) {
            throw new Error(`Server returned non-JSON (HTTP ${res.status})`);
        }
        return res.json();
    }

    /* ════════════════════════════════════════════════════════════════
       3) SECTION: SELECTION
       ════════════════════════════════════════════════════════════════ */
    function initSelection(cfg) {
        const sel          = cfg.selectors;
        const selectAllBox = $(sel.selectAll);
        const bulkBar      = $(sel.bulkBar);
        const bulkCount    = $(sel.bulkCount);

        const rowBoxes    = () => $$(sel.userCheckbox);
        const selectedIds = () => rowBoxes().filter(b => b.checked).map(b => b.value);

        function refresh() {
            const ids   = selectedIds();
            const total = rowBoxes().length;

            bulkBar?.classList.toggle('hidden', ids.length === 0);
            if (bulkCount) bulkCount.textContent = ids.length;

            if (selectAllBox) {
                selectAllBox.checked       = total > 0 && ids.length === total;
                selectAllBox.indeterminate = ids.length > 0 && ids.length < total;
            }
        }

        function ensureSelectionOrWarn() {
            const ids = selectedIds();
            if (ids.length > 0) return ids;

            notify('warning', 'Select at least one user from the table first.');
            document.getElementById('userTable')?.scrollIntoView({ behavior: 'smooth', block: 'center' });

            const firstBox = rowBoxes()[0];
            if (firstBox) {
                firstBox.classList.add('ring-2', 'ring-amber-400', 'ring-offset-2');
                setTimeout(() => firstBox.classList.remove('ring-2', 'ring-amber-400', 'ring-offset-2'), 1400);
                firstBox.focus({ preventScroll: true });
            }
            return null;
        }

        function clear() {
            rowBoxes().forEach(b => { b.checked = false; });
            if (selectAllBox) { selectAllBox.checked = false; selectAllBox.indeterminate = false; }
            refresh();
        }

        function init() {
            selectAllBox?.addEventListener('change', () => {
                rowBoxes().forEach(b => { b.checked = selectAllBox.checked; });
                refresh();
            });
            document.addEventListener('change', e => {
                if (e.target.matches(sel.userCheckbox)) refresh();
            });
            refresh();
        }

        return { init, refresh, clear, selectedIds, rowBoxes, ensureSelectionOrWarn };
    }

    /* ════════════════════════════════════════════════════════════════
       4) SECTION: DROPDOWNS
       ════════════════════════════════════════════════════════════════ */
    function initDropdowns(cfg, crossRefs) {
        const dropdownSel = cfg.selectors.dropdown;

        function closeAll(except = null) {
            $$(dropdownSel).forEach(dd => {
                if (dd === except) return;
                $('[data-dropdown-menu]', dd)?.classList.add('hidden');
                $('[data-dropdown-trigger]', dd)?.setAttribute('aria-expanded', 'false');
                $('[data-dropdown-chevron]', dd)?.classList.remove('rotate-180');
            });
        }

        function init() {
            $$(dropdownSel).forEach(dd => {
                if (dd.dataset.dropdownBound === '1') return;
                dd.dataset.dropdownBound = '1';

                const trigger = $('[data-dropdown-trigger]', dd);
                const menu    = $('[data-dropdown-menu]', dd);
                const chevron = $('[data-dropdown-chevron]', dd);
                if (!trigger || !menu) return;

                trigger.addEventListener('click', e => {
                    e.stopPropagation();
                    const opening = menu.classList.contains('hidden');
                    closeAll(dd);
                    crossRefs.status?.closeAllMenus();
                    menu.classList.toggle('hidden', !opening);
                    trigger.setAttribute('aria-expanded', String(opening));
                    chevron?.classList.toggle('rotate-180', opening);
                });
            });
        }

        return { init, closeAll };
    }

    /* ════════════════════════════════════════════════════════════════
       5) SECTION: STATUS WIDGET
       ════════════════════════════════════════════════════════════════ */
    function initStatusWidgets(cfg) {
        const widgetSel = cfg.selectors.statusWidget;

        function metaFromDom(widget, statusName) {
            const opt = widget.querySelector(`[data-status-option="${statusName}"]`);
            if (!opt) return null;
            return {
                name:    opt.dataset.statusOption,
                value:   opt.dataset.statusValue,
                label:   opt.dataset.statusLabel,
                classes: (opt.dataset.statusClasses || '').split(/\s+/).filter(Boolean),
                dot:     opt.dataset.statusDot,
            };
        }

        function closeAllMenus(except = null) {
            $$(widgetSel).forEach(w => {
                if (w === except) return;
                w.querySelector('[data-status-menu]')?.classList.add('hidden');
                w.querySelector('[data-status-trigger]')?.setAttribute('aria-expanded', 'false');
                w.querySelector('[data-status-chevron]')?.classList.remove('rotate-180');
            });
        }

        function applyStatus(widget, statusName) {
            const meta = metaFromDom(widget, statusName);
            if (!meta) return;

            const trigger = widget.querySelector('[data-status-trigger]');
            const label   = widget.querySelector('[data-status-label]');
            const dot     = widget.querySelector('[data-status-dot]');

            if (label) label.textContent = meta.label;

            if (trigger) {
                widget.querySelectorAll('[data-status-option]').forEach(o => {
                    (o.dataset.statusClasses || '').split(/\s+/).filter(Boolean)
                        .forEach(c => trigger.classList.remove(c));
                });
                meta.classes.forEach(c => trigger.classList.add(c));
            }

            if (dot) {
                widget.querySelectorAll('[data-status-option]').forEach(o => {
                    if (o.dataset.statusDot) dot.classList.remove(o.dataset.statusDot);
                });
                if (meta.dot) dot.classList.add(meta.dot);
            }

            widget.querySelectorAll('[data-status-option]').forEach(o => {
                const selected = o.dataset.statusOption === String(statusName);
                o.setAttribute('aria-selected', String(selected));
                o.classList.toggle('bg-indigo-50/70', selected);
                o.classList.toggle('dark:bg-indigo-950/40', selected);
                o.querySelector('.check-icon')?.remove();

                if (selected) {
                    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                    svg.setAttribute('class', 'check-icon h-3.5 w-3.5 text-indigo-600 dark:text-indigo-400');
                    svg.setAttribute('fill', 'none');
                    svg.setAttribute('stroke', 'currentColor');
                    svg.setAttribute('stroke-width', '2.5');
                    svg.setAttribute('viewBox', '0 0 24 24');
                    const p = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                    p.setAttribute('stroke-linecap', 'round');
                    p.setAttribute('stroke-linejoin', 'round');
                    p.setAttribute('d', 'm4.5 12.75 6 6 9-13.5');
                    svg.appendChild(p);
                    o.appendChild(svg);
                }
            });
        }

        function init() {
            $$(widgetSel).forEach(widget => {
                if (widget.dataset.statusBound === '1') return;
                widget.dataset.statusBound = '1';

                const trigger  = widget.querySelector('[data-status-trigger]');
                const menu     = widget.querySelector('[data-status-menu]');
                const loading  = widget.querySelector('[data-status-loading]');
                const errorEl  = widget.querySelector('[data-status-error]');
                const chevron  = widget.querySelector('[data-status-chevron]');
                const url      = widget.dataset.updateUrl;
                if (!trigger || !menu || !url) return;

                trigger.addEventListener('click', e => {
                    e.stopPropagation();
                    if (trigger.disabled) return;
                    const opening = menu.classList.contains('hidden');
                    closeAllMenus(widget);
                    menu.classList.toggle('hidden', !opening);
                    trigger.setAttribute('aria-expanded', String(opening));
                    chevron?.classList.toggle('rotate-180', opening);
                    if (opening) errorEl?.classList.add('hidden');
                });

                menu.addEventListener('click', e => e.stopPropagation());

                menu.querySelectorAll('[data-status-option]').forEach(opt => {
                    opt.addEventListener('click', async e => {
                        e.stopPropagation();
                        if (trigger.disabled) return;

                        const newStatus = opt.dataset.statusOption;
                        const current   = menu.querySelector('[data-status-option][aria-selected="true"]')?.dataset.statusOption;
                        if (newStatus === current) { closeAllMenus(); return; }

                        errorEl?.classList.add('hidden');
                        trigger.disabled = true;
                        trigger.classList.add('opacity-70', 'cursor-wait');
                        menu.querySelectorAll('[data-status-option]').forEach(i => i.disabled = true);
                        loading?.classList.remove('hidden');
                        loading?.classList.add('flex');

                        try {
                            const res = await fetch(url, {
                                method: 'PATCH',
                                credentials: 'same-origin',
                                headers: jsonHeaders(cfg.csrfToken),
                                body: JSON.stringify({ status: newStatus }),
                            });
                            const data = await res.json().catch(() => ({}));
                            if (!res.ok) throw new Error(
                                data?.errors?.status?.[0] ?? data?.message ?? `Request failed (${res.status})`
                            );
                            applyStatus(widget, data?.status_name ?? newStatus);
                            closeAllMenus();
                            notify('success', data?.message || 'User status updated.');
                        } catch (err) {
                            console.error('[status-update]', err);
                            const msg = err.message || 'Could not update user status.';
                            if (errorEl) { errorEl.textContent = msg; errorEl.classList.remove('hidden'); }
                            notify('error', msg);
                        } finally {
                            trigger.disabled = false;
                            trigger.classList.remove('opacity-70', 'cursor-wait');
                            menu.querySelectorAll('[data-status-option]').forEach(i => i.disabled = false);
                            loading?.classList.add('hidden');
                            loading?.classList.remove('flex');
                        }
                    });
                });
            });
        }

        return { init, closeAllMenus, applyStatus };
    }

    /* ════════════════════════════════════════════════════════════════
       6) SECTION: BULK STATUS
       ════════════════════════════════════════════════════════════════ */
    function initBulkStatus(cfg, { selection, status }) {
        const el = {
            drawer:    document.getElementById('bulkDrawer'),
            backdrop:  document.getElementById('bulkDrawerBackdrop'),
            panel:     document.getElementById('bulkDrawerPanel'),
            close:     document.getElementById('bulkDrawerClose'),
            cancel:    document.getElementById('bulkDrawerCancel'),
            apply:     document.getElementById('bulkDrawerApply'),
            count:     document.getElementById('bulkDrawerCount'),
            applyCount:document.getElementById('bulkDrawerApplyCount'),
            users:     document.getElementById('bulkDrawerUsers'),
            warning:   document.getElementById('bulkDrawerWarning'),
            error:     document.getElementById('bulkDrawerError'),
            openBtn:   document.getElementById('openBulkDrawer'),
        };

        function renderSelection() {
            const ids = selection.selectedIds();
            if (el.count) el.count.textContent = ids.length;
            if (el.applyCount) el.applyCount.textContent = ids.length;
            if (!el.users) return;

            el.users.innerHTML = '';
            ids.slice(0, 30).forEach(id => {
                const row  = document.querySelector(`[data-user-row][data-user-id="${id}"]`);
                const name = row?.querySelector('.text-sm.font-semibold')?.textContent?.trim() || `User #${id}`;
                const li = document.createElement('li');
                li.className = 'flex items-center gap-2';
                li.innerHTML = `
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-indigo-100 text-[9px] font-bold uppercase text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300">
                        ${name.substring(0, 2).toUpperCase()}
                    </span>
                    <span class="truncate">${name}</span>`;
                el.users.appendChild(li);
            });

            if (ids.length > 30) {
                const li = document.createElement('li');
                li.className = 'pt-1 text-[11px] font-semibold text-indigo-600 dark:text-indigo-400';
                li.textContent = `+ ${ids.length - 30} more`;
                el.users.appendChild(li);
            }
        }

        function open() {
            if (!selection.ensureSelectionOrWarn()) return;
            if (!el.drawer) { notify('error', 'Status drawer not found.'); return; }

            $$('input[name="bulk_status"]').forEach(r => { r.checked = false; });
            el.apply?.setAttribute('disabled', 'disabled');
            el.error?.classList.add('hidden');
            el.warning?.classList.add('hidden');
            renderSelection();

            el.drawer.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            requestAnimationFrame(() => {
                el.backdrop?.classList.remove('opacity-0');
                el.panel?.classList.remove('translate-x-full');
            });
            setTimeout(() => $('input[name="bulk_status"]')?.focus(), 250);
        }

        function close() {
            if (!el.drawer) return;
            el.backdrop?.classList.add('opacity-0');
            el.panel?.classList.add('translate-x-full');
            setTimeout(() => {
                el.drawer.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }

        async function submit() {
            if (!cfg.urls.bulkStatus) { notify('error', 'Bulk status endpoint not available.'); return; }
            const ids = selection.ensureSelectionOrWarn(); if (!ids) return;
            const chosen = $('input[name="bulk_status"]:checked')?.value;
            if (!chosen) { notify('warning', 'Choose a status first.'); return; }

            const originalHtml = el.apply?.innerHTML;
            if (el.apply) {
                el.apply.disabled = true;
                el.apply.innerHTML = `<svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg><span>Applying…</span>`;
            }
            el.error?.classList.add('hidden');

            try {
                const res = await fetch(cfg.urls.bulkStatus, {
                    method: 'PATCH', credentials: 'same-origin',
                    headers: jsonHeaders(cfg.csrfToken),
                    body: JSON.stringify({ ids, status: chosen }),
                });
                const data = await res.json().catch(() => ({}));
                if (!res.ok) throw new Error(
                    data?.errors?.status?.[0] ?? data?.errors?.ids?.[0] ?? data?.message ?? `Request failed (${res.status})`
                );

                ids.forEach(id => {
                    const widget = document.querySelector(`[data-user-row][data-user-id="${id}"] [data-status-widget]`);
                    if (widget) status.applyStatus(widget, data?.status_name ?? chosen);
                });

                selection.clear();
                close();
                notify('success', data?.message || 'Bulk status update complete.');
            } catch (err) {
                console.error('[bulk-status]', err);
                const msg = err.message || 'Bulk status update failed.';
                if (el.error) { el.error.textContent = msg; el.error.classList.remove('hidden'); }
                notify('error', msg);
            } finally {
                if (el.apply) { el.apply.disabled = false; el.apply.innerHTML = originalHtml; }
            }
        }

        function init() {
            el.openBtn?.addEventListener('click', open);
            el.close?.addEventListener('click', close);
            el.cancel?.addEventListener('click', close);
            el.backdrop?.addEventListener('click', close);
            el.apply?.addEventListener('click', submit);

            $$('input[name="bulk_status"]').forEach(radio => {
                radio.addEventListener('change', () => {
                    el.apply?.removeAttribute('disabled');
                    radio.value === 'Banned'
                        ? el.warning?.classList.remove('hidden')
                        : el.warning?.classList.add('hidden');
                });
            });
        }

        return { init, open, close };
    }

    /* ════════════════════════════════════════════════════════════════
       7) SECTION: BULK ROLE
       ════════════════════════════════════════════════════════════════ */
    function initBulkRole(cfg, { selection, dropdowns }) {
        async function submit(roleName, button) {
            if (!cfg.urls.bulkRole) { notify('error', 'Bulk role endpoint not available.'); return; }
            const ids = selection.ensureSelectionOrWarn(); if (!ids) return;
            dropdowns.closeAll();

            const confirmed = window.Swal
                ? (await window.Swal.fire({
                    title: `Assign "${roleName}" role?`,
                    text: `This will replace the roles of ${ids.length} user(s).`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, assign', cancelButtonText: 'Cancel',
                    reverseButtons: true, focusCancel: true,
                })).isConfirmed
                : confirm(`Assign "${roleName}" to ${ids.length} user(s)? Their existing roles will be replaced.`);
            if (!confirmed) return;

            const originalHtml = button?.innerHTML;
            if (button) {
                button.disabled = true;
                button.innerHTML = `<svg class="h-3.5 w-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg><span class="flex-1">Assigning…</span>`;
            }

            try {
                const res = await fetch(cfg.urls.bulkRole, {
                    method: 'PATCH', credentials: 'same-origin',
                    headers: jsonHeaders(cfg.csrfToken),
                    body: JSON.stringify({ ids, role: roleName }),
                });
                const data = await res.json().catch(() => ({}));
                if (!res.ok) throw new Error(
                    data?.errors?.role?.[0] ?? data?.errors?.ids?.[0] ?? data?.message ?? `Request failed (${res.status})`
                );
                notify('success', data?.message || 'Role assigned successfully.');
                setTimeout(() => window.location.reload(), 700);
            } catch (err) {
                console.error('[bulk-role]', err);
                notify('error', err.message || 'Bulk role update failed.');
            } finally {
                if (button) { button.disabled = false; button.innerHTML = originalHtml; }
            }
        }

        async function openPicker() {
            if (!selection.ensureSelectionOrWarn()) return;

            const roleButtons = $$(cfg.selectors.roleBtns);
            if (roleButtons.length === 0) { notify('error', 'No roles available.'); return; }

            const options = {};
            roleButtons.forEach(btn => { options[btn.dataset.bulkRole] = btn.dataset.bulkRole; });

            const result = window.Swal
                ? await window.Swal.fire({
                    title: `Change role for ${selection.selectedIds().length} user(s)`,
                    input: 'select',
                    inputOptions: options,
                    inputPlaceholder: 'Select a role…',
                    showCancelButton: true,
                    confirmButtonText: 'Assign', cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    inputValidator: v => !v && 'Pick a role',
                })
                : { isConfirmed: false };

            if (result.isConfirmed && result.value) submit(result.value, null);
        }

        function init() { /* bound via delegated click in app() */ }

        return { init, submit, openPicker };
    }

    /* ════════════════════════════════════════════════════════════════
       8) SECTION: BULK OPERATIONS
       ════════════════════════════════════════════════════════════════ */
    function initOpsDrawer(cfg, { selection }) {
        const el = {
            drawer:     document.getElementById('bulkOpsDrawer'),
            backdrop:   document.getElementById('bulkOpsBackdrop'),
            panel:      document.getElementById('bulkOpsPanel'),
            close:      document.getElementById('bulkOpsClose'),
            cancel:     document.getElementById('bulkOpsCancel'),
            apply:      document.getElementById('bulkOpsApply'),
            count:      document.getElementById('bulkOpsCount'),
            applyCount: document.getElementById('bulkOpsApplyCount'),
            users:      document.getElementById('bulkOpsUsers'),
            warning:    document.getElementById('bulkOpsWarning'),
            warningTxt: document.getElementById('bulkOpsWarningText'),
            error:      document.getElementById('bulkOpsError'),
        };

        function renderSelection() {
            const ids = selection.selectedIds();
            if (el.count) el.count.textContent = ids.length;
            if (el.applyCount) el.applyCount.textContent = ids.length;
            if (!el.users) return;

            el.users.innerHTML = '';
            ids.slice(0, 30).forEach(id => {
                const row  = document.querySelector(`[data-user-row][data-user-id="${id}"]`);
                const name = row?.querySelector('.text-sm.font-semibold')?.textContent?.trim() || `User #${id}`;
                const li = document.createElement('li');
                li.className = 'flex items-center gap-2';
                li.innerHTML = `
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-amber-100 text-[9px] font-bold uppercase text-amber-700 dark:bg-amber-950/60 dark:text-amber-300">
                        ${name.substring(0, 2).toUpperCase()}
                    </span>
                    <span class="truncate">${name}</span>`;
                el.users.appendChild(li);
            });

            if (ids.length > 30) {
                const li = document.createElement('li');
                li.className = 'pt-1 text-[11px] font-semibold text-amber-600 dark:text-amber-400';
                li.textContent = `+ ${ids.length - 30} more`;
                el.users.appendChild(li);
            }
        }

        function open(operation) {
            if (!selection.ensureSelectionOrWarn()) return;
            if (!el.drawer) { notify('error', 'Operations drawer not found.'); return; }

            $$('input[name="bulk_operation"]').forEach(r => { r.checked = false; });
            el.apply?.setAttribute('disabled', 'disabled');
            el.error?.classList.add('hidden');
            el.warning?.classList.add('hidden');

            if (operation) {
                const radio = $(`input[name="bulk_operation"][value="${operation}"]`);
                if (radio) {
                    radio.checked = true;
                    radio.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }

            renderSelection();
            el.drawer.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            requestAnimationFrame(() => {
                el.backdrop?.classList.remove('opacity-0');
                el.panel?.classList.remove('translate-x-full');
            });
        }

        function close() {
            if (!el.drawer) return;
            el.backdrop?.classList.add('opacity-0');
            el.panel?.classList.add('translate-x-full');
            setTimeout(() => {
                el.drawer.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }

        async function submit() {
            if (!cfg.urls.bulkOp) { notify('error', 'Bulk operation endpoint not available.'); return; }
            const ids = selection.ensureSelectionOrWarn(); if (!ids) return;

            const chosen = $('input[name="bulk_operation"]:checked')?.value;
            if (!chosen) { notify('warning', 'Choose an operation first.'); return; }

            if (['delete', 'force-delete'].includes(chosen)) {
                const verb = chosen === 'force-delete' ? 'Permanently delete' : 'Delete';
                const confirmed = window.Swal
                    ? (await window.Swal.fire({
                        title: `${verb} ${ids.length} user(s)?`,
                        text: chosen === 'force-delete'
                            ? 'This action is IRREVERSIBLE. All data will be destroyed.'
                            : 'Users will be moved to the trash. You can restore them later.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: `Yes, ${verb.toLowerCase()}`,
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#ef4444',
                        reverseButtons: true, focusCancel: true,
                    })).isConfirmed
                    : confirm(`${verb} ${ids.length} user(s)?`);
                if (!confirmed) return;
            }

            const originalHtml = el.apply?.innerHTML;
            if (el.apply) {
                el.apply.disabled = true;
                el.apply.innerHTML = `<svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg><span>Running…</span>`;
            }
            el.error?.classList.add('hidden');

            try {
                const res = await fetch(cfg.urls.bulkOp, {
                    method: 'PATCH', credentials: 'same-origin',
                    headers: jsonHeaders(cfg.csrfToken),
                    body: JSON.stringify({ ids, operation: chosen }),
                });
                const data = await res.json().catch(() => ({}));
                if (!res.ok) throw new Error(
                    data?.errors?.operation?.[0] ?? data?.errors?.ids?.[0] ?? data?.message ?? `Request failed (${res.status})`
                );

                notify('success', data?.message || 'Operation complete.');
                close();

                if (data?.operation === 'export' && data?.redirect) { window.open(data.redirect, '_blank'); return; }
                if (data?.operation === 'view-employees' && data?.redirect) { window.location.href = data.redirect; return; }
                setTimeout(() => window.location.reload(), 700);
            } catch (err) {
                console.error('[bulk-operation]', err);
                const msg = err.message || 'Operation failed.';
                if (el.error) { el.error.textContent = msg; el.error.classList.remove('hidden'); }
                notify('error', msg);
            } finally {
                if (el.apply) { el.apply.disabled = false; el.apply.innerHTML = originalHtml; }
            }
        }

        function init() {
            el.close?.addEventListener('click', close);
            el.cancel?.addEventListener('click', close);
            el.backdrop?.addEventListener('click', close);
            el.apply?.addEventListener('click', submit);

            $$('input[name="bulk_operation"]').forEach(radio => {
                radio.addEventListener('change', () => {
                    el.apply?.removeAttribute('disabled');
                    const warning = cfg.opsWarnings[radio.value];
                    if (warning) {
                        if (el.warningTxt) el.warningTxt.textContent = warning;
                        el.warning?.classList.remove('hidden');
                    } else {
                        el.warning?.classList.add('hidden');
                    }
                });
            });
        }

        return { init, open, close };
    }

    /* ════════════════════════════════════════════════════════════════
       9) SECTION: PROFILE DATA
       ════════════════════════════════════════════════════════════════ */
    function initProfileData(cfg) {
        let data = {};
        function init() {
            const el = $(cfg.selectors.usersDataEl);
            if (el) {
                try { data = JSON.parse(el.textContent || '{}'); }
                catch (e) { console.error('Could not parse usersData JSON:', e); }
            }
        }
        return { init, get: () => data };
    }

    /* ════════════════════════════════════════════════════════════════
       10) SECTION: MAKE EMPLOYEE / UPDATE EMPLOYEE
       ════════════════════════════════════════════════════════════════ */
    function initMakeEmployee(cfg, { notify: notifyFn }) {
        const sel = cfg.selectors;
        const el = {
            modal:       $(sel.makeEmployeeModal),
            form:        $(sel.makeEmployeeForm),
            nameTitle:   $(sel.makeEmployeeName),
            submitBtn:   $(sel.makeEmployeeSubmit),

            headerTitle:      $(sel.headerTitle),
            headerBadge:      $(sel.headerBadge),
            headerBadgeLabel: $(sel.headerBadgeLabel),
            headerBadgeIcon:  $(sel.headerBadgeIcon),

            rightStatusBadge:     $(sel.rightStatusBadge),
            rightStatusIcon:      $(sel.rightStatusIcon),
            rightStatusLabel:     $(sel.rightStatusLabel),
            rightJobProfileBadge: $(sel.rightJobProfileBadge),
            rightJobProfileIcon:  $(sel.rightJobProfileIcon),
            rightJobProfileLabel: $(sel.rightJobProfileLabel),
            rightJobProfileBtn:   $(sel.rightJobProfileBtn),

            jobProfileSection:   $(sel.jobProfileSection),
            noJobProfileBox:     $(sel.noJobProfileBox),
            createJobProfileBtn: $(sel.createJobProfileBtn),

            name:        $(sel.meFields.name),
            dob:         $(sel.meFields.dob),
            age:         $(sel.meFields.age),
            gender:      $(sel.meFields.gender),
            zip:         $(sel.meFields.zip),
            department:  $(sel.meFields.department),
            branch:      $(sel.meFields.branch),
            country:     $(sel.meFields.country),
            state:       $(sel.meFields.state),
            city:        $(sel.meFields.city),
            address:     $(sel.meFields.address),
            error:       $(sel.meFields.error),
        };

        function showHeaderAsEmployee() {
            if (el.headerTitle) el.headerTitle.textContent = 'Employee Information';
            if (el.headerBadge) {
                el.headerBadge.classList.remove('hidden');
                el.headerBadge.classList.add('inline-flex',
                    'border-indigo-200', 'bg-indigo-50', 'text-indigo-700',
                    'dark:border-indigo-800', 'dark:bg-indigo-950/60', 'dark:text-indigo-300');
            }
            if (el.headerBadgeLabel) el.headerBadgeLabel.textContent = 'Employee';
            if (el.headerBadgeIcon)  el.headerBadgeIcon.className = 'ri-user-star-line text-base';
        }

        function showHeaderAsUser() {
            if (el.headerTitle) el.headerTitle.textContent = 'Convert to Employee';
            if (el.headerBadge) {
                el.headerBadge.classList.add('hidden');
                el.headerBadge.classList.remove('inline-flex',
                    'border-indigo-200', 'bg-indigo-50', 'text-indigo-700',
                    'dark:border-indigo-800', 'dark:bg-indigo-950/60', 'dark:text-indigo-300');
            }
            if (el.headerBadgeLabel) el.headerBadgeLabel.textContent = 'Employee';
            if (el.headerBadgeIcon)  el.headerBadgeIcon.className = 'ri-user-star-line text-base';
        }

        function setRightStatus(isEmployee) {
            if (!el.rightStatusBadge) return;

            el.rightStatusBadge.classList.remove(
                'border-indigo-200', 'bg-indigo-50', 'text-indigo-700',
                'dark:border-indigo-800', 'dark:bg-indigo-950/60', 'dark:text-indigo-300',
                'border-gray-200', 'bg-gray-100', 'text-gray-600',
                'dark:border-gray-700', 'dark:bg-gray-800', 'dark:text-gray-300'
            );

            if (isEmployee) {
                el.rightStatusBadge.classList.add(
                    'border-indigo-200', 'bg-indigo-50', 'text-indigo-700',
                    'dark:border-indigo-800', 'dark:bg-indigo-950/60', 'dark:text-indigo-300'
                );
                if (el.rightStatusIcon)  el.rightStatusIcon.className  = 'ri-user-star-line text-base';
                if (el.rightStatusLabel) el.rightStatusLabel.textContent = 'Employee';
            } else {
                el.rightStatusBadge.classList.add(
                    'border-gray-200', 'bg-gray-100', 'text-gray-600',
                    'dark:border-gray-700', 'dark:bg-gray-800', 'dark:text-gray-300'
                );
                if (el.rightStatusIcon)  el.rightStatusIcon.className  = 'ri-user-line text-base';
                if (el.rightStatusLabel) el.rightStatusLabel.textContent = 'Non Employee';
            }
        }

        function setRightJobProfile(hasJobProfile, userId, username) {
            const badge = el.rightJobProfileBadge;
            if (!badge) return;

            badge.classList.remove('hidden',
                'border-emerald-200', 'bg-emerald-50', 'text-emerald-700',
                'dark:border-emerald-800', 'dark:bg-emerald-950/60', 'dark:text-emerald-300',
                'border-gray-200', 'bg-gray-100', 'text-gray-600',
                'dark:border-gray-700', 'dark:bg-gray-800', 'dark:text-gray-300');

            if (hasJobProfile) {
                badge.classList.add(
                    'border-emerald-200', 'bg-emerald-50', 'text-emerald-700',
                    'dark:border-emerald-800', 'dark:bg-emerald-950/60', 'dark:text-emerald-300');
                if (el.rightJobProfileIcon)  el.rightJobProfileIcon.className = 'ri-profile-line text-base';
                if (el.rightJobProfileLabel) el.rightJobProfileLabel.textContent = 'Job Profile';

                if (el.rightJobProfileBtn) {
                    el.rightJobProfileBtn.classList.remove('hidden');
                    el.rightJobProfileBtn.onclick = (ev) => {
                        ev.preventDefault();
                        const url = `${cfg.urls.jobProfileCreate}?user_id=${encodeURIComponent(userId)}`;
                        if (typeof window.openJobProfileModal === 'function') {
                            window.openJobProfileModal(userId, username || '');
                        } else {
                            window.open(url, '_blank', 'noopener');
                        }
                    };
                }
            } else {
                badge.classList.add(
                    'border-gray-200', 'bg-gray-100', 'text-gray-600',
                    'dark:border-gray-700', 'dark:bg-gray-800', 'dark:text-gray-300');
                if (el.rightJobProfileIcon)  el.rightJobProfileIcon.className = 'ri-profile-line text-base';
                if (el.rightJobProfileLabel) el.rightJobProfileLabel.textContent = 'Job Profile — none';
                if (el.rightJobProfileBtn)   el.rightJobProfileBtn.classList.add('hidden');
            }
        }

        function hideRightJobProfile() {
            el.rightJobProfileBadge?.classList.add('hidden');
            el.rightJobProfileBtn?.classList.add('hidden');
        }

        function fillJobProfile(jp) {
            const section = el.jobProfileSection;
            if (!section) return;

            const has = !!(jp && (jp.id || jp.designation));
            section.classList.toggle('hidden', !has);
            if (el.noJobProfileBox) {
                el.noJobProfileBox.classList.toggle('hidden', has || !jp?.__employee__);
            }

            if (!has) return;

            section.querySelectorAll('[data-field]').forEach(input => {
                const key = input.dataset.field;
                const val = jp[key] ?? '';
                if (input.type === 'checkbox') input.checked = !!val;
                else input.value = val ?? '';
            });
        }

        function clearJobProfile() {
            const section = el.jobProfileSection;
            if (section) {
                section.classList.add('hidden');
                section.querySelectorAll('[data-field]').forEach(input => {
                    if (input.type === 'checkbox') input.checked = false;
                    else input.value = '';
                });
            }
            if (el.noJobProfileBox) el.noJobProfileBox.classList.add('hidden');
        }

        function computeAge() {
            if (!el.dob || !el.age) return;
            const dob = el.dob.value;
            if (!dob) { el.age.value = ''; return; }
            const birth = new Date(dob);
            if (isNaN(birth.getTime())) { el.age.value = ''; return; }
            const today = new Date();
            let age = today.getFullYear() - birth.getFullYear();
            const m = today.getMonth() - birth.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--;
            el.age.value = age >= 0 ? age : '';
        }

        function normalizeGender(value) {
            if (!value) return '';
            const v = String(value).trim().toLowerCase();
            const map = {
                'm': 'male', 'male': 'male', 'man': 'male',
                'f': 'female', 'female': 'female', 'woman': 'female',
                'o': 'other', 'other': 'other', 'non-binary': 'other', 'nonbinary': 'other',
            };
            return map[v] || '';
        }

        function setSelectValue(selectEl, value) {
            if (!selectEl) return;
            if (value === null || value === undefined) { selectEl.value = ''; return; }
            const str = String(value);
            let opt = Array.from(selectEl.options).find(o => String(o.value) === str);
            if (!opt) { opt = new Option(str, str, true, true); selectEl.add(opt); }
            selectEl.value = str;
        }

        function getSubmitLabelEl() {
            if (!el.submitBtn) return null;
            return el.submitBtn.querySelector('[data-role="submit-label"]')
                || el.submitBtn.querySelector('span');
        }

        function setSubmitState(mode) {
            if (!el.submitBtn) return;

            const labelEl = getSubmitLabelEl();

            if (mode === 'update') {
                el.submitBtn.disabled = false;
                el.submitBtn.removeAttribute('title');
                if (labelEl) labelEl.textContent = 'Update information';
                else el.submitBtn.textContent = 'Update information';

                el.submitBtn.classList.remove('bg-emerald-600', 'hover:bg-emerald-700',
                                              'focus:ring-emerald-500/40');
                el.submitBtn.classList.add('bg-indigo-600', 'hover:bg-indigo-700',
                                           'focus:ring-indigo-500/40');
            } else if (mode === 'make') {
                el.submitBtn.disabled = false;
                el.submitBtn.removeAttribute('title');
                if (labelEl) labelEl.textContent = 'Yes, make employee';
                else el.submitBtn.textContent = 'Yes, make employee';

                el.submitBtn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700',
                                              'focus:ring-indigo-500/40');
                el.submitBtn.classList.add('bg-emerald-600', 'hover:bg-emerald-700',
                                           'focus:ring-emerald-500/40');
            } else if (mode === 'disabled') {
                el.submitBtn.disabled = true;
            }
        }

        function resetErrorBanner() {
            if (!el.error) return;
            el.error.className =
                'mt-4 hidden rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700 ' +
                'dark:border-red-900/40 dark:bg-red-950/30 dark:text-red-300';
            el.error.textContent = '';
        }

        function showInfoBanner(username) {
            if (!el.error) return;
            el.error.className =
                'mt-4 rounded-xl border border-amber-200 bg-amber-50 p-3 text-sm text-amber-800 ' +
                'dark:border-amber-900/40 dark:bg-amber-950/30 dark:text-amber-200';
            el.error.innerHTML = `
                <div class="flex items-start gap-2">
                    <i class="ri-information-line mt-0.5 text-base"></i>
                    <div>
                        <p class="font-semibold">${username} is already an employee.</p>
                        <p class="mt-0.5 text-xs">You can update their employment information below.</p>
                    </div>
                </div>`;
            el.error.classList.remove('hidden');
        }

        function showErrorBanner(message) {
            if (!el.error) return;
            el.error.className =
                'mt-4 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700 ' +
                'dark:border-red-900/40 dark:bg-red-950/30 dark:text-red-300';
            el.error.textContent = message;
            el.error.classList.remove('hidden');
        }

        async function loadStates(countryId, selectedStateId) {
            if (!el.state) return;
            el.state.innerHTML = '<option value="">Select state…</option>';
            if (el.city) el.city.innerHTML = '<option value="">Select city…</option>';
            if (!countryId) return;

            try {
                const res = await fetch(`${cfg.urls.states}?country_id=${encodeURIComponent(countryId)}`, {
                    credentials: 'same-origin',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                });
                if (!res.ok) return;
                const list = await res.json();
                (list || []).forEach(s => {
                    el.state.add(new Option(s.name, s.id, false, String(s.id) === String(selectedStateId)));
                });
            } catch (e) { console.warn('[makeEmployee] loadStates:', e); }
        }

        async function loadCities(stateId, selectedCityId) {
            if (!el.city) return;
            el.city.innerHTML = '<option value="">Select city…</option>';
            if (!stateId) return;

            try {
                const res = await fetch(`${cfg.urls.cities}?state_id=${encodeURIComponent(stateId)}`, {
                    credentials: 'same-origin',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                });
                if (!res.ok) return;
                const list = await res.json();
                (list || []).forEach(c => {
                    el.city.add(new Option(c.name, c.id, false, String(c.id) === String(selectedCityId)));
                });
            } catch (e) { console.warn('[makeEmployee] loadCities:', e); }
        }

        function setFieldError(input, message) {
            if (!input) return;
            const wrap = input.closest('div');
            const errId = input.id + '_error';
            let errEl = wrap?.querySelector('#' + errId);

            input.classList.remove('border-gray-300', 'dark:border-gray-600',
                                   'focus:border-emerald-500', 'focus:ring-emerald-500/30');
            input.classList.add('border-red-400', 'dark:border-red-500',
                                'focus:border-red-400', 'focus:ring-red-500/30');
            input.setAttribute('aria-invalid', 'true');

            if (!errEl && wrap) {
                errEl = document.createElement('p');
                errEl.id = errId;
                errEl.className = 'mt-1 flex items-center gap-1 text-xs font-medium text-red-600 dark:text-red-400';
                errEl.innerHTML = '<i class="ri-error-warning-line text-sm"></i><span></span>';
                wrap.appendChild(errEl);
            }
            if (errEl) {
                errEl.querySelector('span').textContent = message;
                errEl.classList.remove('hidden');
            }
            input.focus({ preventScroll: false });
            input.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        function clearFieldError(input) {
            if (!input) return;
            input.classList.remove('border-red-400', 'dark:border-red-500',
                                   'focus:border-red-400', 'focus:ring-red-500/30');
            input.classList.add('border-gray-300', 'dark:border-gray-600',
                                'focus:border-emerald-500', 'focus:ring-emerald-500/30');
            input.setAttribute('aria-invalid', 'false');
            input.closest('div')?.querySelector('#' + input.id + '_error')?.classList.add('hidden');
        }

        async function open(id, username) {
            if (!el.modal || !el.form) { notifyFn('error', 'Make-employee modal not found.'); return; }

            el.form.reset();
            resetErrorBanner();
            clearJobProfile();
            showHeaderAsUser();
            setRightStatus(false);
            hideRightJobProfile();
            if (el.state) el.state.innerHTML = '<option value="">Select state…</option>';
            if (el.city)  el.city.innerHTML  = '<option value="">Select city…</option>';
            if (el.age)   el.age.value = '';
            if (el.nameTitle) el.nameTitle.textContent = username || 'User';

            setSubmitState('disabled');
            setSubmitState('make');
            setSubmitState('disabled');

            el.form.action = `${cfg.urls.makeEmployeeDataBase}/${encodeURIComponent(id)}/make-employee`;

            el.modal.classList.remove('hidden');
            el.modal.classList.add('flex');
            document.body.style.overflow = 'hidden';

            try {
                const res = await fetch(
                    `${cfg.urls.makeEmployeeDataBase}/${encodeURIComponent(id)}/make-employee/data`,
                    { credentials: 'same-origin', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } }
                );
                const json = await parseJson(res);
                if (!res.ok) throw new Error(json?.message || 'Failed to load user data.');

                const d = json.data || {};
                const p = d.profile || {};

                const resolvedName = d.name || d.username || username || '';
                if (el.nameTitle) el.nameTitle.textContent = resolvedName;
                if (el.name) el.name.value = resolvedName;

                if (el.dob && p.birthdate) { el.dob.value = p.birthdate; computeAge(); }
                if (el.gender) el.gender.value = normalizeGender(p.gender);
                if (el.zip && p.zip_code) el.zip.value = p.zip_code;
                if (el.address && p.address) el.address.value = p.address;

                const deptId   = p.department_id ?? d.employee?.department_id ?? d.department_id ?? '';
                const branchId = p.branch_id     ?? d.employee?.branch_id     ?? d.branch_id     ?? '';
                setSelectValue(el.department, deptId);
                setSelectValue(el.branch, branchId);

                if (el.country && p.country_id) el.country.value = p.country_id;
                if (p.country_id) {
                    await loadStates(p.country_id, p.state_id);
                    if (p.state_id) {
                        setSelectValue(el.state, p.state_id);
                        await loadCities(p.state_id, p.city_id);
                        setSelectValue(el.city, p.city_id);
                    }
                }

                if (d.make_emp_url) el.form.action = d.make_emp_url;

                if (d.is_employee) {
                    el.form.action =
                        d.update_url
                        || `${cfg.urls.makeEmployeeDataBase}/${encodeURIComponent(id)}/update-employee`;

                    showInfoBanner(d.username || username || 'This user');
                    setSubmitState('update');
                    showHeaderAsEmployee();
                    setRightStatus(true);

                    const hasJobProfile = !!(d.job_profile_id || d.job_profile?.id);
                    setRightJobProfile(hasJobProfile, d.id, d.username || username);

                    if (hasJobProfile) {
                        fillJobProfile({ ...(d.job_profile || {}), id: d.job_profile_id, __employee__: true });
                    } else {
                        clearJobProfile();
                        if (el.noJobProfileBox) el.noJobProfileBox.classList.remove('hidden');

                        if (el.createJobProfileBtn) {
                            el.createJobProfileBtn.onclick = () => {
                                const url = `${cfg.urls.jobProfileCreate}?user_id=${encodeURIComponent(d.id)}`;
                                if (typeof window.openJobProfileModal === 'function') {
                                    window.openJobProfileModal(d.id, d.username || username);
                                } else {
                                    window.open(url, '_blank', 'noopener');
                                }
                            };
                        }
                    }
                    return;
                }

                clearJobProfile();
                showHeaderAsUser();
                setRightStatus(false);
                hideRightJobProfile();
                setSubmitState('make');
            } catch (err) {
                console.error('[makeEmployee] load failed:', err);
                showErrorBanner(err.message || 'Failed to load user data.');
                setRightStatus(false);
                hideRightJobProfile();
                notifyFn('error', err.message || 'Failed to load user data.');
            }
        }

        function close() {
            if (!el.modal) return;
            el.modal.classList.add('hidden');
            el.modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        function init() {
            if (el.dob && !el.dob.dataset.ageBound) {
                el.dob.dataset.ageBound = '1';
                el.dob.addEventListener('change', computeAge);
            }
            if (el.country && !el.country.dataset.cascadeBound) {
                el.country.dataset.cascadeBound = '1';
                el.country.addEventListener('change', e => loadStates(e.target.value));
            }
            if (el.state && !el.state.dataset.cascadeBound) {
                el.state.dataset.cascadeBound = '1';
                el.state.addEventListener('change', e => loadCities(e.target.value));
            }
            if (el.form && !el.form.dataset.submitBound) {
                el.form.dataset.submitBound = '1';

                el.form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    e.stopPropagation();

                    const required = [
                        { input: el.name,       label: 'Full name' },
                        { input: el.dob,        label: 'Date of birth' },
                        { input: el.gender,     label: 'Gender' },
                        { input: el.department, label: 'Department' },
                    ];
                    let firstInvalid = null;
                    required.forEach(({ input, label }) => {
                        if (!input) return;
                        const empty = input.type === 'date' ? !input.value : !input.value?.trim();
                        if (empty) { setFieldError(input, label + ' is required.'); firstInvalid = firstInvalid || input; }
                        else clearFieldError(input);
                    });
                    if (el.dob?.value && new Date(el.dob.value) > new Date()) {
                        setFieldError(el.dob, 'Date of birth cannot be in the future.');
                        firstInvalid = firstInvalid || el.dob;
                    }
                    if (firstInvalid) {
                        notifyFn('warning', 'Please complete all required fields.');
                        return;
                    }

                    if (typeof window.setButtonLoading === 'function') {
                        window.setButtonLoading('makeEmployeeSubmit', true);
                    }
                    if (typeof window.showLoader === 'function') {
                        window.showLoader('Saving…');
                    }

                    try {
                        const res = await fetch(el.form.action, {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: new FormData(el.form),
                        });

                        const data = await res.json().catch(() => ({}));

                        if (res.status === 422 && data?.errors) {
                            let hadFieldError = false;
                            for (const [field, msgs] of Object.entries(data.errors)) {
                                const input = el.form.querySelector(`[name="${field}"]`);
                                if (input) {
                                    setFieldError(input, Array.isArray(msgs) ? msgs[0] : String(msgs));
                                    hadFieldError = true;
                                }
                            }
                            const summary = Object.values(data.errors).flat().slice(0, 3).join(' · ');
                            notifyFn('error', summary || data?.message || 'Please fix the highlighted fields.');
                            if (!hadFieldError) showErrorBanner(data?.message || 'Validation failed.');
                            return;
                        }

                        if (!res.ok || data?.success === false) {
                            throw new Error(data?.message || data?.error || `Save failed (HTTP ${res.status}).`);
                        }

                        notifyFn('success', data.message || 'Saved successfully.');
                        close();

                        if (data?.redirect_url) {
                            if (window.location.pathname === new URL(data.redirect_url, window.location.origin).pathname) {
                                window.location.reload();
                            } else {
                                window.location.href = data.redirect_url;
                            }
                            return;
                        }

                        setTimeout(() => window.location.reload(), 300);

                    } catch (err) {
                        console.error('[makeEmployee submit]', err);
                        const msg = err.message || 'Save failed.';
                        showErrorBanner(msg);
                        notifyFn('error', msg);
                    } finally {
                        if (typeof window.setButtonLoading === 'function') {
                            window.setButtonLoading('makeEmployeeSubmit', false);
                        }
                    }
                });

                [el.name, el.dob, el.gender, el.department].forEach(input => {
                    if (!input || input.dataset.validationBound) return;
                    input.dataset.validationBound = '1';
                    input.addEventListener('change', () => { if (input.value) clearFieldError(input); });
                    input.addEventListener('input',  () => { if (input.value) clearFieldError(input); });
                });
            }
            el.modal?.addEventListener('click', e => { if (e.target === el.modal) close(); });
        }

        return { init, open, close, computeAge };
    }

    /* ════════════════════════════════════════════════════════════════
       11) SECTION: DELETE CONFIRMATION
       ════════════════════════════════════════════════════════════════ */
    function initDelete(cfg) {
        function remove(id) {
            const form = document.getElementById('delete-form-' + id);
            if (!form) { console.error('Delete form not found for ID:', id); return; }

            if (window.Swal?.fire) {
                window.Swal.fire({
                    title: 'Delete this user?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete', cancelButtonText: 'Cancel',
                    reverseButtons: true, focusCancel: true, buttonsStyling: false,
                    customClass: {
                        popup: 'modern-delete-modal', title: 'modern-delete-title',
                        htmlContainer: 'modern-delete-text', icon: 'modern-delete-icon',
                        confirmButton: 'modern-delete-confirm', cancelButton: 'modern-delete-cancel',
                    },
                }).then(r => { if (r.isConfirmed) form.submit(); });
            } else if (confirm('Delete this user? This action cannot be undone.')) {
                form.submit();
            }
        }
        return { init: () => {}, remove };
    }

    /* ════════════════════════════════════════════════════════════════
       12) SECTION: FILTER PANEL
       ════════════════════════════════════════════════════════════════ */
    function initFilterPanel(cfg) {
        const panel   = $(cfg.selectors.filterPanel);
        const toggle  = $(cfg.selectors.toggleFilterBtn);
        const closeBt = $(cfg.selectors.closeFilterBtn);
        const chevron = $(cfg.selectors.filterChevron);

        function open() {
            if (!panel) return;
            panel.classList.add('is-open');
            panel.style.maxHeight = panel.scrollHeight + 'px';
            toggle?.setAttribute('aria-expanded', 'true');
            if (chevron) chevron.style.transform = 'rotate(180deg)';
        }
        function close() {
            if (!panel) return;
            panel.style.maxHeight = null;
            panel.classList.remove('is-open');
            toggle?.setAttribute('aria-expanded', 'false');
            if (chevron) chevron.style.transform = 'rotate(0deg)';
        }

        function init() {
            if (toggle && !toggle.dataset.filterBound) {
                toggle.dataset.filterBound = '1';
                toggle.addEventListener('click', e => {
                    e.preventDefault(); e.stopPropagation();
                    panel?.classList.contains('is-open') ? close() : open();
                });
            }
            if (closeBt && !closeBt.dataset.filterBound) {
                closeBt.dataset.filterBound = '1';
                closeBt.addEventListener('click', e => {
                    e.preventDefault(); e.stopPropagation();
                    close();
                });
            }
            if (cfg.openFilterOnLoad) open();
        }
        return { init, open, close };
    }

    /* ════════════════════════════════════════════════════════════════
       13) SECTION: CRITERIA SYNC
       ════════════════════════════════════════════════════════════════ */
    function initCriteriaSync(cfg) {
        function sync() {
            const selected = $(cfg.selectors.criteriaRadio + ':checked')?.value || 'name';
            $$(cfg.selectors.criteriaField).forEach(field => {
                field.style.display = field.dataset.criteria === selected ? '' : 'none';
            });
        }
        function init() {
            $$(cfg.selectors.criteriaRadio).forEach(r => {
                if (r.dataset.criteriaBound === '1') return;
                r.dataset.criteriaBound = '1';
                r.addEventListener('change', sync);
            });
            sync();
        }
        return { init, sync };
    }

    /* ════════════════════════════════════════════════════════════════
       14) SECTION: DOWNLOADS
       ════════════════════════════════════════════════════════════════ */
    function initDownloads(cfg) {
        const toggle  = $(cfg.selectors.downloadToggle);
        const menu    = $(cfg.selectors.downloadMenu);
        const wrap    = $(cfg.selectors.downloadWrap);

        function closeMenu() {
            menu?.classList.add('hidden');
            toggle?.setAttribute('aria-expanded', 'false');
        }

        function init() {
            if (toggle && menu && !toggle.dataset.dlBound) {
                toggle.dataset.dlBound = '1';
                toggle.addEventListener('click', e => {
                    e.preventDefault(); e.stopPropagation();
                    const isHidden = menu.classList.contains('hidden');
                    menu.classList.toggle('hidden', !isHidden);
                    toggle.setAttribute('aria-expanded', String(isHidden));
                });
            }

            $$(cfg.selectors.downloadItem).forEach(item => {
                if (item.dataset.downloadBound === '1') return;
                item.dataset.downloadBound = '1';
                item.addEventListener('click', function () {
                    const spinner  = this.querySelector('.spinner-btn');
                    const label    = this.querySelector('.download-label');
                    const original = label?.textContent ?? '';
                    spinner?.classList.remove('hidden');
                    if (label) label.textContent = 'Downloading…';
                    setTimeout(() => {
                        spinner?.classList.add('hidden');
                        if (label) label.textContent = original;
                    }, 3000);
                    closeMenu();
                });
            });
        }
        return { init, closeMenu, wrap };
    }

    /* ════════════════════════════════════════════════════════════════
       15) SECTION: GLOBAL LISTENERS
       ════════════════════════════════════════════════════════════════ */
    function initGlobalListeners(cfg, refs) {
        function init() {
            document.addEventListener('click', e => {
                /* ── NEVER swallow a real navigation link ── */
                const anchor = e.target.closest('a[href]');
                if (anchor && !anchor.hasAttribute('data-no-native')) {
                    /* Allow native navigation. Just close dropdowns quietly. */
                    refs.dropdowns.closeAll();
                    refs.status.closeAllMenus();
                    if (refs.downloads.wrap && !refs.downloads.wrap.contains(e.target)) {
                        refs.downloads.closeMenu();
                    }
                    return;
                }

                if (e.target.closest('[data-bulk-clear]')) {
                    e.preventDefault();
                    refs.selection.clear();
                    return;
                }
                if (e.target.closest('[data-open-bulk-status]')) {
                    e.preventDefault();
                    refs.dropdowns.closeAll();
                    refs.bulkStatus.open();
                    return;
                }
                if (e.target.closest('[data-open-bulk-role]')) {
                    e.preventDefault();
                    refs.dropdowns.closeAll();
                    refs.bulkRole.openPicker();
                    return;
                }
                const roleBtn = e.target.closest('[data-bulk-role]');
                if (roleBtn) {
                    e.preventDefault();
                    refs.bulkRole.submit(roleBtn.dataset.bulkRole, roleBtn);
                    return;
                }
                const opBtn = e.target.closest('[data-bulk-operation]');
                if (opBtn) {
                    e.preventDefault();
                    refs.dropdowns.closeAll();
                    refs.opsDrawer.open(opBtn.dataset.bulkOperation);
                    return;
                }

                if (e.target.closest('[data-dropdown-menu]')) return;
                refs.dropdowns.closeAll();
                refs.status.closeAllMenus();
                if (refs.downloads.wrap && !refs.downloads.wrap.contains(e.target)) {
                    refs.downloads.closeMenu();
                }
            });

            document.addEventListener('keydown', e => {
                if (e.key !== 'Escape') return;
                refs.dropdowns.closeAll();
                refs.status.closeAllMenus();
                refs.filter.close();
                refs.makeEmployee.close();
                refs.downloads.closeMenu();
                refs.bulkStatus.close();
                refs.opsDrawer.close();
                if (typeof window.closeProfile === 'function') window.closeProfile();
            });

            document.querySelector('[data-action="refresh"]')?.addEventListener('click', () => window.location.reload());
            $(cfg.selectors.searchForm)?.addEventListener('submit', () => {
                if (typeof window.setButtonLoading === 'function') window.setButtonLoading('searchBtn', true);
            });
            $(cfg.selectors.profileModal)?.addEventListener('click', e => {
                if (e.target === $(cfg.selectors.profileModal) && typeof window.closeProfile === 'function') {
                    window.closeProfile();
                }
            });
        }
        return { init };
    }

    /* ════════════════════════════════════════════════════════════════
       16) APP — orchestrator
       ════════════════════════════════════════════════════════════════ */
    function app(options = {}) {
        const cfg = configurationOptions();

        const selection   = initSelection(cfg);
        const status      = initStatusWidgets(cfg);
        const dropdowns   = initDropdowns(cfg, { status });
        const downloads   = initDownloads(cfg);
        const filter      = initFilterPanel(cfg);
        const criteria    = initCriteriaSync(cfg);
        const profile     = initProfileData(cfg);
        const makeEmployee= initMakeEmployee(cfg, { notify });
        const del         = initDelete(cfg);
        const bulkStatus  = initBulkStatus(cfg, { selection, status });
        const bulkRole    = initBulkRole(cfg, { selection, dropdowns });
        const opsDrawer   = initOpsDrawer(cfg, { selection });

        const global = initGlobalListeners(cfg, {
            selection, status, dropdowns, downloads, filter, makeEmployee, bulkStatus, bulkRole, opsDrawer,
        });

        profile.init();
        dropdowns.init();
        status.init();
        selection.init();
        bulkStatus.init();
        opsDrawer.init();
        filter.init();
        criteria.init();
        downloads.init();
        makeEmployee.init();
        global.init();

        const api = {
            config: cfg,
            selection, status, dropdowns, downloads, filter, criteria,
            profile, makeEmployee, deleteUser: del, bulkStatus, bulkRole, opsDrawer,
            notify,
            makeEmployeeOpen: (id, username) => makeEmployee.open(id, username),
            closeMakeEmployee: () => makeEmployee.close(),
            deleteData: (id) => del.remove(id),
            openBulkDrawer: () => bulkStatus.open(),
            openOpsDrawer: (op) => opsDrawer.open(op),
            submitBulkRole: (role, btn) => bulkRole.submit(role, btn),
            openRolePickerFromHeader: () => bulkRole.openPicker(),
        };

        window.app = api;
        window.makeEmployee = api.makeEmployeeOpen;
        window.closeMakeEmployee = api.closeMakeEmployee;
        window.deleteData = api.deleteData;
        window.applyNewStatus = status.applyStatus;

        window.openJobProfileModal = function (userId, username) {
            const url = `${cfg.urls.jobProfileCreate}?user_id=${encodeURIComponent(userId)}`;
            if (typeof window.jobProfileModal?.open === 'function') {
                window.jobProfileModal.open(userId, username);
            } else {
                window.open(url, '_blank', 'noopener');
            }
        };

        return api;
    }

    /* ────────────────────────────────────────────────────────────────
       17) BOOT
       ──────────────────────────────────────────────────────────────── */
    document.addEventListener('DOMContentLoaded', () => {
        try {
            app();
        } catch (err) {
            console.error('[APP BOOT ERROR]', err);
        }
    });
})();
</script>