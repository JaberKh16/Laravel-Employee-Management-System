{{--
|--------------------------------------------------------------------------
| Inline Spinner (small, sits inline with text)
|--------------------------------------------------------------------------
| Params:
|   $size   (optional) 'sm' | 'md' | 'lg'  (default: 'sm')
|   $color  (optional) 'indigo' | 'white' | 'slate' | 'emerald'
|
| Usage:
|   @include('admin.components.loading.inline')
|   @include('admin.components.loading.inline', ['size' => 'md', 'color' => 'white'])
--}}

@php
    $size  = $size  ?? 'sm';
    $color = $color ?? 'indigo';

    $sizes = ['sm' => 'h-4 w-4', 'md' => 'h-6 w-6', 'lg' => 'h-8 w-8'];
    $sizeClass = $sizes[$size] ?? $sizes['sm'];

    $colors = [
        'indigo'  => 'text-indigo-600',
        'white'   => 'text-white',
        'slate'   => 'text-slate-500',
        'emerald' => 'text-emerald-600',
    ];
    $colorClass = $colors[$color] ?? $colors['indigo'];
@endphp

<span class="inline-flex items-center justify-center {{ $colorClass }}">
    <svg class="{{ $sizeClass }} animate-spin" viewBox="0 0 24 24" fill="none">
        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" class="opacity-25"/>
        <path fill="currentColor" class="opacity-90"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
    </svg>
</span>