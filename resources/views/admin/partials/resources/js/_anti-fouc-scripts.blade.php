{{-- Anti-FOUC: apply dark class BEFORE CSS loads to prevent white flash --}}
<script>
    (function () {
        try {
            var stored = localStorage.getItem('color-theme');
            var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (stored === 'dark' || (!stored && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        } catch (e) {
            // localStorage unavailable (private mode, disabled cookies) — fail silently
        }
    })();
</script>