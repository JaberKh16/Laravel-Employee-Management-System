{{-- resources/views/users/partials/_form.blade.php --}}
@php
    $isEdit = isset($user) && $user->exists;
    $action = $isEdit ? route('users.update', $user) : route('users.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<form action="{{ $action }}"
      method="POST"
      id="userForm"
      novalidate
      data-ignore-id="{{ $isEdit ? $user->id : '' }}">
    @csrf
    @method($method)

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- ── Username ───────────────────────────────────── --}}
        <div>
            <label for="username" class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                Username <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <input type="text"
                       name="username"
                       id="username"
                       value="{{ old('username', $user->username ?? '') }}"
                       data-validate="username"
                       data-ignore="{{ $isEdit ? $user->id : '' }}"
                       autocomplete="off"
                       placeholder="e.g. jdoe"
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800
                              focus:border-blue-500 focus:ring-blue-500 pr-10 transition-colors">
                <span class="field-icon absolute right-3 top-1/2 -translate-y-1/2 hidden"></span>
            </div>
            <p class="field-message text-xs mt-1 hidden"></p>
            @error('username')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ── Email ──────────────────────────────────────── --}}
        <div>
            <label for="email" class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                Email <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <input type="email"
                       name="email"
                       id="email"
                       value="{{ old('email', $user->email ?? '') }}"
                       data-validate="email"
                       data-ignore="{{ $isEdit ? $user->id : '' }}"
                       autocomplete="off"
                       placeholder="e.g. jdoe@example.com"
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800
                              focus:border-blue-500 focus:ring-blue-500 pr-10 transition-colors">
                <span class="field-icon absolute right-3 top-1/2 -translate-y-1/2 hidden"></span>
            </div>
            <p class="field-message text-xs mt-1 hidden"></p>
            @error('email')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ── Password ───────────────────────────────────── --}}
        <div>
            <label for="password" class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                Password @unless($isEdit)<span class="text-red-500">*</span>@endunless
            </label>
            <div class="relative">
                <input type="password"
                       name="password"
                       id="password"
                       data-validate="password"
                       autocomplete="new-password"
                       placeholder="{{ $isEdit ? 'Leave blank to keep current' : 'Min 8 chars' }}"
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800
                              focus:border-blue-500 focus:ring-blue-500 pr-10 transition-colors">
                <span class="field-icon absolute right-3 top-1/2 -translate-y-1/2 hidden"></span>
            </div>
            {{-- Strength bar --}}
            <div class="mt-2 h-1.5 w-full rounded bg-gray-200 dark:bg-gray-700 overflow-hidden">
                <div id="pwBar" class="h-full w-0 transition-all duration-300"></div>
            </div>
            <p class="field-message text-xs mt-1 hidden"></p>
            @error('password')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ── Confirm Password ───────────────────────────── --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                Confirm Password @unless($isEdit)<span class="text-red-500">*</span>@endunless
            </label>
            <div class="relative">
                <input type="password"
                       name="password_confirmation"
                       id="password_confirmation"
                       data-match="password"
                       autocomplete="new-password"
                       placeholder="Re-type password"
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800
                              focus:border-blue-500 focus:ring-blue-500 pr-10 transition-colors">
                <span class="field-icon absolute right-3 top-1/2 -translate-y-1/2 hidden"></span>
            </div>
            <p class="field-message text-xs mt-1 hidden"></p>
        </div>

        {{-- ── First Name ─────────────────────────────────── --}}
        <div>
            <label for="first_name" class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                First Name <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   name="first_name"
                   id="first_name"
                   value="{{ old('first_name', $user->profile->first_name ?? '') }}"
                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800
                          focus:border-blue-500 focus:ring-blue-500">
            @error('first_name')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ── Last Name ──────────────────────────────────── --}}
        <div>
            <label for="last_name" class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                Last Name <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   name="last_name"
                   id="last_name"
                   value="{{ old('last_name', $user->profile->last_name ?? '') }}"
                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800
                          focus:border-blue-500 focus:ring-blue-500">
            @error('last_name')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ── Middle Name ────────────────────────────────── --}}
        <div>
            <label for="middle_name" class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                Middle Name
            </label>
            <input type="text"
                   name="middle_name"
                   id="middle_name"
                   value="{{ old('middle_name', $user->profile->middle_name ?? '') }}"
                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800
                          focus:border-blue-500 focus:ring-blue-500">
        </div>

        {{-- ── Phone ──────────────────────────────────────── --}}
        <div>
            <label for="phone" class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                Phone
            </label>
            <div class="relative">
                <input type="text"
                       name="phone"
                       id="phone"
                       value="{{ old('phone', $user->profile->phone ?? '') }}"
                       data-validate="phone"
                       data-ignore_user="{{ $isEdit ? $user->id : '' }}"
                       autocomplete="off"
                       placeholder="e.g. 09171234567"
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800
                              focus:border-blue-500 focus:ring-blue-500 pr-10 transition-colors">
                <span class="field-icon absolute right-3 top-1/2 -translate-y-1/2 hidden"></span>
            </div>
            <p class="field-message text-xs mt-1 hidden"></p>
            @error('phone')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ── Gender (searchable select) ─────────────────── --}}
        <div>
            <label for="gender" class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                Gender
            </label>
            <select name="gender"
                    id="gender"
                    placeholder="Search or select gender…"
                    class="tom-select w-full">
                <option value="">— Select —</option>
                <option value="male"   @selected(old('gender', $user->profile->gender ?? '') === 'male')>
                    Male
                </option>
                <option value="female" @selected(old('gender', $user->profile->gender ?? '') === 'female')>
                    Female
                </option>
                <option value="other"  @selected(old('gender', $user->profile->gender ?? '') === 'other')>
                    Other
                </option>
            </select>
            @error('gender')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ── Birthdate ──────────────────────────────────── --}}
        <div>
            <label for="birthdate" class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                Birthdate
            </label>
            <input type="date"
                   name="birthdate"
                   id="birthdate"
                   value="{{ old('birthdate', isset($user->profile->birthdate) ? $user->profile->birthdate->format('Y-m-d') : '') }}"
                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800
                          focus:border-blue-500 focus:ring-blue-500">
            @error('birthdate')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ── Roles (multi-select, searchable) ───────────── --}}
        <div class="md:col-span-2">
            <label for="roles" class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">
                Roles
            </label>
            <select name="roles[]"
                    id="roles"
                    multiple
                    placeholder="Search roles…"
                    class="tom-select w-full">
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}"
                            data-icon="{{ $role->icon ?? '' }}"
                            @selected(in_array($role->name, old('roles', $isEdit ? $user->getRoleNames()->all() : [])))>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>
            @error('roles')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- ── Actions ────────────────────────────────────────── --}}
    <div class="mt-8 flex justify-end gap-2">
        <a href="{{ route('users.index') }}"
           class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                  text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800
                  transition-colors">
            Cancel
        </a>
        <button type="submit"
                id="submitBtn"
                class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700
                       disabled:opacity-50 transition-colors">
            {{ $isEdit ? 'Update User' : 'Create User' }}
        </button>
    </div>
</form>