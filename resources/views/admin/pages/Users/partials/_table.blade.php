    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table id="userTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
               <thead class="bg-gray-50 dark:bg-gray-900/40">
                {{-- Bulk action row — full-width, appears when rows are selected --}}
                    @include('admin.pages.Users.partials._bulk_status_bar')

                    {{-- Column header row --}}
                    <tr>
                        <th class="w-10 px-4 py-3 text-left">
                            <input type="checkbox" id="bulkSelectAll" aria-label="Select all users on this page"
                                class="h-4 w-4 cursor-pointer rounded border-gray-300 text-indigo-600 focus:ring-2 focus:ring-indigo-500/40 dark:border-gray-600 dark:bg-gray-900">
                        </th>

                        @foreach ($columns as $key => $label)
                            @php $align = $key === 'actions' ? 'text-right' : 'text-left'; @endphp
                            <th class="px-4 py-3 {{ $align }} text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                {{ $label }}
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($users as $user)
                        <tr class="group transition-colors hover:bg-indigo-50/40 dark:hover:bg-indigo-950/20"
                            data-user-row
                            data-user-id="{{ $user->id }}">

                            {{-- Bulk checkbox --}}
                            <td class="w-10 whitespace-nowrap px-4 py-3">
                                <input type="checkbox"
                                       name="user_ids[]"
                                       value="{{ $user->id }}"
                                       data-user-checkbox
                                       aria-label="Select {{ $user->username }}"
                                       class="h-4 w-4 cursor-pointer rounded border-gray-300 text-indigo-600 focus:ring-2 focus:ring-indigo-500/40 dark:border-gray-600 dark:bg-gray-900">
                            </td>

                            {{-- Index --}}
                            <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ $users->firstItem() + $loop->index }}
                            </td>

                            {{-- Username --}}
                            <td class="whitespace-nowrap px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-xs font-bold uppercase text-white">
                                        {{ strtoupper(substr($user->username ?? 'U', 0, 2)) }}
                                    </span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->username }}</span>
                                </div>
                            </td>

                            {{-- Full name --}}
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                {{ optional($user->profile)->first_name }} {{ optional($user->profile)->last_name }}
                            </td>

                            {{-- Email --}}
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                <a href="mailto:{{ $user->email }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">{{ $user->email }}</a>
                            </td>

                            {{-- Status widget --}}
                            @php
                                $currentStatus = $user->status;
                                if (! $currentStatus instanceof \App\Http\Enums\ActiveStatus) {
                                    $currentStatus = \App\Http\Enums\ActiveStatus::tryFrom((int) $currentStatus)
                                        ?? \App\Http\Enums\ActiveStatus::Active;
                                }
                                $currentValue = $currentStatus->value;
                                $currentMeta  = $statusOptions[$currentValue] ?? reset($statusOptions);
                            @endphp

                            <td class="whitespace-nowrap px-4 py-3 relative">
                                <div data-status-widget
                                     data-update-url="{{ route('users.update-status', $user->id) }}"
                                     class="relative inline-block">
                                    <button type="button" data-status-trigger aria-expanded="false" aria-haspopup="listbox"
                                            class="inline-flex items-center gap-2 rounded-lg border px-2.5 py-1.5 text-xs font-semibold transition-all duration-200 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 {{ $currentMeta['classes'] }}">
                                        <span data-status-dot class="h-1.5 w-1.5 rounded-full {{ $currentMeta['dot'] }}"></span>
                                        <span data-status-label>{{ $currentMeta['label'] }}</span>
                                        <svg data-status-chevron class="h-3.5 w-3.5 opacity-60 transition-transform duration-200"
                                             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                                        </svg>
                                    </button>

                                    <div data-status-menu role="listbox" aria-label="Change user status"
                                         class="absolute left-0 top-full z-40 mt-2 hidden w-44 overflow-hidden rounded-xl border border-gray-200 bg-white p-1 shadow-xl shadow-gray-900/10 ring-1 ring-black/5 dark:border-gray-700 dark:bg-gray-800">
                                        <div class="relative">
                                            @foreach ($statusOptions as $option)
                                                @php $isCurrent = $currentValue === $option['value']; @endphp
                                                <button type="button" role="option"
                                                        aria-selected="{{ $isCurrent ? 'true' : 'false' }}"
                                                        data-status-option="{{ $option['name'] }}"
                                                        data-status-value="{{ $option['value'] }}"
                                                        data-status-label="{{ $option['label'] }}"
                                                        data-status-classes="{{ $option['classes'] }}"
                                                        data-status-dot="{{ $option['dot'] }}"
                                                        class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-left text-xs font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-gray-700/60 {{ $isCurrent ? 'bg-indigo-50/70 dark:bg-indigo-950/40' : '' }}">
                                                    <span class="h-2 w-2 shrink-0 rounded-full {{ $option['dot'] }}"></span>
                                                    <span class="flex-1">{{ $option['label'] }}</span>
                                                    @if ($isCurrent)
                                                        <svg class="check-icon h-3.5 w-3.5 text-indigo-600 dark:text-indigo-400"
                                                             fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                                        </svg>
                                                    @endif
                                                </button>
                                            @endforeach

                                            <div data-status-loading
                                                 class="absolute inset-0 hidden items-center justify-center rounded-lg bg-white/80 backdrop-blur-[1px] dark:bg-gray-800/80">
                                                <svg class="h-5 w-5 animate-spin text-indigo-500" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                                                </svg>
                                            </div>
                                        </div>

                                        <div data-status-error role="alert"
                                             class="mx-1 mb-1 hidden rounded-lg border border-red-100 bg-red-50 px-3 py-2 text-[11px] font-medium text-red-600 dark:border-red-900 dark:bg-red-950/40 dark:text-red-400"></div>
                                    </div>
                                </div>
                            </td>

                            {{-- Roles --}}
                            @php
                                $roleColors = [
                                    'super-admin' => 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-950/50 dark:text-purple-300 dark:border-purple-800',
                                    'admin'       => 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-950/50 dark:text-indigo-300 dark:border-indigo-800',
                                    'manager'     => 'bg-sky-50 text-sky-700 border-sky-200 dark:bg-sky-950/50 dark:text-sky-300 dark:border-sky-800',
                                    'employee'    => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800',
                                    'user'        => 'bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700',
                                ];

                                $primaryRole = $user->roles->first();
                                $roleName    = $primaryRole?->name ?? 'User';
                                $roleSlug    = strtolower($roleName);
                                $roleClass   = $roleColors[$roleSlug] ?? 'bg-slate-50 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700';
                                $extraRoles  = $user->roles->skip(1);
                            @endphp

                            <td class="whitespace-nowrap px-4 py-3">
                                <div class="flex items-center gap-1.5">
                                    <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-semibold capitalize {{ $roleClass }}">
                                        <svg class="h-3 w-3 opacity-70" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        {{ $roleName }}
                                    </span>

                                    @if ($extraRoles->isNotEmpty())
                                        <span class="inline-flex items-center rounded-full border border-gray-200 bg-gray-50 px-2 py-1 text-[10px] font-bold text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                                              title="{{ $extraRoles->pluck('name')->implode(', ') }}">
                                            +{{ $extraRoles->count() }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Updated at --}}
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                <span title="{{ $user->updated_at }}">{{ $user->updated_at->diffForHumans() }}</span>
                            </td>

                            {{-- Actions --}}
                            <td class="whitespace-nowrap px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    @php $actions = $actionButtons[$user->id] ?? []; @endphp

                                    @isset($actions['view_profile'])
                                        <button type="button"
                                                title="{{ $actions['view_profile']['label'] }}"
                                                onclick="openProfile({{ $user->id }})"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg {{ $actions['view_profile']['color'] }} transition-colors hover:bg-slate-100 dark:hover:bg-slate-800/40">
                                            <i class="{{ $actions['view_profile']['icon'] }} text-base"></i>
                                        </button>
                                    @endisset

                                    @isset($actions['make_employee'])
                                        <button type="button"
                                                title="{{ $actions['make_employee']['label'] }}"
                                                onclick="makeEmployee({{ $user->id }}, '{{ addslashes($user->username) }}')"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg {{ $actions['make_employee']['color'] }} transition-colors hover:bg-emerald-100 dark:hover:bg-emerald-950/40">
                                            <i class="{{ $actions['make_employee']['icon'] }} text-base"></i>
                                        </button>
                                    @endisset

                                    @isset($actions['edit'])
                                        <a href="{{ $actions['edit']['route'] }}"
                                           title="{{ $actions['edit']['label'] }}"
                                           class="inline-flex h-8 w-8 items-center justify-center rounded-lg {{ $actions['edit']['color'] }} transition-colors hover:bg-indigo-100 dark:hover:bg-indigo-950/40">
                                            <i class="{{ $actions['edit']['icon'] }} text-base"></i>
                                        </a>
                                    @endisset

                                    @isset($actions['delete'])
                                        <button type="button"
                                                title="{{ $actions['delete']['label'] }}"
                                                onclick="deleteData({{ $user->id }})"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg {{ $actions['delete']['color'] }} transition-colors hover:bg-red-100 dark:hover:bg-red-950/40">
                                            <i class="{{ $actions['delete']['icon'] }} text-base"></i>
                                        </button>

                                        <form id="delete-form-{{ $user->id }}"
                                              action="{{ $actions['delete']['route'] }}"
                                              method="POST"
                                              class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endisset
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) + 1 }}" class="px-4 py-14 text-center">
                                <div class="mx-auto flex max-w-sm flex-col items-center gap-3">
                                    <span class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-500">
                                        <i class="ri-user-search-line text-3xl"></i>
                                    </span>
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">No users found</h3>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Try adjusting filters or create a new user.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="border-t border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-900/40">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>

    {{-- Result count --}}
    <p class="text-xs text-gray-500 dark:text-gray-400">
        Showing
        <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $users->firstItem() ?? 0 }}</span>
        to
        <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $users->lastItem() ?? 0 }}</span>
        of
        <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $users->total() }}</span>
        users
    </p>
</div>
