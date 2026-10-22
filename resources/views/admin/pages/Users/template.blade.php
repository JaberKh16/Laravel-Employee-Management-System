@extends('admin.layout.main')

@push('dashboard_style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container { width: 100% !important; }
    .select2-container--default .select2-selection--multiple {
        min-height: 44px; border: 1px solid #d1d5db; border-radius: 12px;
        padding: 4px 8px; background: #fff;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #6366f1; box-shadow: 0 0 0 3px rgb(99 102 241 / 15%);
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background: #eef2ff; border: 1px solid #c7d2fe; border-radius: 7px;
        color: #4338ca; padding: 3px 7px; margin-top: 4px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #6366f1; margin-right: 5px;
    }
    .select2-dropdown {
        border: 1px solid #c7d2fe; border-radius: 10px;
        overflow: hidden; box-shadow: 0 10px 25px rgb(0 0 0 / 8%);
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background: #4f46e5;
    }
    .dark .select2-container--default .select2-selection--multiple {
        background: #111827; border-color: #4b5563; color: #f9fafb;
    }
    .dark .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        color: #f9fafb;
    }
    .dark .select2-dropdown {
        background: #1f2937; border-color: #4b5563; color: #f9fafb;
    }
    .dark .select2-container--default .select2-results__option[aria-selected="true"] {
        background: #374151;
    }

    /* Modal shell */
    .user-modal-backdrop { backdrop-filter: blur(2px); animation: umFadeIn .18s ease-out; }
    .user-modal-panel    { animation: umSlideUp .22s cubic-bezier(.2,.9,.3,1); }
    @keyframes umFadeIn  { from { opacity: 0; } to { opacity: 1; } }
    @keyframes umSlideUp {
        from { transform: translateY(14px) scale(.985); opacity: 0; }
        to   { transform: translateY(0)    scale(1);    opacity: 1; }
    }
    body.um-scroll-lock { overflow: hidden; }
</style>
@endpush

@section('dashboard_content')
    <div class="mx-auto max-w-3xl">
        <header class="mb-6 flex flex-wrap items-end justify-between gap-4 border-b border-gray-200 pb-5 dark:border-gray-700">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Users
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Create or edit users from here.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('users.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                    <span>Back to Users</span>
                </a>
                <button type="button" onclick="window.openCreateUserModal()"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all hover:-translate-y-0.5 hover:shadow-xl hover:brightness-105">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    <span>Create User</span>
                </button>
            </div>
        </header>
    </div>

    @include('admin.users.modals._create-modal')
    @include('admin.users.modals._edit-modal')
@endsection

@push('dashboard_script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@include('admin.users.partials._user-modal-script')
@endpush