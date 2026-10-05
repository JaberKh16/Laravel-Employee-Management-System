
<script>
(function () {
    'use strict';

    // ─────────────────────────────────────────────────────────────
    // Guard: bail if showLoader hasn't been defined by the overlay
    // ─────────────────────────────────────────────────────────────
    if (typeof window.showLoader !== 'function') {
        console.warn('[Loader] window.showLoader not defined. Check that components.loading.overlay is included before this script.');
        return;
    }

    // ─────────────────────────────────────────────────────────────
    // Helper: element already has a loader attached?
    // Prevents duplicate listeners when scripts are injected twice.
    // ─────────────────────────────────────────────────────────────
    function markAndCheck(el, flag) {
        if (!el) return false;
        if (el.dataset[flag] === '1') return true;   // already wired
        el.dataset[flag] = '1';
        return false;
    }

    // ─────────────────────────────────────────────────────────────
    // Helper: skip links that shouldn't trigger the loader
    // ─────────────────────────────────────────────────────────────
    function shouldSkipLink(el) {
        if (!el || !el.href) return true;
        if (el.classList.contains('no-loader'))         return true;
        if (el.classList.contains('download-item'))     return true;
        if (el.hasAttribute('download'))                return true;
        if (el.target === '_blank')                     return true;
        if (el.href.startsWith('javascript:'))          return true;
        if (el.href.startsWith('mailto:'))              return true;
        if (el.href.startsWith('tel:'))                 return true;
        if (el.getAttribute('href') === '#')            return true;
        if (el.getAttribute('href')?.startsWith('#'))   return true;
        if (el.hostname && el.hostname !== window.location.hostname) return true;
        return false;
    }

    // ─────────────────────────────────────────────────────────────
    // Helper: is the target already showing a spinner?
    // (e.g. the button component — we don't want to double-spin)
    // ─────────────────────────────────────────────────────────────
    function isButtonAlreadyLoading(btn) {
        if (!btn) return false;
        const spinEl = btn.querySelector('.spinner-btn, .spinner');
        return spinEl && !spinEl.classList.contains('hidden');
    }

    // ═════════════════════════════════════════════════════════════
    // 1. LINKS — show overlay on click
    // ═════════════════════════════════════════════════════════════
    function wireLinks(root) {
        (root || document)
            .querySelectorAll('a[href]')
            .forEach(link => {
                if (markAndCheck(link, 'loaderLinked')) return;
                link.addEventListener('click', function (e) {
                    if (shouldSkipLink(this)) return;
                    // If user opened in new tab via modifier, don't show overlay
                    if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
                    window.showLoader('Loading…');
                });
            });
    }

    // ═════════════════════════════════════════════════════════════
    // 2. FORMS — show overlay on submit
    //    (respects [data-loading="…"] for custom messages)
    //    (respects .no-loader to skip)
    //    (auto-spins a submit button that has an ID)
    // ═════════════════════════════════════════════════════════════
    function wireForms(root) {
        (root || document)
            .querySelectorAll('form')
            .forEach(form => {
                if (markAndCheck(form, 'loaderWired')) return;
                form.addEventListener('submit', function () {
                    if (this.classList.contains('no-loader')) return;

                    const msg = this.dataset.loading || this.dataset.loadingText || 'Processing…';
                    window.showLoader(msg);

                    // Auto-spin the submit button if it has an ID
                    // (skip if already spinning to avoid double-spin)
                    const submitBtn = this.querySelector('button[type="submit"]:not([disabled])');
                    if (
                        submitBtn &&
                        submitBtn.id &&
                        typeof window.setButtonLoading === 'function' &&
                        !isButtonAlreadyLoading(submitBtn)
                    ) {
                        window.setButtonLoading(submitBtn.id, true);
                    }
                });
            });
    }

    // ═════════════════════════════════════════════════════════════
    // 3. BUTTONS with [data-loading] — show overlay on click
    //    (skip type="submit" — the form handler already covers them)
    // ═════════════════════════════════════════════════════════════
    function wireButtons(root) {
        (root || document)
            .querySelectorAll('button[data-loading]')
            .forEach(btn => {
                if (markAndCheck(btn, 'loaderWired')) return;
                // Submit buttons are handled by wireForms()
                if (btn.type === 'submit') return;

                btn.addEventListener('click', function () {
                    window.showLoader(this.dataset.loading || 'Loading…');
                });
            });
    }

    // ═════════════════════════════════════════════════════════════
    // 4. BFCACHE safety net — hide loader when returning via back/forward
    // ═════════════════════════════════════════════════════════════
    window.addEventListener('pageshow', function (e) {
        if (e.persisted) {
            window.hideLoader();
            // Re-enable any disabled buttons
            document.querySelectorAll('button[disabled]').forEach(b => {
                // Only re-enable buttons that were loading (had a spinner)
                if (b.querySelector('.spinner-btn, .spinner')) {
                    b.disabled = false;
                }
            });
        }
    });

    // ═════════════════════════════════════════════════════════════
    // 5. AUTO-HIDE after page fully loads (safety)
    // ═════════════════════════════════════════════════════════════
    window.addEventListener('load', function () {
        // If a navigation left the loader visible, hide it after everything settles
        window.setTimeout(function () {
            const loader = document.getElementById('globalLoader');
            if (loader && loader.classList.contains('show')) {
                window.hideLoader();
            }
        }, 300);
    });

    // ═════════════════════════════════════════════════════════════
    // BOOT
    // ═════════════════════════════════════════════════════════════
    function boot() {
        wireLinks();
        wireForms();
        wireButtons();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        // DOM already ready (script injected late)
        boot();
    }

    // ═════════════════════════════════════════════════════════════
    // EXPOSE for dynamically added elements (modals, AJAX content)
    // Usage: window.rewireLoader(containerElement)
    // ═════════════════════════════════════════════════════════════
    window.rewireLoader = function (root) {
        wireLinks(root);
        wireForms(root);
        wireButtons(root);
    };
})();
</script>