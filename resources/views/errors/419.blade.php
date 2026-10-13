@extends('errors.minimal')

@section('title', __('419 — Session Expired'))
@section('code-label', __('Error 419'))
@section('heading', __('Your session expired'))
@section('description', __('For your security, we logged you out due to inactivity. Please sign in again to continue where you left off.'))

@section('glow-class', 'bg-violet-500')
@section('icon-bg-class', 'bg-gradient-to-br from-violet-500 via-purple-500 to-indigo-600')
@section('icon-shadow', 'shadow-violet-500/30')

@section('icon')
    <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
@endsection

@section('actions')
    <a href="{{ route('login') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl
              text-sm font-semibold text-white
              bg-gradient-to-br from-violet-600 to-indigo-600
              hover:from-violet-700 hover:to-indigo-700
              shadow-lg shadow-violet-500/30 hover:shadow-xl hover:shadow-violet-500/40
              transition-all duration-200 hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
        </svg>
        {{ __('Sign in again') }}
    </a>
@endsection

@section('footer-hint')
    {{ __('Error code:') }} <span class="font-mono font-semibold">419 · Page Expired</span>
@endsection