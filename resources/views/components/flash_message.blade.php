@php
    $flashTypes = [
        'success' => [
            'icon'  => 'M4.5 12.75l6 6 9-13.5',
            'title' => 'Success',
            'wrap'  => 'border-emerald-200 bg-emerald-50 dark:border-emerald-900/40 dark:bg-emerald-950/30',
            'badge' => 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-400',
            'text'  => 'text-emerald-900 dark:text-emerald-200',
            'body'  => 'text-emerald-800 dark:text-emerald-300',
            'close' => 'text-emerald-600 hover:bg-emerald-100 dark:text-emerald-400 dark:hover:bg-emerald-900/40',
        ],
        'error' => [
            'icon'  => 'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z',
            'title' => 'Error',
            'wrap'  => 'border-red-200 bg-red-50 dark:border-red-900/40 dark:bg-red-950/30',
            'badge' => 'bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-400',
            'text'  => 'text-red-900 dark:text-red-200',
            'body'  => 'text-red-800 dark:text-red-300',
            'close' => 'text-red-600 hover:bg-red-100 dark:text-red-400 dark:hover:bg-red-900/40',
        ],
        'warning' => [
            'icon'  => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
            'title' => 'Warning',
            'wrap'  => 'border-amber-200 bg-amber-50 dark:border-amber-900/40 dark:bg-amber-950/30',
            'badge' => 'bg-amber-100 text-amber-600 dark:bg-amber-900/40 dark:text-amber-400',
            'text'  => 'text-amber-900 dark:text-amber-200',
            'body'  => 'text-amber-800 dark:text-amber-300',
            'close' => 'text-amber-600 hover:bg-amber-100 dark:text-amber-400 dark:hover:bg-amber-900/40',
        ],
        'info' => [
            'icon'  => 'M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z',
            'title' => 'Info',
            'wrap'  => 'border-sky-200 bg-sky-50 dark:border-sky-900/40 dark:bg-sky-950/30',
            'badge' => 'bg-sky-100 text-sky-600 dark:bg-sky-900/40 dark:text-sky-400',
            'text'  => 'text-sky-900 dark:text-sky-200',
            'body'  => 'text-sky-800 dark:text-sky-300',
            'close' => 'text-sky-600 hover:bg-sky-100 dark:text-sky-400 dark:hover:bg-sky-900/40',
        ],
    ];
@endphp

@foreach ($flashTypes as $type => $style)
    @if (session($type))
        <div class="mb-4 flex items-start gap-3 rounded-xl border p-4 shadow-sm {{ $style['wrap'] }}" role="alert">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $style['badge'] }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $style['icon'] }}"/>
                </svg>
            </span>
            <div class="flex-1">
                <p class="text-sm font-semibold {{ $style['text'] }}">{{ $style['title'] }}</p>
                <p class="mt-0.5 text-sm {{ $style['body'] }}">{{ session($type) }}</p>
            </div>
            <button type="button"
                    onclick="this.closest('[role=alert]').remove()"
                    class="shrink-0 rounded-lg p-1 transition {{ $style['close'] }}">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif
@endforeach