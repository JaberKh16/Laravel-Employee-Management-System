{{--
|--------------------------------------------------------------------------
| Global Loading Overlay
|--------------------------------------------------------------------------
| Usage:  @include('admin.components.loading.overlay')
| Then:   window.showLoader('Loading…');
|         window.hideLoader();
--}}

<div id="globalLoader"
     class="fixed inset-0 z-[9999] hidden items-center justify-center"
     style="background: rgba(15, 23, 42, 0.55); backdrop-filter: blur(6px);">

    <div class="flex flex-col items-center gap-4">

        {{-- Animated rings --}}
        <div class="relative h-20 w-20">
            <div class="absolute inset-0 rounded-full border-4 border-indigo-200/30"></div>
            <div class="absolute inset-0 rounded-full border-4 border-transparent border-t-indigo-500 animate-spin"></div>
            <div class="absolute inset-2 rounded-full border-4 border-transparent border-t-purple-500 animate-spin"
                 style="animation-duration: 1.4s; animation-direction: reverse;"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="h-3 w-3 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 animate-pulse"></div>
            </div>
        </div>

        {{-- Message --}}
        <p id="globalLoaderText"
           class="text-sm font-semibold tracking-wide text-white drop-shadow-md">
            Loading…
        </p>

        {{-- Optional progress bar --}}
        <div class="h-1 w-48 overflow-hidden rounded-full bg-white/20">
            <div class="h-full w-1/2 animate-pulse rounded-full bg-gradient-to-r from-indigo-500 to-purple-500"></div>
        </div>
    </div>
</div>

<style>
    #globalLoader.show { display: flex; animation: fadeIn 0.15s ease; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>

<script>
(function () {
    const loader = document.getElementById('globalLoader');
    const loaderText = document.getElementById('globalLoaderText');

    window.showLoader = function (message) {
        if (typeof message === 'string') loaderText.textContent = message;
        loader.classList.remove('hidden');
        loader.classList.add('show');
        // Prevent double-click on links/buttons
        document.body.style.pointerEvents = 'none';
        loader.style.pointerEvents = 'auto';
    };

    window.hideLoader = function () {
        loader.classList.add('hidden');
        loader.classList.remove('show');
        document.body.style.pointerEvents = '';
    };
})();
</script>