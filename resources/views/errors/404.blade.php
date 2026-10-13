@extends('errors.minimal')

@section('title', __('404 — Page Not Found'))
@section('code-label', __('Error 404'))
@section('heading', __('Page not found'))
@section('description', __("The page you're looking for doesn't exist or has been moved. Check the URL or head back to somewhere familiar."))

@section('glow-class', 'bg-blue-500')
@section('icon-bg-class', 'bg-gradient-to-br from-blue-500 via-indigo-500 to-violet-600')
@section('icon-shadow', 'shadow-blue-500/30')

@section('icon')
    <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
              d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
@endsection

@section('extra')
    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full
                bg-blue-50 text-blue-700 text-xs font-medium
                ring-1 ring-blue-200/60
                dark:bg-blue-500/10 dark:text-blue-400 dark:ring-blue-500/20">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
        </svg>
        {{ __('URL:') }} <code class="font-mono">{{ Str::limit(request()->path(), 40) }}</code>
    </div>
@endsection

@section('actions')
    <a href="{{ url('/') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl
              text-sm font-semibold text-white
              bg-gradient-to-br from-blue-600 to-indigo-600
              hover:from-blue-700 hover:to-indigo-700
              shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40
              transition-all duration-200 hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        {{ __('Go home') }}
    </a>

    <button type="button" onclick="history.back()"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl
                   text-sm font-semibold
                   text-gray-700 dark:text-gray-200
                   bg-white dark:bg-gray-800
                   ring-1 ring-gray-200 dark:ring-gray-700
                   hover:bg-gray-50 dark:hover:bg-gray-700
                   transition-all duration-200 hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        {{ __('Go back') }}
    </button>
@endsection

@section('footer-hint')
    {{ __('Error code:') }} <span class="font-mono font-semibold">404 · Not Found</span>
@endsection