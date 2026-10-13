@extends('errors.minimal')

@section('title', __('503 — Under Maintenance'))
@section('code-label', __('Error 503'))
@section('heading', __("We'll be right back"))
@section('description', __("We're performing scheduled maintenance to improve your experience. Please check back in a little while — thanks for your patience!"))

@section('glow-class', 'bg-emerald-500')
@section('icon-bg-class', 'bg-gradient-to-br from-emerald-500 via-teal-500 to-cyan-600')
@section('icon-shadow', 'shadow-emerald-500/30')

@section('icon')
    <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
    </svg>
@endsection

@section('actions')
    <button type="button" onclick="window.location.reload()"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl
                   text-sm font-semibold text-white
                   bg-gradient-to-br from-emerald-600 to-teal-600
                   hover:from-emerald-700 hover:to-teal-700
                   shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40
                   transition-all duration-200 hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
        {{ __('Check again') }}
    </button>
@endsection

@section('footer-hint')
    {{ __('We appreciate your patience.') }}
@endsection