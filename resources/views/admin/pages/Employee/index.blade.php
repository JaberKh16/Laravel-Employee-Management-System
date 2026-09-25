@extends('admin.layout.main')

@push('dashboard_style')
    {{-- Add any Vue-specific CSS here if needed --}}
@endpush

@section('dashboard_content')
    <div id="app">
        <employee-index />
        <router-view />
    </div>
@endsection

@push('dashboard_script')
    {{-- Load the compiled Vue bundle (from Vite or Mix) --}}
    {{-- @vite(['resources/js/app.js']) --}}

    {{-- OR if you're using Laravel Mix instead of Vite: --}}
    <script src="{{ mix('js/app.js') }}" defer></script>
@endpush