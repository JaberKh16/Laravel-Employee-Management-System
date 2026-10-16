<div id="makeEmployeeModal" class="fixed inset-0 z-50 hidden items-center justify-center modal-backdrop p-4">
    <div class="modal-card w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-gray-800">
        <div class="p-6">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-950/40">
                <i class="ri-briefcase-4-line text-3xl text-emerald-600 dark:text-emerald-400"></i>
            </div>
            <h3 class="mt-4 text-center text-lg font-bold text-gray-900 dark:text-white">Convert to Employee</h3>
            <p class="mt-2 text-center text-sm text-gray-500 dark:text-gray-400">
                Are you sure you want to make <strong id="makeEmployeeName" class="text-gray-900 dark:text-white"></strong> an employee?
                This will create an employee record and grant access to employee features.
            </p>
        </div>
        <form id="makeEmployeeForm" method="POST" action="">
            @csrf
            <div class="flex items-center justify-end gap-2 border-t border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/40">
                <button type="button" onclick="closeMakeEmployee()"
                        class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
                    Cancel
                </button>

                @include('components.loading.button', [
                    'id'      => 'makeEmployeeSubmit',
                    'label'   => 'Yes, make employee',
                    'loading' => 'Converting…',
                    'type'    => 'submit',
                    'variant' => 'success',
                    'icon'    => 'ri-briefcase-4-fill',
                ])
            </div>
        </form>
    </div>
</div>