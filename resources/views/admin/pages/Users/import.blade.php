@extends('admin.layout.main')

@section('dashboard_content')
<div class="mx-auto max-w-3xl">

    <header class="mb-5 flex flex-wrap items-end justify-between gap-4 border-b border-gray-200 pb-4 dark:border-gray-700">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">Import Users</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Upload a CSV or XLSX file to bulk-create or update user accounts.
            </p>
        </div>

        <a href="{{ route('users.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
            <i class="ri-arrow-left-line text-base"></i>
            <span>Back to users</span>
        </a>
    </header>

    @if (session('error'))
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    @if (session('warning'))
        <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700 dark:border-amber-900 dark:bg-amber-950/40 dark:text-amber-300">
            {{ session('warning') }}
        </div>
    @endif

    @if (session('import_summary'))
        @php $s = session('import_summary'); @endphp
        <div class="mb-5 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
            <h2 class="mb-3 text-sm font-bold text-gray-800 dark:text-gray-200">Import summary</h2>

            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-center dark:border-emerald-800 dark:bg-emerald-950/40">
                    <div class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-300">{{ $s['created'] }}</div>
                    <div class="text-xs font-medium text-emerald-700 dark:text-emerald-400">Created</div>
                </div>
                <div class="rounded-lg border border-sky-200 bg-sky-50 p-3 text-center dark:border-sky-800 dark:bg-sky-950/40">
                    <div class="text-2xl font-extrabold text-sky-600 dark:text-sky-300">{{ $s['updated'] }}</div>
                    <div class="text-xs font-medium text-sky-700 dark:text-sky-400">Updated</div>
                </div>
                <div class="rounded-lg border border-amber-200 bg-amber-50 p-3 text-center dark:border-amber-800 dark:bg-amber-950/40">
                    <div class="text-2xl font-extrabold text-amber-600 dark:text-amber-300">{{ $s['skipped'] }}</div>
                    <div class="text-xs font-medium text-amber-700 dark:text-amber-400">Skipped</div>
                </div>
                <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-center dark:border-red-800 dark:bg-red-950/40">
                    <div class="text-2xl font-extrabold text-red-600 dark:text-red-300">{{ $s['errors'] }}</div>
                    <div class="text-xs font-medium text-red-700 dark:text-red-400">Errors</div>
                </div>
            </div>

            @if (! empty($s['details']['errors']))
                <details class="mt-4">
                    <summary class="cursor-pointer text-xs font-semibold text-red-600 dark:text-red-400">
                        View error details
                    </summary>
                    <ul class="mt-2 space-y-1 text-xs text-red-700 dark:text-red-300">
                        @foreach ($s['details']['errors'] as $err)
                            <li>Row {{ $err['row'] }}: {{ $err['message'] }}</li>
                        @endforeach
                    </ul>
                </details>
            @endif
        </div>
    @endif

    <form method="POST"
          action="{{ route('users.import.store') }}"
          enctype="multipart/form-data"
          class="space-y-5 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        @csrf

        <div>
            <label class="mb-1.5 block text-sm font-semibold text-gray-700 dark:text-gray-300">
                File <span class="text-red-500">*</span>
            </label>

            <label for="file"
                   class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 px-6 py-8 text-center transition-colors hover:border-indigo-400 hover:bg-indigo-50/40 dark:border-gray-600 dark:bg-gray-900/40 dark:hover:border-indigo-500 dark:hover:bg-indigo-950/20">
                <i class="ri-upload-cloud-2-line text-3xl text-indigo-500"></i>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Click to upload</span>
                <span class="text-xs text-gray-500 dark:text-gray-400">CSV, XLSX or XLS — max 10 MB</span>
                <span id="fileName" class="mt-1 hidden rounded-full bg-indigo-100 px-2.5 py-1 text-[11px] font-semibold text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300"></span>
                <input id="file" name="file" type="file" accept=".csv,.txt,.xlsx,.xls" class="sr-only" required>
            </label>

            @error('file')
                <p class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="default_role" class="mb-1.5 block text-sm font-semibold text-gray-700 dark:text-gray-300">
                    Default role
                </label>
                <select id="default_role" name="default_role"
                        class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200">
                    <option value="">— None —</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" @selected(old('default_role') === $role)>{{ $role }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col gap-2 pt-6">
                <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <input type="hidden" name="update_existing" value="0">
                    <input type="checkbox" name="update_existing" value="1"
                           @checked(old('update_existing'))
                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-900">
                    <span>Update existing users</span>
                </label>

                <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <input type="hidden" name="skip_header" value="0">
                    <input type="checkbox" name="skip_header" value="1" checked
                           @checked(old('skip_header', true))
                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-900">
                    <span>First row is a header</span>
                </label>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-gray-200 pt-5 dark:border-gray-700">
            <a href="{{ route('users.import.template') }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
                <i class="ri-file-download-line text-base"></i>
                <span>Download CSV template</span>
            </a>

            <div class="flex items-center gap-2">
                <a href="{{ route('users.index') }}"
                   class="rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                    Cancel
                </a>

                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-indigo-500/40 active:translate-y-0">
                    <i class="ri-upload-2-line text-base"></i>
                    <span>Import</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('dashboard_scripts')
<script>
    const fileInput = document.getElementById('file');
    const fileName  = document.getElementById('fileName');

    fileInput?.addEventListener('change', () => {
        const f = fileInput.files?.[0];
        if (!f) { fileName.classList.add('hidden'); return; }
        fileName.textContent = `${f.name} · ${(f.size / 1024).toFixed(1)} KB`;
        fileName.classList.remove('hidden');
    });
</script>
@endpush