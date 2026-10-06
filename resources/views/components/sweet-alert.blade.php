{{-- resources/views/admin/layout/main.blade.php --}}
<style>
    /* ══════════════════════════════════════════════════════════════
       NOTIFICATION TOASTS — Modern slide-in from top-right
       ══════════════════════════════════════════════════════════════ */

    /* Container stacking */
    .swal2-container.swal2-top-end {
        padding: 1rem !important;
        z-index: 9999 !important;
    }

    /* Toast base — modern card */
    .swal2-popup.swal2-toast {
        padding: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
        overflow: visible !important;
        width: auto !important;
        max-width: 26rem !important;
        min-width: 20rem !important;
    }

    /* Inner wrapper — the actual visible card */
    .swal2-popup.swal2-toast .swal2-toast-card,
    .swal2-popup.swal2-toast {
        border-radius: 1rem !important;
    }

    .swal2-toast-wrapper {
        display: flex;
        align-items: flex-start;
        gap: 0.875rem;
        padding: 0.95rem 1rem;
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 1rem;
        box-shadow:
            0 10px 15px -3px rgba(0, 0, 0, 0.08),
            0 4px 6px -4px rgba(0, 0, 0, 0.05),
            0 0 0 1px rgba(0, 0, 0, 0.02);
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
        animation: toast-in 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .dark .swal2-toast-wrapper {
        background: #1f2937;
        border-color: rgba(255, 255, 255, 0.08);
        box-shadow:
            0 10px 15px -3px rgba(0, 0, 0, 0.4),
            0 4px 6px -4px rgba(0, 0, 0, 0.3),
            0 0 0 1px rgba(255, 255, 255, 0.04);
    }

    /* Colored left accent bar */
    .swal2-toast-wrapper::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
    }

    /* Icon circle */
    .swal2-toast-icon {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 9999px;
        position: relative;
    }

    .swal2-toast-icon svg {
        width: 1.25rem;
        height: 1.25rem;
    }

    /* Content area */
    .swal2-toast-content {
        flex: 1;
        min-width: 0;
        padding-top: 0.125rem;
    }

    .swal2-toast-title {
        font-size: 0.875rem;
        font-weight: 700;
        line-height: 1.25;
        color: #111827;
        margin-bottom: 0.125rem;
    }

    .dark .swal2-toast-title {
        color: #f9fafb;
    }

    .swal2-toast-message {
        font-size: 0.8125rem;
        line-height: 1.45;
        color: #6b7280;
        word-wrap: break-word;
    }

    .dark .swal2-toast-message {
        color: #d1d5db;
    }

    /* Close button */
    .swal2-toast-close {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 1.75rem;
        height: 1.75rem;
        border-radius: 0.5rem;
        color: #9ca3af;
        cursor: pointer;
        transition: all 0.15s ease;
        border: none;
        background: transparent;
        padding: 0;
    }

    .swal2-toast-close:hover {
        background: rgba(0, 0, 0, 0.05);
        color: #4b5563;
    }

    .dark .swal2-toast-close:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #e5e7eb;
    }

    .swal2-toast-close svg {
        width: 0.875rem;
        height: 0.875rem;
    }

    /* Progress bar */
    .swal2-toast-progress {
        position: absolute;
        left: 0;
        bottom: 0;
        height: 2px;
        width: 100%;
        transform-origin: left center;
        animation: toast-progress linear forwards;
    }

    /* ══════════════════════════════════════════════════════════════
       TYPE VARIANTS
       ══════════════════════════════════════════════════════════════ */

    /* SUCCESS */
    .swal2-toast-success::before { background: linear-gradient(180deg, #10b981, #059669); }
    .swal2-toast-success .swal2-toast-icon {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #047857;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.12);
    }
    .dark .swal2-toast-success .swal2-toast-icon {
        background: linear-gradient(135deg, #064e3b, #065f46);
        color: #6ee7b7;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
    }
    .swal2-toast-success .swal2-toast-progress {
        background: linear-gradient(90deg, #10b981, #34d399);
    }

    /* ERROR */
    .swal2-toast-error::before { background: linear-gradient(180deg, #ef4444, #dc2626); }
    .swal2-toast-error .swal2-toast-icon {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #b91c1c;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.12);
    }
    .dark .swal2-toast-error .swal2-toast-icon {
        background: linear-gradient(135deg, #7f1d1d, #991b1b);
        color: #fca5a5;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15);
    }
    .swal2-toast-error .swal2-toast-progress {
        background: linear-gradient(90deg, #ef4444, #f87171);
    }

    /* WARNING */
    .swal2-toast-warning::before { background: linear-gradient(180deg, #f59e0b, #d97706); }
    .swal2-toast-warning .swal2-toast-icon {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #b45309;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.12);
    }
    .dark .swal2-toast-warning .swal2-toast-icon {
        background: linear-gradient(135deg, #78350f, #92400e);
        color: #fcd34d;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.15);
    }
    .swal2-toast-warning .swal2-toast-progress {
        background: linear-gradient(90deg, #f59e0b, #fbbf24);
    }

    /* INFO */
    .swal2-toast-info::before { background: linear-gradient(180deg, #0ea5e9, #0284c7); }
    .swal2-toast-info .swal2-toast-icon {
        background: linear-gradient(135deg, #e0f2fe, #bae6fd);
        color: #0369a1;
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.12);
    }
    .dark .swal2-toast-info .swal2-toast-icon {
        background: linear-gradient(135deg, #082f49, #075985);
        color: #7dd3fc;
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15);
    }
    .swal2-toast-info .swal2-toast-progress {
        background: linear-gradient(90deg, #0ea5e9, #38bdf8);
    }

    /* ══════════════════════════════════════════════════════════════
       ANIMATIONS
       ══════════════════════════════════════════════════════════════ */
    @keyframes toast-in {
        from {
            opacity: 0;
            transform: translateX(100%) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateX(0) scale(1);
        }
    }

    @keyframes toast-out {
        from {
            opacity: 1;
            transform: translateX(0) scale(1);
        }
        to {
            opacity: 0;
            transform: translateX(100%) scale(0.95);
        }
    }

    @keyframes toast-progress {
        from { transform: scaleX(1); }
        to   { transform: scaleX(0); }
    }

    /* Exit animation applied by Swal */
    .swal2-container.swal2-top-end .swal2-popup.swal2-toast.swal2-hide,
    .swal2-container.swal2-top-end .swal2-popup.swal2-toast[data-swal2-close] {
        animation: toast-out 0.25s ease forwards !important;
    }

    /* ══════════════════════════════════════════════════════════════
       STACKING LAYOUT
       ══════════════════════════════════════════════════════════════ */
    .swal2-container.swal2-top-end {
        display: flex !important;
        flex-direction: column !important;
        align-items: flex-end !important;
        gap: 0.5rem !important;
    }

    /* ══════════════════════════════════════════════════════════════
       MODAL (non-toast) DARK MODE
       ══════════════════════════════════════════════════════════════ */
    .dark .swal2-popup:not(.swal2-toast) {
        background: #1f2937 !important;
        color: #f9fafb !important;
    }
    .dark .swal2-title:not(.swal2-toast-title) {
        color: #f9fafb !important;
    }
    .dark .swal2-html-container {
        color: #d1d5db !important;
    }
</style>

{{-- SweetAlert2 flash + confirm helpers --}}
<script>
(function () {
    'use strict';

    if (!window.Swal) {
        console.warn('[notifications] Swal not loaded — include the CDN first.');
        return;
    }

    // ═══════════════════════════════════════════════════════════
    // THEME / PRESETS
    // ═══════════════════════════════════════════════════════════
    const isDark = () => document.documentElement.classList.contains('dark');

    const baseModal = {
        confirmButtonColor: '#6366f1',
        cancelButtonColor:  '#6b7280',
        customClass: {
            popup:         'rounded-2xl shadow-2xl',
            title:         'text-lg font-bold',
            htmlContainer: 'text-sm text-gray-600 dark:text-gray-300',
            confirmButton: 'rounded-xl px-4 py-2 text-sm font-semibold',
            cancelButton:  'rounded-xl px-4 py-2 text-sm font-semibold',
        },
        buttonsStyling: true,
        reverseButtons: true,
        didOpen: (el) => {
            if (isDark()) {
                el.style.background = '#1f2937';
                el.style.color = '#f9fafb';
            }
        },
    };

    // Icon SVG paths
    const ICONS = {
        success: '<svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>',
        error:   '<svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>',
        warning: '<svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>',
        info:    '<svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>',
    };

    const CLOSE_ICON = '<svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>';

    const TITLES = {
        success: 'Success',
        error:   'Error',
        warning: 'Warning',
        info:    'Info',
    };

    // ═══════════════════════════════════════════════════════════
    // TOAST FACTORY — modern slide-in card
    // ═══════════════════════════════════════════════════════════
    function showToast(type, message, options) {
        options = options || {};

        const duration = options.timer || 4500;
        const title    = options.title || TITLES[type] || '';
        const msg      = message || '';

        // Build custom HTML for the toast
        const html = `
            <div class="swal2-toast-wrapper swal2-toast-${type}">
                <div class="swal2-toast-icon">${ICONS[type] || ICONS.info}</div>
                <div class="swal2-toast-content">
                    <div class="swal2-toast-title">${escapeHtml(title)}</div>
                    <div class="swal2-toast-message">${escapeHtml(msg)}</div>
                </div>
                <button type="button" class="swal2-toast-close" aria-label="Close">${CLOSE_ICON}</button>
                ${duration > 0 ? `<div class="swal2-toast-progress" style="animation-duration: ${duration}ms"></div>` : ''}
            </div>
        `;

        return Swal.fire({
            toast: true,
            position: 'top-end',
            html: html,
            showConfirmButton: false,
            showCloseButton: false,
            timer: duration > 0 ? duration : undefined,
            timerProgressBar: false,   // we render our own progress bar
            width: 'auto',
            padding: 0,
            background: 'transparent',
            backdrop: false,
            customClass: {
                popup: '!p-0 !bg-transparent !shadow-none',
                htmlContainer: '!m-0 !p-0',
            },
            didOpen: (popup) => {
                // Wire up the custom close button
                const btn = popup.querySelector('.swal2-toast-close');
                if (btn) {
                    btn.addEventListener('click', () => Swal.close());
                }
            },
        });
    }

    // ═══════════════════════════════════════════════════════════
    // PUBLIC API
    // ═══════════════════════════════════════════════════════════
    window.toast = showToast;

    window.toastSuccess = (msg, opts) => showToast('success', msg, opts);
    window.toastError   = (msg, opts) => showToast('error',   msg, opts);
    window.toastWarning = (msg, opts) => showToast('warning', msg, opts);
    window.toastInfo    = (msg, opts) => showToast('info',    msg, opts);

    // ═══════════════════════════════════════════════════════════
    // FLASH MESSAGES FROM SESSION
    // ═══════════════════════════════════════════════════════════
    @if (session('success'))
        showToast('success', @json(session('success')));
    @endif

    @if (session('error'))
        showToast('error', @json(session('error')));
    @endif

    @if (session('warning'))
        showToast('warning', @json(session('warning')));
    @endif

    @if (session('info'))
        showToast('info', @json(session('info')));
    @endif

    // ═══════════════════════════════════════════════════════════
    // VALIDATION ERRORS (rendered as a rich modal)
    // ═══════════════════════════════════════════════════════════
    @if ($errors->any() && session('swal_errors'))
        Swal.fire({
            ...@json($__swalBase ?? []),
            icon: 'error',
            title: 'Please fix the following:',
            html: `
                <ul style="text-align:left;margin:0;padding-left:1.25rem;list-style:disc;">
                    @foreach ($errors->all() as $error)
                        <li style="margin-bottom:0.35rem;">{{ $error }}</li>
                    @endforeach
                </ul>
            `,
            confirmButtonText: 'Got it',
        });
    @endif

    // ═══════════════════════════════════════════════════════════
    // CONFIRM-ON-SUBMIT FOR FORMS
    // ═══════════════════════════════════════════════════════════
    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', function (e) {
            if (form.dataset.confirmed === '1') return;
            e.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: form.dataset.confirmTitle || 'Are you sure?',
                text:  form.dataset.confirm,
                showCancelButton: true,
                confirmButtonText: form.dataset.confirmButton || 'Yes, proceed',
                cancelButtonText:  form.dataset.cancelButton  || 'Cancel',
                confirmButtonColor: form.dataset.confirmColor || '#ef4444',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    form.dataset.confirmed = '1';
                    form.submit();
                }
            });
        });
    });

    // ═══════════════════════════════════════════════════════════
    // CONFIRM-ON-CLICK FOR LINKS/BUTTONS
    // ═══════════════════════════════════════════════════════════
    document.querySelectorAll('[data-confirm]').forEach((el) => {
        if (el.tagName === 'FORM') return;

        el.addEventListener('click', function (e) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: el.dataset.confirmTitle || 'Are you sure?',
                text:  el.dataset.confirm,
                showCancelButton: true,
                confirmButtonText: el.dataset.confirmButton || 'Yes, proceed',
                cancelButtonText:  el.dataset.cancelButton  || 'Cancel',
                confirmButtonColor: el.dataset.confirmColor || '#ef4444',
                reverseButtons: true,
            }).then((result) => {
                if (!result.isConfirmed) return;
                if (el.tagName === 'A' && el.href) {
                    window.location.href = el.href;
                } else if (el.form) {
                    el.form.submit();
                }
            });
        });
    });

    // ═══════════════════════════════════════════════════════════
    // HELPERS
    // ═══════════════════════════════════════════════════════════
    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
})();
</script>