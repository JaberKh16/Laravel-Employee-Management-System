{{-- resources/views/admin/layout/main.blade.php --}}
<style>
    .swal2-dark { background: #1f2937; color: #f9fafb; }
.dark .swal2-popup { background: #1f2937 !important; color: #f9fafb !important; }
</style>

{{-- SweetAlert2 flash + confirm helpers --}}
<script>
(function () {
    'use strict';

    if (!window.Swal) {
        console.warn('[sweetalert] Swal not loaded — include the CDN or npm import first.');
        return;
    }

    // ---------- Theme presets ----------
    const base = {
        confirmButtonColor: '#6366f1',   // indigo-500
        cancelButtonColor:  '#6b7280',   // gray-500
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
            // Match Tailwind's dark mode if the <html> has .dark
            if (document.documentElement.classList.contains('dark')) {
                el.style.background = '#1f2937';
                el.style.color = '#f9fafb';
            }
        },
    };

    // ---------- Flash messages from session ----------
    @if (session('success'))
        Swal.fire({ ...@json($__swalBase ?? []), ...{
            icon: 'success',
            title: @json(session('success')),
            timer: 2500,
            timerProgressBar: true,
            showConfirmButton: false,
        }});
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: @json(session('error')),
            confirmButtonText: 'OK',
        });
    @endif

    @if (session('warning'))
        Swal.fire({
            icon: 'warning',
            title: @json(session('warning')),
            confirmButtonText: 'OK',
        });
    @endif

    @if (session('info'))
        Swal.fire({
            icon: 'info',
            title: @json(session('info')),
            confirmButtonText: 'OK',
        });
    @endif

    // ---------- Validation errors (optional) ----------
    @if ($errors->any() && session('swal_errors'))
        Swal.fire({
            icon: 'error',
            title: 'Please fix the following:',
            html: `<ul style="text-align:left;margin:0;padding-left:1.25rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>`,
        });
    @endif

    // ---------- Confirm-on-submit for forms ----------
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
            }).then((result) => {
                if (result.isConfirmed) {
                    form.dataset.confirmed = '1';
                    form.submit();
                }
            });
        });
    });

    // ---------- Confirm-on-click for links/buttons ----------
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

    // ---------- Expose a tiny API for ad-hoc calls ----------
    window.toast = function (message, icon = 'success') {
        return Swal.fire({
            toast: true,
            position: 'top-end',
            icon,
            title: message,
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
        });
    };
})();
</script>