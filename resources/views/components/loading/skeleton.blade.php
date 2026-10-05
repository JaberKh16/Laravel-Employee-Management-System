{{--
|--------------------------------------------------------------------------
| Skeleton Rows (shimmer placeholder for tables)
|--------------------------------------------------------------------------
| Params:
|   $rows    (optional) number of placeholder rows (default: 6)
|   $cols    (optional) number of columns (default: 5)
|
| Usage:
|   @include('admin.components.loading.skeleton', ['rows' => 8, 'cols' => 6])
--}}

@php
    $rows = $rows ?? 6;
    $cols = $cols ?? 5;
@endphp

<style>
    .skeleton-shimmer {
        background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
        background-size: 200% 100%;
        animation: shimmer 1.4s infinite;
    }
    .dark .skeleton-shimmer {
        background: linear-gradient(90deg, #374151 25%, #4b5563 50%, #374151 75%);
        background-size: 200% 100%;
    }
    @keyframes shimmer {
        0%   { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
</style>

@for ($i = 0; $i < $rows; $i++)
    <tr class="border-b border-gray-100 dark:border-gray-700">
        @for ($j = 0; $j < $cols; $j++)
            <td class="px-4 py-4">
                <div class="skeleton-shimmer h-4 rounded-md"
                     style="width: {{ [40, 70, 55, 80, 35, 60][$j % 6] }}%;"></div>
            </td>
        @endfor
    </tr>
@endfor