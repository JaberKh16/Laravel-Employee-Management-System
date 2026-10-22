 {{-- Scroll-to-top behavior --}}
    <script>
        (function () {
            var btn = document.getElementById('scroll-to-top');
            if (!btn) return;

            function onScroll() {
                var visible = window.scrollY > 300;
                btn.dataset.visible = visible ? 'true' : 'false';
                btn.classList.toggle('hidden', !visible);
            }

            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll();

            btn.addEventListener('click', function () {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        })();
    </script>