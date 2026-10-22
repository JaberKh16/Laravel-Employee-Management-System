@php
    /* ────────────────────────────────────────────────────────
       Safe defaults — never throw if a var wasn't passed.
       ──────────────────────────────────────────────────────── */

    // Mode
    $mode   = $mode ?? 'create';
    $isEdit = ($mode === 'edit');

    // User (create mode = null)
    $user   = $user ?? null;

    // Roles collection (fallback: query DB)
    $roles  = $roles ?? \Spatie\Permission\Models\Role::select('id','name')
                            ->orderBy('name')->get();

    // userRole (edit only; create = empty array)
    $userRole = $userRole ?? ($isEdit && $user ? $user->roles : []);

    // Unique id per mode (keeps create & edit DOM ids apart)
    $uid = $isEdit && $user ? $user->id : null;

    /* ────────────────────────────────────────────────────────
       Normalise selected roles → array of NAMES
       ──────────────────────────────────────────────────────── */
    $rawSelected = old('roles', $userRole);
    if ($rawSelected instanceof \Illuminate\Support\Collection) {
        $rawSelected = $rawSelected->all();
    }

    $selectedRoles = collect($rawSelected)->map(function ($item) use ($roles) {
        if ($item instanceof \Spatie\Permission\Models\Role) return $item->name;
        if (is_string($item) && ! is_numeric($item))         return $item;
        return $roles->firstWhere('id', (int) $item)?->name;
    })->filter()->values()->all();

    /* ────────────────────────────────────────────────────────
       Input class builder
       ──────────────────────────────────────────────────────── */
    $inputClass = function (string $field) use ($errors) {
        return 'block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-gray-900 '
             . 'shadow-sm transition-colors placeholder-gray-400 focus:outline-none '
             . 'focus:ring-2 focus:ring-indigo-500/30 dark:bg-gray-900 dark:text-white '
             . 'dark:placeholder-gray-500 '
             . ($errors->has($field)
                 ? 'border-red-500 focus:border-red-500 focus:ring-red-500/30'
                 : 'border-gray-300 focus:border-indigo-500 dark:border-gray-600 dark:focus:border-indigo-400');
    };
@endphp

{{-- ══════════════════════════════════════════════════════════
     ROLES
     ══════════════════════════════════════════════════════════ --}}
<div>
    <label for="roleSelect_{{ $mode }}"
           class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
        Roles <span class="text-red-500">*</span>
    </label>

    <select id="roleSelect_{{ $mode }}"
            name="roles[]"
            multiple
            size="5"
            data-select2
            data-placeholder="Search and select roles…"
            class="block w-full rounded-xl border bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm
                   focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:bg-gray-900 dark:text-white
                   @error('roles') border-red-500 focus:border-red-500 focus:ring-red-500/30
                   @else border-gray-300 focus:border-indigo-500 dark:border-gray-600 dark:focus:border-indigo-400
                   @enderror">
        @foreach ($roles as $role)
            <option value="{{ $role->id }}"
                {{ in_array($role->name, $selectedRoles, true) ? 'selected' : '' }}>
                {{ $role->name }}
            </option>
        @endforeach
    </select>

    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
        Hold
        <kbd class="rounded border border-gray-300 bg-gray-100 px-1.5 py-0.5 text-[10px] font-mono dark:border-gray-600 dark:bg-gray-700">Ctrl</kbd>
        (Windows) or
        <kbd class="rounded border border-gray-300 bg-gray-100 px-1.5 py-0.5 text-[10px] font-mono dark:border-gray-600 dark:bg-gray-700">⌘</kbd>
        (Mac) to select multiple.
    </p>

    <p data-field-error="roles"
       class="mt-1.5 hidden text-xs font-medium text-red-600 dark:text-red-400"></p>
</div>

<div class="border-t border-gray-100 dark:border-gray-700"></div>

{{-- ══════════════════════════════════════════════════════════
     FIRST + LAST NAME
     ══════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <label for="first_name_{{ $mode }}"
               class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
            First Name <span class="text-red-500">*</span>
        </label>
        <input id="first_name_{{ $mode }}"
               type="text"
               name="first_name"
               value="{{ old('first_name', $user->profile->first_name ?? '') }}"
               required
               autocomplete="given-name"
               placeholder="John"
               class="{{ $inputClass('first_name') }}">
        <p data-field-error="first_name"
           class="mt-1.5 hidden text-xs font-medium text-red-600 dark:text-red-400"></p>
    </div>

    <div>
        <label for="last_name_{{ $mode }}"
               class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Last Name <span class="text-red-500">*</span>
        </label>
        <input id="last_name_{{ $mode }}"
               type="text"
               name="last_name"
               value="{{ old('last_name', $user->profile->last_name ?? '') }}"
               required
               autocomplete="family-name"
               placeholder="Doe"
               class="{{ $inputClass('last_name') }}">
        <p data-field-error="last_name"
           class="mt-1.5 hidden text-xs font-medium text-red-600 dark:text-red-400"></p>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     USERNAME
     ══════════════════════════════════════════════════════════ --}}
