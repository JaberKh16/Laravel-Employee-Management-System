@extends('errors.minimal')

@section('title', __('500 — Server Error'))
@section('code-label', __('Error 500'))
@section('heading', __('Something went wrong'))
@section('description', __("We're experiencing a temporary issue on our end. Our team has been notified and is working on a fix. Please try again in a few moments."))

@section('glow-class', 'bg-rose-500')
@section('icon-bg-class', 'bg-gradient-to-br from-rose-500 via-red-500 to-orange-500')
@section('icon-shadow', 'shadow-rose-500/30')

@section('icon')
    <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
    </svg>
@endsection

@section('extra')
    @if (config('app.debug') && isset($exception))
        <div class="mt-6 text-left max-w-xl mx-auto">
            <details class="rounded-xl border border-rose-200 dark:border-rose-500/20
                            bg-rose-50/60 dark:bg-rose-500/5 overflow-hidden">
                <summary class="cursor-pointer select-none px-4 py-2.5 text-sm font-semibold
                                text-rose-800 dark:text-rose-300
                                hover:bg-rose-100/60 dark:hover:bg-rose-500/10
                                transition-colors">
                    <span class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ __('Debug details (visible in dev only)') }}
                    </span>
                </summary>
                <div class="px-4 py-3 text-xs font-mono text-rose-900 dark:text-rose-200
                            border-t border-rose-200 dark:border-rose-500/20
                            overflow-x-auto whitespace-pre-wrap break-words">
                    <strong>{{ class_basename($exception) }}</strong>{{ ': ' }}{{ $exception->getMessage() }}
                    @if ($exception->getFile())
                        {"\n\n"}<span class="text-rose-700 dark:text-rose-400">at</span>
                        {{ $exception->getFile() }}:{{ $exception->getLine() }}
                    @endif
                </div>
            </details>
        </div>
    @endif
@endsection

@section('actions')
    <button type="button" onclick="window.location.reload()"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl
                   text-sm font-semibold text-white
                   bg-gradient-to-br from-blue-600 to-indigo-600
                   hover:from-blue-700 hover:to-indigo-700
                   shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40
                   transition-all duration-200 hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
        {{ __('Reload page') }}
    </button>

    <a href="{{ url('/') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl
              text-sm font-semibold
              text-gray-700 dark:text-gray-200
              bg-white dark:bg-gray-800
              ring-1 ring-gray-200 dark:ring-gray-700
              hover:bg-gray-50 dark:hover:bg-gray-700
              transition-all duration-200 hover:-translate-y-0.5">
        {{ __('Go home') }}
    </a>
@endsection

@section('footer-hint')
    {{ __('Error code:') }} <span class="font-mono font-semibold">500 · Internal Server Error</span>
@endsection