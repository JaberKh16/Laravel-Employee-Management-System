@extends('admin.layout.main')
@section('title', __('Social Services'))

@push('dashboard_style')
<style>
    /* ===== Modal smooth transitions ===== */
    #service-modal { transition: opacity 220ms ease-out; }
    #service-modal.hidden { display: none; }

    #service-modal .modal-backdrop {
        opacity: 0;
        transition: opacity 220ms ease-out;
    }
    #service-modal.is-open .modal-backdrop { opacity: 1; }
    #service-modal.is-closing .modal-backdrop { opacity: 0; }

    #service-modal .modal-panel {
        opacity: 0;
        transform: translateY(12px) scale(0.97);
        transition:
            opacity 200ms ease-out,
            transform 260ms cubic-bezier(0.16, 1, 0.3, 1);
        will-change: transform, opacity;
    }
    #service-modal.is-open .modal-panel {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
    #service-modal.is-closing .modal-panel {
        opacity: 0;
        transform: translateY(8px) scale(0.98);
    }

    /* ===== Scrollable body ===== */
    #service-modal .modal-scroll {
        overflow-y: auto;
        overflow-x: hidden;
        overscroll-behavior: contain;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: rgba(107, 114, 128, 0.45) transparent;
    }
    #service-modal .modal-scroll::-webkit-scrollbar { width: 8px; }
    #service-modal .modal-scroll::-webkit-scrollbar-track { background: transparent; }
    #service-modal .modal-scroll::-webkit-scrollbar-thumb {
        background-color: rgba(107, 114, 128, 0.45);
        border-radius: 9999px;
    }
    #service-modal .modal-scroll::-webkit-scrollbar-thumb:hover {
        background-color: rgba(107, 114, 128, 0.7);
    }

    /* ===== View panes ===== */
    #service-modal .view-pane { display: none; }
    #service-modal .view-pane.is-active {
        display: block;
        animation: fadeInPane 240ms ease-out;
    }
    @keyframes fadeInPane {
        from { opacity: 0; transform: translateY(4px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ===== Provider card hover ===== */
    .provider-card {
        transition: transform 200ms ease, box-shadow 200ms ease, border-color 200ms ease;
    }
    .provider-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -12px rgba(0,0,0,0.25);
    }
    .provider-card.is-selected {
        border-color: rgb(59 130 246);
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.35);
    }

    /* ===== View-toggle segmented control ===== */
    .view-toggle {
        display: inline-flex;
        padding: 3px;
        border-radius: 10px;
        background: rgb(243 244 246);
    }
    .dark .view-toggle { background: rgb(31 41 55); }
    .view-toggle button {
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 500;
        border-radius: 8px;
        color: rgb(107 114 128);
        transition: all 160ms ease;
    }
    .view-toggle button.is-active {
        background: white;
        color: rgb(17 24 39);
        box-shadow: 0 1px 2px rgba(0,0,0,0.06);
    }
    .dark .view-toggle button.is-active {
        background: rgb(17 24 39);
        color: white;
    }

    @media (prefers-reduced-motion: reduce) {
        #service-modal,
        #service-modal .modal-backdrop,
        #service-modal .modal-panel,
        .provider-card,
        .view-toggle button { transition-duration: 0ms !important; }
    }
</style>
@endpush


@section('dashboard_content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ __('Social Services') }}
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Register and manage OAuth providers used on the sign-in page.') }}
                </p>
            </div>

            <button type="button"
                    id="open-register-modal"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg
                           text-sm font-semibold text-white
                           bg-blue-600 hover:bg-blue-700
                           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
                           dark:focus:ring-offset-gray-950 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                {{ __('Register service') }}
            </button>
        </div>

        {{-- Flash --}}
        @if (session('status'))
            <div class="mb-5 flex items-start gap-3 rounded-lg border
                        border-green-200 bg-green-50 px-4 py-3 text-sm
                        text-green-800 dark:border-green-900/40 dark:bg-green-900/20 dark:text-green-300">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.172 7.707 8.879a1 1 0 10-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if ($errors->any() && !old('__edit_id'))
            <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm
                        text-red-800 dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-300">
                <ul class="list-disc ps-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- List --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm
                    dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-900/60">
                    <tr class="text-left text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        <th class="px-4 py-3 w-10"></th>
                        <th class="px-4 py-3">{{ __('Provider') }}</th>
                        <th class="px-4 py-3 hidden md:table-cell">{{ __('Redirect URI') }}</th>
                        <th class="px-4 py-3 hidden sm:table-cell w-32">{{ __('Order') }}</th>
                        <th class="px-4 py-3 w-28">{{ __('Status') }}</th>
                        <th class="px-4 py-3 w-32 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-sm">
                    @forelse ($services as $service)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-colors">
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg
                                             text-white text-xs font-bold shadow-sm"
                                      style="background-color: {{ $service->brand_color ?: '#2563eb' }}">
                                    {{ strtoupper(substr($service->label, 0, 1)) }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $service->label }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $service->provider }}
                                    @if ($service->is_default)
                                        <span class="ms-2 inline-flex items-center px-1.5 py-0.5 rounded
                                                     bg-blue-100 text-blue-700 text-[10px] font-semibold
                                                     dark:bg-blue-500/20 dark:text-blue-300">
                                            {{ __('DEFAULT') }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-3 hidden md:table-cell">
                                <code class="text-xs text-gray-600 dark:text-gray-300 break-all">
                                    {{ $service->redirect_uri }}
                                </code>
                            </td>

                            <td class="px-4 py-3 hidden sm:table-cell text-gray-600 dark:text-gray-300">
                                {{ $service->sort_order }}
                            </td>

                            <td class="px-4 py-3">
                                @if ($service->is_enabled)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full
                                                 bg-green-100 text-green-700 text-xs font-medium
                                                 dark:bg-green-500/20 dark:text-green-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        {{ __('Enabled') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full
                                                 bg-gray-100 text-gray-600 text-xs font-medium
                                                 dark:bg-gray-700 dark:text-gray-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                        {{ __('Disabled') }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1">
                                    @php
                                        $socialPayloads = [
                                            "id"            => $service->id,
                                            "provider"      => $service->provider,
                                            "label"         => $service->label,
                                            "client_id"     => $service->client_id,
                                            "redirect_uri"  => $service->redirect_uri,
                                            "scopes"        => implode(", ", (array) $service->scopes),
                                            "icon"          => $service->icon,
                                            "brand_color"   => $service->brand_color,
                                            "sort_order"    => $service->sort_order,
                                            "is_enabled"    => $service->is_enabled,
                                            "is_default"    => $service->is_default,
                                            "update_url"    => route("social.services.update", $service),
                                        ];
                                    @endphp
                                    <button type="button"
                                            class="edit-service-btn p-2 rounded-lg text-gray-500
                                                   hover:text-blue-600 hover:bg-blue-50
                                                   dark:text-gray-400 dark:hover:text-blue-400 dark:hover:bg-blue-500/10
                                                   transition"
                                            title="{{ __('Edit') }}"
                                            data-payload='@json($socialPayloads)'>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </button>

                                    <form method="POST"
                                          action="{{ route('social.services.destroy', $service) }}"
                                          onsubmit="return confirm('{{ __('Delete this service?') }}')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-2 rounded-lg text-gray-500
                                                       hover:text-red-600 hover:bg-red-50
                                                       dark:text-gray-400 dark:hover:text-red-400 dark:hover:bg-red-500/10
                                                       transition"
                                                title="{{ __('Delete') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-16 text-center">
                                <div class="mx-auto w-14 h-14 rounded-full bg-gray-100 dark:bg-gray-800
                                            flex items-center justify-center text-gray-400 mb-3">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                              d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('No social services registered yet.') }}
                                </p>
                                <button type="button"
                                        class="open-register-modal mt-4 text-sm font-medium
                                               text-blue-600 hover:text-blue-500 dark:text-blue-400">
                                    {{ __('Register your first service →') }}
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">
            {{ __('Tip: Only enabled services appear on the sign-in page.') }}
        </p>
    </div>
</div>

{{-- ========================================================= --}}
{{-- Register / Edit Modal                                       --}}
{{-- ========================================================= --}}
<div id="service-modal"
     class="fixed inset-0 z-50 hidden"
     aria-modal="true"
     role="dialog"
     aria-labelledby="modal-title">

    {{-- Backdrop --}}
    <div class="modal-backdrop absolute inset-0 bg-gray-900/60 backdrop-blur-sm" data-close-modal></div>

    {{-- Scroll layer --}}
    <div class="modal-scroll absolute inset-0 p-4 sm:p-6">
        <div class="min-h-full flex items-start justify-center">
            <div class="modal-panel relative w-full max-w-3xl my-4 sm:my-8
                        bg-white dark:bg-gray-900
                        rounded-2xl shadow-2xl ring-1 ring-black/5
                        dark:ring-white/10 overflow-hidden">

                {{-- ============== Header ============== --}}
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800
                            bg-white dark:bg-gray-900 sticky top-0 z-20">
                    <div class="flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <h2 id="modal-title"
                                class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                                {{ __('Register social service') }}
                            </h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                {{ __('Pick from the gallery or configure manually.') }}
                            </p>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0">
                            {{-- Theme toggle (icon) --}}
                            <button type="button"
                                    id="modal-theme-toggle"
                                    class="p-2 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100
                                           dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800
                                           transition"
                                    title="{{ __('Toggle theme') }}">
                                <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                </svg>
                            </button>

                            {{-- Close --}}
                            <button type="button" data-close-modal
                                    class="p-2 rounded-lg text-gray-400
                                           hover:text-gray-700 hover:bg-gray-100
                                           dark:hover:text-gray-200 dark:hover:bg-gray-800
                                           transition"
                                    title="{{ __('Close') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- ===== View toggle (Gallery / Manual) ===== --}}
                    <div class="mt-4 flex items-center justify-between gap-3">
                        <div class="view-toggle" role="tablist" aria-label="{{ __('View mode') }}">
                            <button type="button"
                                    class="view-switch-btn is-active"
                                    data-view="gallery"
                                    role="tab"
                                    aria-selected="true">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                    {{ __('Gallery') }}
                                </span>
                            </button>
                            <button type="button"
                                    class="view-switch-btn"
                                    data-view="manual"
                                    role="tab"
                                    aria-selected="false">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ __('Manual') }}
                                </span>
                            </button>
                        </div>

                        {{-- Live selection preview --}}
                        <div id="selection-preview"
                             class="hidden sm:flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                            <span class="w-2 h-2 rounded-full bg-gray-300 dark:bg-gray-700"></span>
                            <span id="selection-preview-text">{{ __('No provider selected') }}</span>
                        </div>
                    </div>
                </div>

                {{-- ============== Form wraps both views ============== --}}
                <form id="service-form"
                      method="POST"
                      action="{{ route('social.services.store') }}"
                      class="p-6 space-y-5">
                    @csrf
                    <input type="hidden" name="_method" id="form-method" value="POST">
                    <input type="hidden" name="__edit_id" id="form-edit-id" value="">

                    {{-- ================================================= --}}
                    {{-- VIEW 1 — GALLERY                                    --}}
                    {{-- ================================================= --}}
                    <div class="view-pane is-active" data-view-pane="gallery">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            {{ __('Choose a provider to pre-fill the configuration. You can still edit every field afterwards.') }}
                        </p>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach ($knownProviders as $key => $meta)
                                <button type="button"
                                        class="provider-card group relative flex flex-col items-start gap-3
                                               p-4 rounded-xl border text-left
                                               border-gray-200 dark:border-gray-800
                                               bg-white dark:bg-gray-900
                                               hover:border-blue-400 dark:hover:border-blue-500
                                               focus:outline-none focus:ring-2 focus:ring-blue-500/40"
                                        data-provider="{{ $key }}"
                                        data-label="{{ $meta['label'] }}"
                                        data-icon="{{ $meta['icon'] }}"
                                        data-color="{{ $meta['color'] }}"
                                        data-scopes="{{ $meta['scopes'] }}">
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg
                                                 text-white text-sm font-bold shadow-sm"
                                          style="background-color: {{ $meta['color'] }}">
                                        {{ strtoupper(substr($meta['label'], 0, 1)) }}
                                    </span>
                                    <span class="min-w-0">
                                        <span class="block text-sm font-semibold text-gray-900 dark:text-white truncate">
                                            {{ $meta['label'] }}
                                        </span>
                                        <span class="block text-[11px] text-gray-500 dark:text-gray-400 font-mono truncate">
                                            {{ $key }}
                                        </span>
                                    </span>

                                    {{-- checkmark (hidden until selected) --}}
                                    <span class="check-indicator absolute top-3 right-3 opacity-0 transition-opacity">
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-600 text-white">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                      d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </span>
                                    </span>
                                </button>
                            @endforeach
                        </div>

                        <div class="mt-5 flex justify-end">
                            <button type="button" id="continue-to-manual"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium
                                           bg-gray-900 text-white hover:bg-gray-800
                                           dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100 transition">
                                {{ __('Continue ') }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M14 5l7 7-7 7M21 12H3"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- ================================================= --}}
                    {{-- VIEW 2 — MANUAL                                     --}}
                    {{-- ================================================= --}}
                    <div class="view-pane" data-view-pane="manual">

                        {{-- Row: provider + label --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="provider" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                    {{ __('Provider key') }} <span class="text-red-500">*</span>
                                </label>
                                <input id="provider" name="provider" type="text" required
                                       value="{{ old('provider') }}"
                                       placeholder="google"
                                       class="w-full px-3 py-2 rounded-lg border text-sm font-mono
                                              bg-white dark:bg-gray-950 text-gray-900 dark:text-white
                                              border-gray-300 dark:border-gray-700
                                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                                <p class="mt-1 text-[11px] text-gray-500 dark:text-gray-400">
                                    {{ __('Lowercase slug, must match Socialite driver name.') }}
                                </p>
                            </div>

                            <div>
                                <label for="label" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                    {{ __('Display label') }} <span class="text-red-500">*</span>
                                </label>
                                <input id="label" name="label" type="text" required
                                       value="{{ old('label') }}"
                                       placeholder="Google"
                                       class="w-full px-3 py-2 rounded-lg border text-sm
                                              bg-white dark:bg-gray-950 text-gray-900 dark:text-white
                                              border-gray-300 dark:border-gray-700
                                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                            </div>
                        </div>

                        {{-- Client id + secret --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-5">
                            <div>
                                <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                    {{ __('Client ID') }} <span class="text-red-500">*</span>
                                </label>
                                <input id="client_id" name="client_id" type="text" required
                                       value="{{ old('client_id') }}"
                                       autocomplete="off"
                                       class="w-full px-3 py-2 rounded-lg border text-sm font-mono
                                              bg-white dark:bg-gray-950 text-gray-900 dark:text-white
                                              border-gray-300 dark:border-gray-700
                                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                            </div>

                            <div>
                                <label for="client_secret" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                    {{ __('Client Secret') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input id="client_secret" name="client_secret" type="password" required
                                           autocomplete="new-password"
                                           class="w-full px-3 py-2 pr-10 rounded-lg border text-sm font-mono
                                                  bg-white dark:bg-gray-950 text-gray-900 dark:text-white
                                                  border-gray-300 dark:border-gray-700
                                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                                    <button type="button" id="toggle-secret"
                                            class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-gray-600
                                                   dark:hover:text-gray-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12z"/>
                                        </svg>
                                    </button>
                                </div>
                                <p id="secret-hint" class="mt-1 text-[11px] text-gray-500 dark:text-gray-400 hidden">
                                    {{ __('Leave unchanged to keep the existing secret.') }}
                                </p>
                            </div>
                        </div>

                        {{-- Redirect URI --}}
                        <div class="mt-5">
                            <label for="redirect_uri" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                {{ __('Redirect URI') }} <span class="text-red-500">*</span>
                            </label>
                            <input id="redirect_uri" name="redirect_uri" type="url" required
                                   value="{{ old('redirect_uri') }}"
                                   placeholder="https://example.com/auth/google/callback"
                                   class="w-full px-3 py-2 rounded-lg border text-sm font-mono
                                          bg-white dark:bg-gray-950 text-gray-900 dark:text-white
                                          border-gray-300 dark:border-gray-700
                                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                        </div>

                        {{-- Scopes --}}
                        <div class="mt-5">
                            <label for="scopes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                {{ __('Scopes') }}
                            </label>
                            <input id="scopes" name="scopes" type="text"
                                   placeholder="openid, email, profile"
                                   class="w-full px-3 py-2 rounded-lg border text-sm
                                          bg-white dark:bg-gray-950 text-gray-900 dark:text-white
                                          border-gray-300 dark:border-gray-700
                                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                        </div>

                        {{-- Icon, color, sort --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-5">
                            <div>
                                <label for="icon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                    {{ __('Icon key') }}
                                </label>
                                <input id="icon" name="icon" type="text"
                                       placeholder="google"
                                       class="w-full px-3 py-2 rounded-lg border text-sm font-mono
                                              bg-white dark:bg-gray-950 text-gray-900 dark:text-white
                                              border-gray-300 dark:border-gray-700
                                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                            </div>

                            <div>
                                <label for="brand_color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                    {{ __('Brand color') }}
                                </label>
                                <div class="flex items-center gap-2">
                                    <input id="brand_color_picker" type="color" value="#2563eb"
                                           class="h-9 w-10 rounded border border-gray-300 dark:border-gray-700 bg-transparent cursor-pointer">
                                    <input id="brand_color" name="brand_color" type="text"
                                           placeholder="#4285F4"
                                           class="flex-1 px-3 py-2 rounded-lg border text-sm font-mono
                                                  bg-white dark:bg-gray-950 text-gray-900 dark:text-white
                                                  border-gray-300 dark:border-gray-700
                                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                                </div>
                            </div>

                            <div>
                                <label for="sort_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                    {{ __('Sort order') }}
                                </label>
                                <input id="sort_order" name="sort_order" type="number" min="0" max="9999"
                                       value="{{ old('sort_order', 0) }}"
                                       class="w-full px-3 py-2 rounded-lg border text-sm
                                              bg-white dark:bg-gray-950 text-gray-900 dark:text-white
                                              border-gray-300 dark:border-gray-700
                                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                            </div>
                        </div>

                        {{-- Toggles --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1 mt-5">
                            <label class="flex items-start gap-3 p-3 rounded-lg
                                          border border-gray-200 dark:border-gray-800
                                          hover:bg-gray-50 dark:hover:bg-gray-800/50 cursor-pointer transition">
                                <input type="hidden" name="is_enabled" value="0">
                                <input id="is_enabled" name="is_enabled" type="checkbox" value="1" checked
                                       class="mt-0.5 w-4 h-4 rounded border-gray-300 text-blue-600
                                              focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800">
                                <span>
                                    <span class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('Enabled') }}</span>
                                    <span class="block text-xs text-gray-500 dark:text-gray-400">{{ __('Show on sign-in page') }}</span>
                                </span>
                            </label>

                            <label class="flex items-start gap-3 p-3 rounded-lg
                                          border border-gray-200 dark:border-gray-800
                                          hover:bg-gray-50 dark:hover:bg-gray-800/50 cursor-pointer transition">
                                <input type="hidden" name="is_default" value="0">
                                <input id="is_default" name="is_default" type="checkbox" value="1"
                                       class="mt-0.5 w-4 h-4 rounded border-gray-300 text-blue-600
                                              focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800">
                                <span>
                                    <span class="block text-sm font-medium text-gray-900 dark:text-white">{{ __('Default') }}</span>
                                    <span class="block text-xs text-gray-500 dark:text-gray-400">{{ __('Highlight as recommended') }}</span>
                                </span>
                            </label>
                        </div>

                        <div class="mt-5 flex justify-start">
                            <button type="button" id="back-to-gallery"
                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium
                                           text-gray-600 hover:text-gray-900 hover:bg-gray-100
                                           dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                {{ __('Back to gallery') }}
                            </button>
                        </div>
                    </div>

                    {{-- ================================================= --}}
                    {{-- Footer (shared)                                     --}}
                    {{-- ================================================= --}}
                    <div class="flex items-center justify-end gap-3 pt-4
                                border-t border-gray-200 dark:border-gray-800">
                        <button type="button" data-close-modal
                                class="px-4 py-2 rounded-lg text-sm font-medium
                                       text-gray-700 hover:bg-gray-100
                                       dark:text-gray-300 dark:hover:bg-gray-800
                                       transition">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                                       text-sm font-semibold text-white
                                       bg-blue-600 hover:bg-blue-700
                                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
                                       dark:focus:ring-offset-gray-950 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                            <span id="submit-label">{{ __('Register') }}</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection


@push('dashboard_script')
<script>
(function () {
    const modal         = document.getElementById('service-modal');
    const form          = document.getElementById('service-form');
    const title         = document.getElementById('modal-title');
    const submitLabel   = document.getElementById('submit-label');
    const methodField   = document.getElementById('form-method');
    const editIdField   = document.getElementById('form-edit-id');
    const secretInput   = document.getElementById('client_secret');
    const secretHint    = document.getElementById('secret-hint');
    const previewBox    = document.getElementById('selection-preview');
    const previewText   = document.getElementById('selection-preview-text');

    const storeUrl = @json(route('social.services.store'));

    /* =========================================================
       Modal open/close with animation
       ========================================================= */
    let closeTimer = null;

    function openModal() {
        if (closeTimer) { clearTimeout(closeTimer); closeTimer = null; }
        modal.classList.remove('hidden', 'is-closing');
        void modal.offsetWidth;
        modal.classList.add('is-open');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal() {
        if (modal.classList.contains('hidden')) return;
        modal.classList.remove('is-open');
        modal.classList.add('is-closing');
        closeTimer = setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('is-closing');
            document.body.classList.remove('overflow-hidden');
            closeTimer = null;
        }, 220);
    }

    /* =========================================================
       View switching (gallery ↔ manual)
       ========================================================= */
    function switchView(view) {
        document.querySelectorAll('.view-pane').forEach(p => {
            p.classList.toggle('is-active', p.dataset.viewPane === view);
        });
        document.querySelectorAll('.view-switch-btn').forEach(b => {
            const active = b.dataset.view === view;
            b.classList.toggle('is-active', active);
            b.setAttribute('aria-selected', active ? 'true' : 'false');
        });
        // reset scroll to top of modal on switch
        const scrollLayer = modal.querySelector('.modal-scroll');
        if (scrollLayer) scrollLayer.scrollTop = 0;
    }

    document.querySelectorAll('.view-switch-btn').forEach(btn => {
        btn.addEventListener('click', () => switchView(btn.dataset.view));
    });

    document.getElementById('continue-to-manual')?.addEventListener('click', () => switchView('manual'));
    document.getElementById('back-to-gallery')?.addEventListener('click', () => switchView('gallery'));

    /* =========================================================
       Provider card selection (gallery)
       ========================================================= */
    function selectProvider(card) {
        const p = card.dataset;

        // Fill form
        document.getElementById('provider').value     = p.provider;
        document.getElementById('label').value        = p.label;
        document.getElementById('icon').value         = p.icon;
        document.getElementById('brand_color').value  = p.color;
        document.getElementById('brand_color_picker').value = p.color;
        document.getElementById('scopes').value       = p.scopes;

        // Auto-fill redirect URI only if empty
        const redirect = document.getElementById('redirect_uri');
        if (!redirect.value) {
            redirect.value = `${window.location.origin}/auth/${p.provider}/callback`;
        }

        // Visual selection state
        document.querySelectorAll('.provider-card').forEach(c => {
            c.classList.remove('is-selected');
            c.querySelector('.check-indicator')?.classList.add('opacity-0');
        });
        card.classList.add('is-selected');
        card.querySelector('.check-indicator')?.classList.remove('opacity-0');

        // Preview text
        if (previewBox && previewText) {
            previewText.textContent = p.label;
            previewBox.classList.remove('hidden');
            const dot = previewBox.querySelector('span');
            if (dot) dot.style.backgroundColor = p.color;
        }
    }

    document.querySelectorAll('.provider-card').forEach(card => {
        card.addEventListener('click', () => selectProvider(card));
        card.addEventListener('dblclick', () => {   // double-click → jump to manual
            selectProvider(card);
            switchView('manual');
        });
    });

    /* =========================================================
       Open buttons
       ========================================================= */
    document.getElementById('open-register-modal')?.addEventListener('click', () => {
        resetForm();
        openModal();
    });
    document.querySelectorAll('.open-register-modal').forEach(el =>
        el.addEventListener('click', () => { resetForm(); openModal(); })
    );

    /* =========================================================
       Close buttons
       ========================================================= */
    document.querySelectorAll('[data-close-modal]').forEach(el =>
        el.addEventListener('click', closeModal)
    );
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
    });

    /* =========================================================
       Theme toggle (adds / removes .dark on <html>)
       ========================================================= */
    document.getElementById('modal-theme-toggle')?.addEventListener('click', () => {
        const root = document.documentElement;
        const isDark = root.classList.toggle('dark');
        try { localStorage.setItem('theme', isDark ? 'dark' : 'light'); } catch (_) {}
    });

    /* =========================================================
       Color picker ↔ text sync
       ========================================================= */
    const colorPicker = document.getElementById('brand_color_picker');
    const colorText   = document.getElementById('brand_color');
    colorPicker?.addEventListener('input', () => colorText.value = colorPicker.value);
    colorText?.addEventListener('input', () => {
        if (/^#[0-9a-fA-F]{6}$/.test(colorText.value)) colorPicker.value = colorText.value;
    });

    /* =========================================================
       Secret show/hide
       ========================================================= */
    document.getElementById('toggle-secret')?.addEventListener('click', () => {
        secretInput.type = secretInput.type === 'password' ? 'text' : 'password';
    });

    /* =========================================================
       Edit → open manual view directly
       ========================================================= */
    document.querySelectorAll('.edit-service-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const data = JSON.parse(btn.dataset.payload);

            title.textContent       = @json(__('Edit social service'));
            submitLabel.textContent = @json(__('Update'));
            methodField.value       = 'PUT';
            editIdField.value       = data.id;
            form.action             = data.update_url;

            document.getElementById('provider').value      = data.provider;
            document.getElementById('label').value         = data.label;
            document.getElementById('client_id').value     = data.client_id;
            document.getElementById('client_secret').value = '__unchanged__';
            document.getElementById('redirect_uri').value  = data.redirect_uri;
            document.getElementById('scopes').value        = data.scopes || '';
            document.getElementById('icon').value          = data.icon || '';
            document.getElementById('brand_color').value   = data.brand_color || '#2563eb';
            document.getElementById('brand_color_picker').value = data.brand_color || '#2563eb';
            document.getElementById('sort_order').value    = data.sort_order;
            document.getElementById('is_enabled').checked  = !!data.is_enabled;
            document.getElementById('is_default').checked  = !!data.is_default;

            secretInput.required = false;
            secretHint.classList.remove('hidden');

            // Mark corresponding gallery card as selected (so switching back looks right)
            document.querySelectorAll('.provider-card').forEach(c => {
                const match = c.dataset.provider === data.provider;
                c.classList.toggle('is-selected', match);
                c.querySelector('.check-indicator')?.classList.toggle('opacity-0', !match);
            });

            // Update preview
            if (previewBox && previewText) {
                previewText.textContent = data.label;
                previewBox.classList.remove('hidden');
                const dot = previewBox.querySelector('span');
                if (dot) dot.style.backgroundColor = data.brand_color || '#2563eb';
            }

            // Jump to manual view for editing
            switchView('manual');
            openModal();
        });
    });

    /* =========================================================
       Reset form (called when opening "new")
       ========================================================= */
    function resetForm() {
        form.reset();
        form.action             = storeUrl;
        methodField.value       = 'POST';
        editIdField.value       = '';
        title.textContent       = @json(__('Register social service'));
        submitLabel.textContent = @json(__('Register'));
        secretInput.required    = true;
        secretHint.classList.add('hidden');

        // Clear gallery selection
        document.querySelectorAll('.provider-card').forEach(c => {
            c.classList.remove('is-selected');
            c.querySelector('.check-indicator')?.classList.add('opacity-0');
        });

        // Reset preview
        if (previewBox && previewText) {
            previewText.textContent = @json(__('No provider selected'));
            previewBox.classList.add('hidden');
            const dot = previewBox.querySelector('span');
            if (dot) dot.style.backgroundColor = '';
        }

        // Always start on gallery view for a new service
        switchView('gallery');
    }
})();
</script>
@endpush