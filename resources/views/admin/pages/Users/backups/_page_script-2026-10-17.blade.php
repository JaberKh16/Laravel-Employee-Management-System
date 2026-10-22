
<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    /* ════════════════════════════════════════════════════════════
       SHARED CONFIG
       ════════════════════════════════════════════════════════════ */
    const csrfToken =
        document.querySelector('meta[name="csrf-token"]')?.content
        ?? @json(csrf_token());

    const BULK_STATUS_URL = @json(\Illuminate\Support\Facades\Route::has('users.bulk-status')
        ? route('users.bulk-status') : null);

    const BULK_ROLE_URL = @json(\Illuminate\Support\Facades\Route::has('users.bulk-role')
        ? route('users.bulk-role') : null);

    const BULK_OPERATION_URL = @json(\Illuminate\Support\Facades\Route::has('users.bulk-operation')
        ? route('users.bulk-operation') : null);

    /* ════════════════════════════════════════════════════════════
       DOM REFS
       ════════════════════════════════════════════════════════════ */
    const selectAllBox = document.getElementById('bulkSelectAll');
    const bulkBar      = document.getElementById('bulkBar');
    const bulkCount    = document.getElementById('bulkCount');

    const drawerEl         = document.getElementById('bulkDrawer');
    const drawerBackdrop   = document.getElementById('bulkDrawerBackdrop');
    const drawerPanel      = document.getElementById('bulkDrawerPanel');
    const drawerClose      = document.getElementById('bulkDrawerClose');
    const drawerCancel     = document.getElementById('bulkDrawerCancel');
    const drawerApply      = document.getElementById('bulkDrawerApply');
    const drawerCount      = document.getElementById('bulkDrawerCount');
    const drawerApplyCount = document.getElementById('bulkDrawerApplyCount');
    const drawerUsers      = document.getElementById('bulkDrawerUsers');
    const drawerWarning    = document.getElementById('bulkDrawerWarning');
    const drawerError      = document.getElementById('bulkDrawerError');

    const opsDrawerEl         = document.getElementById('bulkOpsDrawer');
    const opsDrawerBackdrop   = document.getElementById('bulkOpsBackdrop');
    const opsDrawerPanel      = document.getElementById('bulkOpsPanel');
    const opsDrawerClose      = document.getElementById('bulkOpsClose');
    const opsDrawerCancel     = document.getElementById('bulkOpsCancel');
    const opsDrawerApply      = document.getElementById('bulkOpsApply');
    const opsDrawerCount      = document.getElementById('bulkOpsCount');
    const opsDrawerApplyCount = document.getElementById('bulkOpsApplyCount');
    const opsDrawerUsers      = document.getElementById('bulkOpsUsers');
    const opsDrawerWarning    = document.getElementById('bulkOpsWarning');
    const opsDrawerWarningTxt = document.getElementById('bulkOpsWarningText');
    const opsDrawerError      = document.getElementById('bulkOpsError');

    const usersDataElement  = document.getElementById('usersData');
    const profileModal      = document.getElementById('profileModal');
    const makeEmployeeModal = document.getElementById('makeEmployeeModal');
    const makeEmployeeForm  = document.getElementById('makeEmployeeForm');
    const makeEmployeeName  = document.getElementById('makeEmployeeName');

    const filterPanel   = document.getElementById('filterPanel');
    const toggleBtn     = document.getElementById('toggleFilterBtn');
    const closeBtn      = document.getElementById('closeFilterBtn');
    const filterChevron = document.getElementById('filterChevron');

    const downloadToggle = document.getElementById('downloadToggle');
    const downloadMenu   = document.getElementById('downloadMenu');
    const downloadWrap   = document.getElementById('downloadWrap');

    const searchForm = document.getElementById('mainSearchForm');

    /* ════════════════════════════════════════════════════════════
       NOTIFY
       ════════════════════════════════════════════════════════════ */
    function notify(type, message, options) {
        options = options || {};
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

    /* ════════════════════════════════════════════════════════════
       SELECTION GUARD
       ════════════════════════════════════════════════════════════ */
    function ensureSelectionOrWarn() {
        const ids = selectedIds();
        if (ids.length > 0) return ids;

        notify('warning', 'Select at least one user from the table first.');

        const table = document.getElementById('userTable');
        table?.scrollIntoView({ behavior: 'smooth', block: 'center' });

        const firstBox = document.querySelector('[data-user-checkbox]');
        if (firstBox) {
            firstBox.classList.add('ring-2', 'ring-amber-400', 'ring-offset-2');
            setTimeout(() => firstBox.classList.remove('ring-2', 'ring-amber-400', 'ring-offset-2'), 1400);
            firstBox.focus({ preventScroll: true });
        }
        return null;
    }

    /* ════════════════════════════════════════════════════════════
       DROPDOWNS  ⬅ FIX: menu no longer stops propagation
       ════════════════════════════════════════════════════════════ */
    function closeAllDropdowns(except = null) {
        document.querySelectorAll('[data-dropdown]').forEach(dropdown => {
            if (dropdown === except) return;
            dropdown.querySelector('[data-dropdown-menu]')?.classList.add('hidden');
            dropdown.querySelector('[data-dropdown-trigger]')?.setAttribute('aria-expanded', 'false');
            dropdown.querySelector('[data-dropdown-chevron]')?.classList.remove('rotate-180');
        });
    }

    function bindDropdowns() {
        document.querySelectorAll('[data-dropdown]').forEach(dropdown => {
            if (dropdown.dataset.dropdownBound === '1') return;
            dropdown.dataset.dropdownBound = '1';

            const trigger = dropdown.querySelector('[data-dropdown-trigger]');
            const menu    = dropdown.querySelector('[data-dropdown-menu]');
            const chevron = dropdown.querySelector('[data-dropdown-chevron]');

            if (!trigger || !menu) return;

            trigger.addEventListener('click', event => {
                event.stopPropagation();
                const opening = menu.classList.contains('hidden');
                closeAllDropdowns(dropdown);
                closeAllStatusMenus();
                menu.classList.toggle('hidden', !opening);
                trigger.setAttribute('aria-expanded', String(opening));
                chevron?.classList.toggle('rotate-180', opening);
            });

            // ⬅ FIX: Removed `menu.addEventListener('click', e => e.stopPropagation())`
            //         so clicks inside the menu bubble to the document-level
            //         delegated handler that routes bulk actions.
        });
    }

    /* ════════════════════════════════════════════════════════════
       STATUS WIDGET
       ════════════════════════════════════════════════════════════ */
    function getStatusMetaFromDOM(widget, statusName) {
        const option = widget.querySelector(`[data-status-option="${statusName}"]`);
        if (!option) return null;
        return {
            name:    option.dataset.statusOption,
            value:   option.dataset.statusValue,
            label:   option.dataset.statusLabel,
            classes: (option.dataset.statusClasses || '').split(/\s+/).filter(Boolean),
            dot:     option.dataset.statusDot,
        };
    }

    function closeAllStatusMenus(except = null) {
        document.querySelectorAll('[data-status-widget]').forEach(widget => {
            if (widget === except) return;
            widget.querySelector('[data-status-menu]')?.classList.add('hidden');
            widget.querySelector('[data-status-trigger]')?.setAttribute('aria-expanded', 'false');
            widget.querySelector('[data-status-chevron]')?.classList.remove('rotate-180');
        });
    }

    function applyNewStatus(widget, statusName) {
        const meta = getStatusMetaFromDOM(widget, statusName);
        if (!meta) return;

        const trigger = widget.querySelector('[data-status-trigger]');
        const label   = widget.querySelector('[data-status-label]');
        const dot     = widget.querySelector('[data-status-dot]');

        if (label) label.textContent = meta.label;

        if (trigger) {
            widget.querySelectorAll('[data-status-option]').forEach(opt => {
                (opt.dataset.statusClasses || '').split(/\s+/).filter(Boolean)
                    .forEach(cls => trigger.classList.remove(cls));
            });
            meta.classes.forEach(cls => trigger.classList.add(cls));
        }

        if (dot) {
            widget.querySelectorAll('[data-status-option]').forEach(opt => {
                if (opt.dataset.statusDot) dot.classList.remove(opt.dataset.statusDot);
            });
            if (meta.dot) dot.classList.add(meta.dot);
        }

        widget.querySelectorAll('[data-status-option]').forEach(option => {
            const selected = option.dataset.statusOption === String(statusName);
            option.setAttribute('aria-selected', String(selected));
            option.classList.toggle('bg-indigo-50/70', selected);
            option.classList.toggle('dark:bg-indigo-950/40', selected);
            option.querySelector('.check-icon')?.remove();

            if (selected) {
                const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                svg.setAttribute('class', 'check-icon h-3.5 w-3.5 text-indigo-600 dark:text-indigo-400');
                svg.setAttribute('fill', 'none');
                svg.setAttribute('stroke', 'currentColor');
                svg.setAttribute('stroke-width', '2.5');
                svg.setAttribute('viewBox', '0 0 24 24');
                const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                path.setAttribute('stroke-linecap', 'round');
                path.setAttribute('stroke-linejoin', 'round');
                path.setAttribute('d', 'm4.5 12.75 6 6 9-13.5');
                svg.appendChild(path);
                option.appendChild(svg);
            }
        });
    }

    window.applyNewStatus = applyNewStatus;

    function bindStatusWidgets() {
        document.querySelectorAll('[data-status-widget]').forEach(widget => {
            if (widget.dataset.statusBound === '1') return;
            widget.dataset.statusBound = '1';

            const trigger      = widget.querySelector('[data-status-trigger]');
            const menu         = widget.querySelector('[data-status-menu]');
            const loading      = widget.querySelector('[data-status-loading]');
            const errorElement = widget.querySelector('[data-status-error]');
            const chevron      = widget.querySelector('[data-status-chevron]');
            const url          = widget.dataset.updateUrl;

            if (!trigger || !menu || !url) return;

            trigger.addEventListener('click', event => {
                event.stopPropagation();
                if (trigger.disabled) return;
                const opening = menu.classList.contains('hidden');
                closeAllStatusMenus(widget);
                closeAllDropdowns();
                menu.classList.toggle('hidden', !opening);
                trigger.setAttribute('aria-expanded', String(opening));
                chevron?.classList.toggle('rotate-180', opening);
                if (opening) errorElement?.classList.add('hidden');
            });

            // Stop clicks inside status menu from bubbling to document
            // (so delegated bulk handlers don't fire on status option clicks)
            menu.addEventListener('click', e => e.stopPropagation());

            menu.querySelectorAll('[data-status-option]').forEach(option => {
                option.addEventListener('click', async event => {
                    event.stopPropagation();
                    if (trigger.disabled) return;

                    const newStatusName = option.dataset.statusOption;
                    const currentStatusName = menu.querySelector(
                        '[data-status-option][aria-selected="true"]'
                    )?.dataset.statusOption;

                    if (newStatusName === currentStatusName) {
                        closeAllStatusMenus();
                        return;
                    }

                    errorElement?.classList.add('hidden');
                    trigger.disabled = true;
                    trigger.classList.add('opacity-70', 'cursor-wait');
                    menu.querySelectorAll('[data-status-option]').forEach(i => i.disabled = true);
                    loading?.classList.remove('hidden');
                    loading?.classList.add('flex');

                    try {
                        const response = await fetch(url, {
                            method: 'PATCH',
                            credentials: 'same-origin',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: JSON.stringify({ status: newStatusName }),
                        });
                        const data = await response.json().catch(() => ({}));
                        if (!response.ok) throw new Error(
                            data?.errors?.status?.[0] ?? data?.message ?? `Request failed (${response.status})`
                        );
                        applyNewStatus(widget, data?.status_name ?? newStatusName);
                        closeAllStatusMenus();
                        notify('success', data?.message || 'User status updated successfully.');
                    } catch (error) {
                        console.error('[status-update]', error);
                        const message = error.message || 'Could not update user status.';
                        if (errorElement) {
                            errorElement.textContent = message;
                            errorElement.classList.remove('hidden');
                        }
                        notify('error', message);
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

    /* ════════════════════════════════════════════════════════════
       BULK SELECTION
       ════════════════════════════════════════════════════════════ */
    const rowBoxes = () => document.querySelectorAll('[data-user-checkbox]');

    function selectedIds() {
        return Array.from(rowBoxes()).filter(box => box.checked).map(box => box.value);
    }

    function renderDrawerSelection() {
        const ids = selectedIds();
        if (drawerCount)      drawerCount.textContent      = ids.length;
        if (drawerApplyCount) drawerApplyCount.textContent = ids.length;
        if (!drawerUsers) return;

        drawerUsers.innerHTML = '';
        ids.slice(0, 30).forEach(id => {
            const row  = document.querySelector(`[data-user-row][data-user-id="${id}"]`);
            const name = row?.querySelector('.text-sm.font-semibold')?.textContent?.trim() || `User #${id}`;
            const li = document.createElement('li');
            li.className = 'flex items-center gap-2';
            li.innerHTML = `
                <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-indigo-100 text-[9px] font-bold uppercase text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300">
                    ${name.substring(0, 2).toUpperCase()}
                </span>
                <span class="truncate">${name}</span>
            `;
            drawerUsers.appendChild(li);
        });

        if (ids.length > 30) {
            const li = document.createElement('li');
            li.className = 'pt-1 text-[11px] font-semibold text-indigo-600 dark:text-indigo-400';
            li.textContent = `+ ${ids.length - 30} more`;
            drawerUsers.appendChild(li);
        }
    }

    function refreshSelectionUI() {
        const ids   = selectedIds();
        const total = rowBoxes().length;

        if (bulkBar) bulkBar.classList.toggle('hidden', ids.length === 0);
        if (bulkCount) bulkCount.textContent = ids.length;

        if (selectAllBox) {
            selectAllBox.checked       = total > 0 && ids.length === total;
            selectAllBox.indeterminate = ids.length > 0 && ids.length < total;
        }

        if (drawerEl && !drawerEl.classList.contains('hidden')) renderDrawerSelection();
    }

    /* ════════════════════════════════════════════════════════════
       DRAWERS — bulk status
       ════════════════════════════════════════════════════════════ */
    function openBulkDrawer() {
        if (!ensureSelectionOrWarn()) return;
        if (!drawerEl) { notify('error', 'Status drawer not found.'); return; }

        document.querySelectorAll('input[name="bulk_status"]').forEach(r => r.checked = false);
        drawerApply?.setAttribute('disabled', 'disabled');
        drawerError?.classList.add('hidden');
        drawerWarning?.classList.add('hidden');
        renderDrawerSelection();

        drawerEl.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        requestAnimationFrame(() => {
            drawerBackdrop?.classList.remove('opacity-0');
            drawerPanel?.classList.remove('translate-x-full');
        });
        setTimeout(() => document.querySelector('input[name="bulk_status"]')?.focus(), 250);
    }

    function closeBulkDrawer() {
        if (!drawerEl) return;
        drawerBackdrop?.classList.add('opacity-0');
        drawerPanel?.classList.add('translate-x-full');
        setTimeout(() => {
            drawerEl.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
    }

    async function submitBulkStatus() {
        if (!BULK_STATUS_URL) { notify('error', 'Bulk status endpoint not available.'); return; }
        const ids = ensureSelectionOrWarn(); if (!ids) return;
        const chosen = document.querySelector('input[name="bulk_status"]:checked')?.value;
        if (!chosen) { notify('warning', 'Choose a status first.'); return; }

        const originalHtml = drawerApply?.innerHTML;
        if (drawerApply) {
            drawerApply.disabled = true;
            drawerApply.innerHTML = `<svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg><span>Applying…</span>`;
        }
        drawerError?.classList.add('hidden');

        try {
            const response = await fetch(BULK_STATUS_URL, {
                method: 'PATCH', credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json', 'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ ids, status: chosen }),
            });
            const data = await response.json().catch(() => ({}));
            if (!response.ok) throw new Error(
                data?.errors?.status?.[0] ?? data?.errors?.ids?.[0] ?? data?.message ?? `Request failed (${response.status})`
            );

            ids.forEach(id => {
                const widget = document.querySelector(`[data-user-row][data-user-id="${id}"] [data-status-widget]`);
                if (widget) applyNewStatus(widget, data?.status_name ?? chosen);
            });

            rowBoxes().forEach(box => { box.checked = false; });
            if (selectAllBox) { selectAllBox.checked = false; selectAllBox.indeterminate = false; }
            refreshSelectionUI();
            closeBulkDrawer();
            notify('success', data?.message || 'Bulk status update complete.');
        } catch (error) {
            console.error('[bulk-status]', error);
            const message = error.message || 'Bulk status update failed.';
            if (drawerError) { drawerError.textContent = message; drawerError.classList.remove('hidden'); }
            notify('error', message);
        } finally {
            if (drawerApply) { drawerApply.disabled = false; drawerApply.innerHTML = originalHtml; }
        }
    }

    /* ════════════════════════════════════════════════════════════
       DRAWERS — bulk role
       ════════════════════════════════════════════════════════════ */
    async function submitBulkRole(roleName, button) {
        if (!BULK_ROLE_URL) { notify('error', 'Bulk role endpoint not available.'); return; }
        const ids = ensureSelectionOrWarn(); if (!ids) return;
        closeAllDropdowns();

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
            const response = await fetch(BULK_ROLE_URL, {
                method: 'PATCH', credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json', 'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ ids, role: roleName }),
            });
            const data = await response.json().catch(() => ({}));
            if (!response.ok) throw new Error(
                data?.errors?.role?.[0] ?? data?.errors?.ids?.[0] ?? data?.message ?? `Request failed (${response.status})`
            );

            notify('success', data?.message || 'Role assigned successfully.');
            setTimeout(() => window.location.reload(), 700);
        } catch (error) {
            console.error('[bulk-role]', error);
            notify('error', error.message || 'Bulk role update failed.');
        } finally {
            if (button) { button.disabled = false; button.innerHTML = originalHtml; }
        }
    }

    /* ════════════════════════════════════════════════════════════
       DRAWERS — operations
       ════════════════════════════════════════════════════════════ */
    const OPS_WARNINGS = {
        'delete':         'Deleted users are soft-deleted. You can restore them from the trash if needed.',
        'force-delete':   'Force delete permanently removes users and their data. This CANNOT be undone.',
        'deactivate':     'Deactivated users cannot log in until reactivated.',
        'make-employee':  'Users who are already employees will be skipped.',
        'activate':       '',
        'export':         'This will download a CSV with the selected users.',
        'view-employees': 'You will be redirected to a filtered employee view.',
    };

    function openOpsDrawer(operation) {
        if (!ensureSelectionOrWarn()) return;
        if (!opsDrawerEl) { notify('error', 'Operations drawer not found.'); return; }

        document.querySelectorAll('input[name="bulk_operation"]').forEach(r => r.checked = false);
        opsDrawerApply?.setAttribute('disabled', 'disabled');
        opsDrawerError?.classList.add('hidden');
        opsDrawerWarning?.classList.add('hidden');

        if (operation) {
            const radio = document.querySelector(`input[name="bulk_operation"][value="${operation}"]`);
            if (radio) {
                radio.checked = true;
                radio.dispatchEvent(new Event('change', { bubbles: true }));
            } else {
                console.warn('[ops] Unknown operation:', operation);
            }
        }

        renderOpsDrawerSelection();
        opsDrawerEl.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        requestAnimationFrame(() => {
            opsDrawerBackdrop?.classList.remove('opacity-0');
            opsDrawerPanel?.classList.remove('translate-x-full');
        });
    }

    function closeOpsDrawer() {
        if (!opsDrawerEl) return;
        opsDrawerBackdrop?.classList.add('opacity-0');
        opsDrawerPanel?.classList.add('translate-x-full');
        setTimeout(() => {
            opsDrawerEl.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
    }

    function renderOpsDrawerSelection() {
        const ids = selectedIds();
        if (opsDrawerCount)      opsDrawerCount.textContent      = ids.length;
        if (opsDrawerApplyCount) opsDrawerApplyCount.textContent = ids.length;
        if (!opsDrawerUsers) return;

        opsDrawerUsers.innerHTML = '';
        ids.slice(0, 30).forEach(id => {
            const row  = document.querySelector(`[data-user-row][data-user-id="${id}"]`);
            const name = row?.querySelector('.text-sm.font-semibold')?.textContent?.trim() || `User #${id}`;
            const li = document.createElement('li');
            li.className = 'flex items-center gap-2';
            li.innerHTML = `
                <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-amber-100 text-[9px] font-bold uppercase text-amber-700 dark:bg-amber-950/60 dark:text-amber-300">
                    ${name.substring(0, 2).toUpperCase()}
                </span>
                <span class="truncate">${name}</span>
            `;
            opsDrawerUsers.appendChild(li);
        });

        if (ids.length > 30) {
            const li = document.createElement('li');
            li.className = 'pt-1 text-[11px] font-semibold text-amber-600 dark:text-amber-400';
            li.textContent = `+ ${ids.length - 30} more`;
            opsDrawerUsers.appendChild(li);
        }
    }

    document.querySelectorAll('input[name="bulk_operation"]').forEach(radio => {
        radio.addEventListener('change', () => {
            if (opsDrawerApply) opsDrawerApply.removeAttribute('disabled');
            const warning = OPS_WARNINGS[radio.value];
            if (warning) {
                if (opsDrawerWarningTxt) opsDrawerWarningTxt.textContent = warning;
                opsDrawerWarning?.classList.remove('hidden');
            } else {
                opsDrawerWarning?.classList.add('hidden');
            }
        });
    });

    async function submitBulkOperation() {
        if (!BULK_OPERATION_URL) { notify('error', 'Bulk operation endpoint not available.'); return; }
        const ids = ensureSelectionOrWarn(); if (!ids) return;

        const chosen = document.querySelector('input[name="bulk_operation"]:checked')?.value;
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

        const originalHtml = opsDrawerApply?.innerHTML;
        if (opsDrawerApply) {
            opsDrawerApply.disabled = true;
            opsDrawerApply.innerHTML = `<svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg><span>Running…</span>`;
        }
        opsDrawerError?.classList.add('hidden');

        try {
            const response = await fetch(BULK_OPERATION_URL, {
                method: 'PATCH', credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json', 'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ ids, operation: chosen }),
            });
            const data = await response.json().catch(() => ({}));
            if (!response.ok) throw new Error(
                data?.errors?.operation?.[0] ?? data?.errors?.ids?.[0] ?? data?.message ?? `Request failed (${response.status})`
            );

            notify('success', data?.message || 'Operation complete.');
            closeOpsDrawer();

            if (data?.operation === 'export' && data?.redirect) {
                window.open(data.redirect, '_blank');
                return;
            }
            if (data?.operation === 'view-employees' && data?.redirect) {
                window.location.href = data.redirect;
                return;
            }
            setTimeout(() => window.location.reload(), 700);
        } catch (error) {
            console.error('[bulk-operation]', error);
            const message = error.message || 'Operation failed.';
            if (opsDrawerError) { opsDrawerError.textContent = message; opsDrawerError.classList.remove('hidden'); }
            notify('error', message);
        } finally {
            if (opsDrawerApply) { opsDrawerApply.disabled = false; opsDrawerApply.innerHTML = originalHtml; }
        }
    }

    /* ════════════════════════════════════════════════════════════
       PROFILE MODAL
       ════════════════════════════════════════════════════════════ */
    let usersData = {};
    if (usersDataElement) {
        try { usersData = JSON.parse(usersDataElement.textContent || '{}'); }
        catch (e) { console.error('Could not parse usersData JSON:', e); }
    }

    
    /* ════════════════════════════════════════════════════════════
       MAKE EMPLOYEE MODAL
       ════════════════════════════════════════════════════════════ */
    window.makeEmployee = function (id, username) {
        const user = usersData[id];
        if (!user || !makeEmployeeModal || !makeEmployeeForm) return;
        if (makeEmployeeName) makeEmployeeName.textContent = username || user.username || 'User';
        if (user.make_emp_url) makeEmployeeForm.action = user.make_emp_url;
        makeEmployeeModal.classList.remove('hidden');
        makeEmployeeModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    };

    window.closeMakeEmployee = function () {
        if (!makeEmployeeModal) return;
        makeEmployeeModal.classList.add('hidden');
        makeEmployeeModal.classList.remove('flex');
        document.body.style.overflow = '';
    };

    window.makeEmployee = function (userId, userName) {
        var modal = $('makeEmployeeModal');
        if (!modal) return;

        _currentUserId = userId;
        $('makeEmployeeName').textContent = userName || 'this user';

        // Build form action: /admin/users/{id}/make-employee
        var form = $('makeEmployeeForm');
        form.action = '/admin/users/' + encodeURIComponent(userId) + '/make-employee';

        // Reset fields
        form.reset();
        $('me_name').value = userName || '';
        $('me_age').value = '';
        var err = $('meError');
        if (err) { err.classList.add('hidden'); err.textContent = ''; }

        // Reset dependent dropdowns
        $('me_state').innerHTML = '<option value="">Select state…</option>';
        $('me_city').innerHTML  = '<option value="">Select city…</option>';

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };

    // ═══════════════════════════════════════════════════════
    // CLOSE
    // ═══════════════════════════════════════════════════════
    window.closeMakeEmployee = function () {
        var modal = $('makeEmployeeModal');
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        _currentUserId = null;
    };

    // ═══════════════════════════════════════════════════════
    // AUTO-COMPUTE AGE FROM DOB
    // ═══════════════════════════════════════════════════════
    function computeAge() {
        var dob = $('me_dob').value;
        if (!dob) { $('me_age').value = ''; return; }

        var birth = new Date(dob);
        var today = new Date();
        var age = today.getFullYear() - birth.getFullYear();
        var m = today.getMonth() - birth.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--;
        $('me_age').value = age >= 0 ? age : '';
    }

    // ═══════════════════════════════════════════════════════
    // CASCADING LOCATION
    // ═══════════════════════════════════════════════════════
    function loadStates(countryId) {
        var sel = $('me_state');
        sel.innerHTML = '<option value="">Select state…</option>';
        if (!countryId) return;

        fetch('/api/states?country_id=' + encodeURIComponent(countryId), {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.ok ? r.json() : []; })
        .then(function (data) {
            (data || []).forEach(function (s) {
                sel.add(new Option(s.name, s.id));
            });
        })
        .catch(function (e) { console.warn('[makeEmployee] loadStates:', e); });
    }

    function loadCities(stateId) {
        var sel = $('me_city');
        sel.innerHTML = '<option value="">Select city…</option>';
        if (!stateId) return;

        fetch('/api/cities?state_id=' + encodeURIComponent(stateId), {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.ok ? r.json() : []; })
        .then(function (data) {
            (data || []).forEach(function (c) {
                sel.add(new Option(c.name, c.id));
            });
        })
        .catch(function (e) { console.warn('[makeEmployee] loadCities:', e); });
    }

    // ═══════════════════════════════════════════════════════
    // BIND EVENTS
    // ═══════════════════════════════════════════════════════
    function bindEvents() {
        var dob = $('me_dob');
        if (dob) dob.addEventListener('change', computeAge);

        var country = $('me_country');
        if (country) {
            country.addEventListener('change', function (e) {
                loadStates(e.target.value);
                $('me_city').innerHTML = '<option value="">Select city…</option>';
            });
        }

        var state = $('me_state');
        if (state) {
            state.addEventListener('change', function (e) {
                loadCities(e.target.value);
            });
        }

        // Close on backdrop click
        var modal = $('makeEmployeeModal');
        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === modal) window.closeMakeEmployee();
            });
        }

        // ESC closes
        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') return;
            var m = $('makeEmployeeModal');
            if (m && !m.classList.contains('hidden')) window.closeMakeEmployee();
        });
    }

    /* ════════════════════════════════════════════════════════════
       DELETE CONFIRMATION
       ════════════════════════════════════════════════════════════ */
    window.deleteData = function (id) {
        const form = document.getElementById('delete-form-' + id);
        if (!form) { console.error('Delete form not found for ID:', id); return; }

        if (window.Swal && typeof window.Swal.fire === 'function') {
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
            }).then(result => { if (result.isConfirmed) form.submit(); });
        } else if (confirm('Delete this user? This action cannot be undone.')) {
            form.submit();
        }
    };

    /* ════════════════════════════════════════════════════════════
       FILTER PANEL
       ════════════════════════════════════════════════════════════ */
    function openFilter() {
        if (!filterPanel) return;
        filterPanel.classList.add('is-open');
        toggleBtn?.setAttribute('aria-expanded', 'true');
        if (filterChevron) filterChevron.style.transform = 'rotate(180deg)';
    }

    function closeFilter() {
        if (!filterPanel) return;
        filterPanel.classList.remove('is-open');
        toggleBtn?.setAttribute('aria-expanded', 'false');
        if (filterChevron) filterChevron.style.transform = 'rotate(0deg)';
    }

    /* ════════════════════════════════════════════════════════════
       ROLE PICKER
       ════════════════════════════════════════════════════════════ */
    async function openRolePickerFromHeader() {
        if (!ensureSelectionOrWarn()) return;

        const roleButtons = document.querySelectorAll('[data-bulk-role]');
        if (roleButtons.length === 0) { notify('error', 'No roles available.'); return; }

        const options = {};
        roleButtons.forEach(btn => { options[btn.dataset.bulkRole] = btn.dataset.bulkRole; });

        const result = window.Swal
            ? await window.Swal.fire({
                title: `Change role for ${selectedIds().length} user(s)`,
                input: 'select',
                inputOptions: options,
                inputPlaceholder: 'Select a role…',
                showCancelButton: true,
                confirmButtonText: 'Assign', cancelButtonText: 'Cancel',
                reverseButtons: true,
                inputValidator: (value) => !value && 'Pick a role',
            })
            : { isConfirmed: false };

        if (result.isConfirmed && result.value) {
            submitBulkRole(result.value, null);
        }
    }

    /* ════════════════════════════════════════════════════════════
       EVENT BINDING
       ════════════════════════════════════════════════════════════ */
    bindDropdowns();
    bindStatusWidgets();

    document.querySelectorAll('input[name="bulk_status"]').forEach(radio => {
        radio.addEventListener('change', () => {
            if (drawerApply) drawerApply.removeAttribute('disabled');
            if (radio.value === 'Banned') drawerWarning?.classList.remove('hidden');
            else drawerWarning?.classList.add('hidden');
        });
    });

    document.getElementById('openBulkDrawer')?.addEventListener('click', openBulkDrawer);

    drawerClose?.addEventListener('click', closeBulkDrawer);
    drawerCancel?.addEventListener('click', closeBulkDrawer);
    drawerBackdrop?.addEventListener('click', closeBulkDrawer);
    drawerApply?.addEventListener('click', submitBulkStatus);

    opsDrawerClose?.addEventListener('click', closeOpsDrawer);
    opsDrawerCancel?.addEventListener('click', closeOpsDrawer);
    opsDrawerBackdrop?.addEventListener('click', closeOpsDrawer);
    opsDrawerApply?.addEventListener('click', submitBulkOperation);

    selectAllBox?.addEventListener('change', () => {
        rowBoxes().forEach(box => { box.checked = selectAllBox.checked; });
        refreshSelectionUI();
    });

    document.addEventListener('change', event => {
        if (event.target.matches('[data-user-checkbox]')) refreshSelectionUI();
    });

    /* ════════════════════════════════════════════════════════════
       DELEGATED CLICK HANDLERS — bulk operations from header & bulk bar
       ⬅ FIX: This is the handler that now reliably fires because
              we removed the menu's stopPropagation above.
       ════════════════════════════════════════════════════════════ */
    document.addEventListener('click', event => {

        // Clear selection (bulk bar)
        if (event.target.closest('[data-bulk-clear]')) {
            event.preventDefault();
            rowBoxes().forEach(box => { box.checked = false; });
            if (selectAllBox) { selectAllBox.checked = false; selectAllBox.indeterminate = false; }
            refreshSelectionUI();
            return;
        }

        // Header: "Change status…"
        if (event.target.closest('[data-open-bulk-status]')) {
            event.preventDefault();
            closeAllDropdowns();
            openBulkDrawer();
            return;
        }

        // Header: "Change role"
        if (event.target.closest('[data-open-bulk-role]')) {
            event.preventDefault();
            closeAllDropdowns();
            openRolePickerFromHeader();
            return;
        }

        // Bulk bar: role picker option
        const roleBtn = event.target.closest('[data-bulk-role]');
        if (roleBtn) {
            event.preventDefault();
            submitBulkRole(roleBtn.dataset.bulkRole, roleBtn);
            return;
        }

        // Header: any bulk operation (Set Active, Make employees, Export, Delete…)
        const opBtn = event.target.closest('[data-bulk-operation]');
        if (opBtn) {
            event.preventDefault();
            closeAllDropdowns();
            openOpsDrawer(opBtn.dataset.bulkOperation);
            return;
        }
    });

    /* ════════════════════════════════════════════════════════════
       INITIAL
       ════════════════════════════════════════════════════════════ */
    refreshSelectionUI();

    document.querySelector('[data-action="refresh"]')?.addEventListener('click', () => {
        window.location.reload();
    });

    if (searchForm) {
        searchForm.addEventListener('submit', function () {
            if (typeof window.setButtonLoading === 'function') {
                window.setButtonLoading('searchBtn', true);
            }
        });
    }

    document.querySelectorAll('[data-download]').forEach(item => {
        item.addEventListener('click', function () {
            const spinner  = this.querySelector('.spinner-btn');
            const label    = this.querySelector('.download-label');
            const original = label?.textContent ?? '';
            spinner?.classList.remove('hidden');
            if (label) label.textContent = 'Downloading…';
            if (typeof window.showLoader === 'function') {
                window.showLoader(this.dataset.loadingText || 'Preparing download…');
            }
            window.setTimeout(() => {
                window.hideLoader?.();
                spinner?.classList.add('hidden');
                if (label) label.textContent = original;
            }, 2500);
        });
    });

    toggleBtn?.addEventListener('click', () => {
        if (!filterPanel) return;
        filterPanel.classList.contains('is-open') ? closeFilter() : openFilter();
    });
    closeBtn?.addEventListener('click', closeFilter);

    @if (request()->hasAny(['name', 'email', 'date', 'dob', 'status', 'updated_from', 'updated_to']))
        openFilter();
    @endif

    const radios = document.querySelectorAll('.criteria-radio');
    const fields = document.querySelectorAll('.criteria-field');

    function syncCriteriaFields() {
        const selected = document.querySelector('.criteria-radio:checked')?.value || 'name';
        fields.forEach(field => {
            field.style.display = field.dataset.criteria === selected ? '' : 'none';
        });
    }
    radios.forEach(r => r.addEventListener('change', syncCriteriaFields));
    syncCriteriaFields();

    downloadToggle?.addEventListener('click', event => {
        event.stopPropagation();
        if (!downloadMenu) return;
        const isHidden = downloadMenu.classList.contains('hidden');
        downloadMenu.classList.toggle('hidden', !isHidden);
        downloadToggle.setAttribute('aria-expanded', String(isHidden));
    });

    profileModal?.addEventListener('click', e => {
        if (e.target === profileModal) window.closeProfile();
    });
    makeEmployeeModal?.addEventListener('click', e => {
        if (e.target === makeEmployeeModal) window.closeMakeEmployee();
    });
    makeEmployeeForm?.addEventListener('submit', function () {
        if (typeof window.setButtonLoading === 'function') window.setButtonLoading('makeEmployeeSubmit', true);
        if (typeof window.showLoader === 'function') window.showLoader('Creating employee record…');
    });

    /* ════════════════════════════════════════════════════════════
       GLOBAL: click-outside closes dropdowns  ⬅ FIX: skip clicks inside menus
       ════════════════════════════════════════════════════════════ */
    document.addEventListener('click', event => {
        // Don't close if the click originated inside a dropdown menu
        if (event.target.closest('[data-dropdown-menu]')) return;
        closeAllDropdowns();
        closeAllStatusMenus();

        if (downloadWrap && !downloadWrap.contains(event.target)) {
            downloadMenu?.classList.add('hidden');
            downloadToggle?.setAttribute('aria-expanded', 'false');
        }
    });

    document.addEventListener('keydown', event => {
        if (event.key !== 'Escape') return;
        closeAllDropdowns();
        closeAllStatusMenus();
        closeFilter();
        window.closeProfile();
        window.closeMakeEmployee();
        downloadMenu?.classList.add('hidden');
        downloadToggle?.setAttribute('aria-expanded', 'false');
        if (drawerEl && !drawerEl.classList.contains('hidden')) closeBulkDrawer();
        if (opsDrawerEl && !opsDrawerEl.classList.contains('hidden')) closeOpsDrawer();
    });
});
</script>
