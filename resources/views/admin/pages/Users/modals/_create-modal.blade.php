{{-- ════════════════════════════════════════════════════════════
     CREATE USER MODAL
     ════════════════════════════════════════════════════════════ --}}
<div id="createUserModal"
     class="fixed inset-0 z-[100] hidden items-center justify-center p-4"
     role="dialog" aria-modal="true" aria-labelledby="createUserTitle">
    <div class="user-modal-backdrop absolute inset-0 bg-gray-900/50 dark:bg-black/60"
         data-user-modal-close="createUserModal"></div>

    <div class="user-modal-panel relative z-10 flex max-h-[92vh] w-full max-w-2xl flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-gray-700 dark:bg-gray-800">

        <header class="flex items-start justify-between gap-4 border-b border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/40">
            <div>
                <h2 id="createUserTitle" class="text-lg font-semibold text-gray-900 dark:text-white">
                    Create User
                </h2>
                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                    Add a new user to the system
                </p>
            </div>
            <button type="button"
                    data-user-modal-close="createUserModal"
                    class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                    aria-label="Close">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </header>

        <form id="createUserForm"
              method="POST"
              action="{{ route('users.store') }}"
              class="flex min-h-0 flex-1 flex-col">
            @csrf

            <div class="min-h-0 flex-1 space-y-5 overflow-y-auto px-6 py-5">
                @include('admin.pages.Users.partials._form-fields', [
                    'mode' => 'create',
                    'user' => null,
                ])
            </div>

            <footer class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/40">
                <button type="button"
                        data-user-modal-close="createUserModal"
                        class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                    Cancel
                </button>
                <button type="submit"
                        id="createUserSubmit"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all hover:-translate-y-0.5 hover:shadow-xl hover:brightness-105">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    <span data-submit-label>Create User</span>
                </button>
            </footer>
        </form>
    </div>
</div>