<div>
    <label for="username_{{ $mode }}"
           class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
        Username <span class="text-red-500">*</span>
    </label>
    <input id="username_{{ $mode }}"
           type="text"
           name="username"
           value="{{ old('username', $user->username ?? '') }}"
           required
           autocomplete="username"
           placeholder="e.g. john.doe"
           class="{{ $inputClass('username') }}">
    <p data-field-error="username"
       class="mt-1.5 hidden text-xs font-medium text-red-600 dark:text-red-400"></p>
    <div id="username-status-{{ $mode }}"
         data-username-status
         class="mt-2 text-xs font-medium"
         aria-live="polite"></div>
</div>

{{-- ══════════════════════════════════════════════════════════
     EMAIL
     ══════════════════════════════════════════════════════════ --}}
<div>
    <label for="email_{{ $mode }}"
           class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
        Email Address <span class="text-red-500">*</span>
    </label>
    <input id="email_{{ $mode }}"
           type="email"
           name="email"
           value="{{ old('email', $user->email ?? '') }}"
           required
           autocomplete="email"
           placeholder="john.doe@example.com"
           class="{{ $inputClass('email') }}">
    <p data-field-error="email"
       class="mt-1.5 hidden text-xs font-medium text-red-600 dark:text-red-400"></p>
    <div id="email-status-{{ $mode }}"
         data-email-status
         class="mt-2 text-xs font-medium"
         aria-live="polite"></div>
</div>

<div class="border-t border-gray-100 dark:border-gray-700"></div>

{{-- ══════════════════════════════════════════════════════════
     PASSWORD
     ══════════════════════════════════════════════════════════ --}}
<div>
    <label for="password_{{ $mode }}"
           class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
        Password
        @if ($isEdit)
            <span class="ml-1 text-xs font-normal text-gray-400">(leave blank to keep current)</span>
        @else
            <span class="text-red-500">*</span>
        @endif
    </label>

    <div class="relative">
        <input id="password_{{ $mode }}"
               type="password"
               name="password"
               {{ $isEdit ? '' : 'required' }}
               autocomplete="new-password"
               placeholder="••••••••"
               class="{{ $inputClass('password') }} pr-11">

        <button type="button"
                data-toggle-password="password_{{ $mode }}"
                aria-label="Toggle password visibility"
                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none dark:hover:text-gray-300">
            <svg data-eye-open class="h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <svg data-eye-closed class="hidden h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
            </svg>
        </button>
    </div>

    <p data-field-error="password"
       class="mt-1.5 hidden text-xs font-medium text-red-600 dark:text-red-400"></p>

    <div class="mt-3" aria-live="polite">
        <div class="mb-2 flex items-center justify-between">
            <span class="text-xs text-gray-500 dark:text-gray-400">Password strength</span>
            <span data-pw-strength-label class="text-xs font-semibold text-gray-500">Not entered</span>
        </div>
        <div class="grid grid-cols-5 gap-1.5">
            @for ($i = 1; $i <= 5; $i++)
                <div data-strength-bar="{{ $i }}"
                     class="h-1.5 rounded-full bg-gray-200 dark:bg-gray-700"></div>
            @endfor
        </div>
        <p data-pw-feedback class="mt-2 text-xs text-gray-500 dark:text-gray-400"></p>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     CONFIRM PASSWORD
     ══════════════════════════════════════════════════════════ --}}
<div>
    <label for="password-confirm-{{ $mode }}"
           class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
        Confirm Password
        @if (! $isEdit)
            <span class="text-red-500">*</span>
        @endif
    </label>

    <div class="relative">
        <input id="password-confirm-{{ $mode }}"
               type="password"
               name="password_confirmation"
               {{ $isEdit ? '' : 'required' }}
               autocomplete="new-password"
               placeholder="••••••••"
               class="{{ $inputClass('password_confirmation') }} pr-11">

        <button type="button"
                data-toggle-password="password-confirm-{{ $mode }}"
                aria-label="Toggle password visibility"
                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none dark:hover:text-gray-300">
            <svg data-eye-open class="h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <svg data-eye-closed class="hidden h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
            </svg>
        </button>
    </div>

    <p data-field-error="password_confirmation"
       class="mt-1.5 hidden text-xs font-medium text-red-600 dark:text-red-400"></p>
    <div data-pw-confirm-status class="mt-2 text-xs font-medium" aria-live="polite"></div>
</div>