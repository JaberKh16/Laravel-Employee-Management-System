{{--
|--------------------------------------------------------------------------
| Modern Loading Button Component (Remix Icons)
|--------------------------------------------------------------------------
| Params:
|   $id         (required)  unique id
|   $label      (required)  button label
|   $loading    (optional)  text while loading (default: 'Loading…')
|   $type       (optional)  'submit' | 'button' | 'reset'  (default: 'submit')
|   $variant    (optional)  'primary' | 'indigo' | 'success' | 'danger'
|                            | 'warning' | 'neutral' | 'white' | 'ghost'
|                            (default: 'primary')
|   $size       (optional)  'sm' | 'md' | 'lg'  (default: 'md')
|   $icon       (optional)  FA icon class — overrides default per variant
|   $loadingIcon (optional) custom icon class during loading (unused if spinner)
|   $class      (optional)  extra classes
|   $attrs      (optional)  extra HTML attributes as raw string
|
| Usage:
|   @include('components.loading.button', [
|       'id' => 'saveBtn', 'label' => 'Save Changes',
|       'loading' => 'Saving…', 'variant' => 'success',
|   ])
|--------------------------------------------------------------------------
--}}


@php
    $id          = $id          ?? 'btn-' . uniqid();
    $label       = $label       ?? 'Submit';
    $loading     = $loading     ?? 'Loading…';
    $type        = $type        ?? 'submit';
    $variant     = $variant     ?? 'primary';
    $size        = $size        ?? 'md';
    $icon        = $icon        ?? null;
    $loadingIcon = $loadingIcon ?? null;
    $class       = $class       ?? '';
    $attrs       = $attrs       ?? '';

    /* ── Variants ─────────────────────────────────────────────── */
    $variants = [
        'primary' => [
            'class' => 'bg-slate-900 hover:bg-slate-800 active:bg-slate-950 text-white shadow-slate-900/20 focus-visible:ring-slate-500 dark:bg-slate-700 dark:hover:bg-slate-600',
            'icon'  => 'ri-flashlight-fill',
        ],
        'indigo' => [
            'class' => 'bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white shadow-indigo-500/30 focus-visible:ring-indigo-500',
            'icon'  => 'ri-filter-3-fill',
        ],
        'success' => [
            'class' => 'bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white shadow-emerald-500/30 focus-visible:ring-emerald-500',
            'icon'  => 'ri-check-line',
        ],
        'danger' => [
            'class' => 'bg-red-600 hover:bg-red-700 active:bg-red-800 text-white shadow-red-500/30 focus-visible:ring-red-500',
            'icon'  => 'ri-alert-fill',
        ],
        'warning' => [
            'class' => 'bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white shadow-amber-500/30 focus-visible:ring-amber-500',
            'icon'  => 'ri-error-warning-fill',
        ],
        'neutral' => [
            'class' => 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 active:bg-gray-100 shadow-gray-200/50 focus-visible:ring-gray-400 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700',
            'icon'  => 'ri-settings-3-fill',
        ],
        'white' => [
            'class' => 'bg-white text-indigo-600 hover:bg-indigo-50 active:bg-indigo-100 shadow-indigo-500/20 focus-visible:ring-indigo-500',
            'icon'  => 'ri-checkbox-circle-fill',
        ],
        'ghost' => [
            'class' => 'bg-transparent text-gray-700 hover:bg-gray-100 active:bg-gray-200 focus-visible:ring-gray-400 dark:text-gray-200 dark:hover:bg-gray-800',
            'icon'  => 'ri-arrow-right-line',
        ],
    ];

    $variantData  = $variants[$variant] ?? $variants['primary'];
    $variantClass = $variantData['class'];
    $finalIcon    = $icon ?: $variantData['icon'];

    /* ── Sizes ────────────────────────────────────────────────── */
    $sizes = [
        'sm' => ['btn' => 'px-3 py-1.5 text-xs rounded-lg gap-1.5', 'icon' => 'text-xs', 'spinner' => 'h-3 w-3'],
        'md' => ['btn' => 'px-4 py-2.5 text-sm rounded-xl gap-2',   'icon' => 'text-base', 'spinner' => 'h-4 w-4'],
        'lg' => ['btn' => 'px-6 py-3 text-base rounded-xl gap-2.5', 'icon' => 'text-lg', 'spinner' => 'h-5 w-5'],
    ];
    $sizeData = $sizes[$size] ?? $sizes['md'];
@endphp

<button type="{{ $type }}"
        id="{{ $id }}"
        data-loading-text="{{ $loading }}"
        data-original-icon="{{ $finalIcon }}"
        {!! $attrs !!}
        class="btn-loading group relative inline-flex items-center justify-center font-semibold
               shadow-sm transition-all duration-200
               hover:-translate-y-px hover:shadow-md
               active:translate-y-0 active:shadow-sm
               focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2
               disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:translate-y-0 disabled:hover:shadow-sm
               {{ $sizeData['btn'] }} {{ $variantClass }} {{ $class }}">

    {{-- Original icon (hidden during loading) --}}
    @if ($finalIcon)
        <i class="{{ $finalIcon }} btn-icon {{ $sizeData['icon'] }} leading-none transition-all duration-200 group-hover:scale-110"></i>
    @endif

    {{-- Dual-ring spinner (shown during loading) --}}
    <span class="btn-spinner hidden relative {{ $sizeData['spinner'] }} shrink-0">
        <span class="absolute inset-0 rounded-full border-2 border-current opacity-25"></span>
        <span class="absolute inset-0 rounded-full border-2 border-transparent border-t-current animate-spin"></span>
    </span>

    {{-- Label --}}
    <span class="btn-label whitespace-nowrap leading-none">{{ $label }}</span>

    {{-- Hover glow --}}
    <span class="pointer-events-none absolute inset-0 rounded-[inherit] opacity-0 group-hover:opacity-100 transition-opacity duration-200"
          style="background: radial-gradient(circle at center, currentColor 0%, transparent 70%); opacity: 0.05;"></span>
</button>


<script>
if (typeof window.setButtonLoading !== 'function') {
    window.setButtonLoading = function (id, loading) {
        const btn = document.getElementById(id);
        if (!btn) return;

        const iconEl  = btn.querySelector('.btn-icon');
        const spinEl  = btn.querySelector('.btn-spinner');
        const labelEl = btn.querySelector('.btn-label');
        if (!labelEl) return;

        if (!btn.dataset.originalLabel) {
            btn.dataset.originalLabel = labelEl.textContent.trim();
        }
        const original    = btn.dataset.originalLabel;
        const loadingText = btn.dataset.loadingText || 'Loading…';

        if (loading) {
            btn.disabled = true;
            btn.classList.add('cursor-wait');
            if (iconEl) iconEl.classList.add('hidden');
            if (spinEl) spinEl.classList.remove('hidden');
            labelEl.textContent = loadingText;
            labelEl.classList.add('opacity-90');
        } else {
            btn.disabled = false;
            btn.classList.remove('cursor-wait');
            if (iconEl) iconEl.classList.remove('hidden');
            if (spinEl) spinEl.classList.add('hidden');
            labelEl.textContent = original;
            labelEl.classList.remove('opacity-90');
        }
    };
}
</script>
