@extends('admin.layout.main')
@push('dashboard_style')
@include('admin.pages.Users.partials._page_styles')
@endpush

@section('dashboard_content')

    {{-- Page header --}}
    @include('admin.pages.Users.partials._page_header')

    {{-- Toolbar --}}
    <div class="toolbar-row mb-4">
        <button type="button"
                id="toggleFilterBtn"
                aria-expanded="false"
                aria-controls="filterPanel"
                class="toolbar-btn rounded-xl border border-gray-300 bg-white px-4 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
            <i class="ri-filter-3-line text-lg"></i>
            <span>Filter</span>
            <i id="filterChevron" class="ri-arrow-down-s-line text-base transition-transform duration-300"></i>
        </button>

        @include('admin.pages.Users.partials._search_form')
        @include('admin.pages.Users.partials._options_download')
    </div>

    {{-- Collapsible filter panel --}}
    @include('admin.pages.Users.partials._filter_panel')

    {{-- Table (bulk bar + bulk status bar are inside _table via _bulk_status_bar include) --}}
    @include('admin.pages.Users.partials._table')

    {{-- Right-side drawers --}}
    @include('admin.pages.Users.partials._bulk_status_drawer')
    @include('admin.pages.Users.partials._bulk_operations_drawer')

    {{-- Modals --}}
    @include('admin.pages.Users.modals._create-modal')
    @include('admin.pages.Users.modals._edit-modal')
    @include('admin.pages.Users.modals._profile_view_modal')
    @include('admin.pages.Users.modals._make_employee_modal')

@endsection

@include('admin.pages.Users.partials._meta_scripts')
@include('admin.pages.Users.partials._page_scripts')
@include('admin.pages.Users.partials._user-modal-script')
