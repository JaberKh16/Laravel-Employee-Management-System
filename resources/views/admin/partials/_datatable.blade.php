{{-- ============================================================
     DATATABLE STYLES
============================================================ --}}
@push('dashboard_style')
    @once
        <style>
            .dt-card {
                background: #ffffff;
                border-radius: 1rem;
                border: 1px solid rgba(15, 23, 42, 0.06);
                box-shadow: 0 4px 24px -8px rgba(15, 23, 42, 0.08);
                overflow: hidden;
                margin-top: 1.5rem;
                transition: box-shadow 0.25s ease;
            }
            .dt-card:hover {
                box-shadow: 0 12px 32px -8px rgba(15, 23, 42, 0.12);
            }

            .dt-card__header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                padding: 1.15rem 1.35rem;
                border-bottom: 1px solid rgba(15, 23, 42, 0.06);
                flex-wrap: wrap;
            }
            .dt-card__title-group {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                min-width: 0;
            }
            .dt-card__icon {
                width: 38px; height: 38px;
                border-radius: 0.65rem;
                background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
                color: #059669;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 0.95rem;
                flex-shrink: 0;
            }
            .dt-card__title {
                font-size: 1rem;
                font-weight: 700;
                color: #0f172a;
                margin: 0;
                line-height: 1.3;
            }
            .dt-card__subtitle {
                font-size: 0.75rem;
                color: #64748b;
                margin: 0.1rem 0 0;
            }

            .dt-card__actions {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                flex-wrap: wrap;
            }

            /* Search box */
            .dt-search {
                position: relative;
            }
            .dt-search__icon {
                position: absolute;
                top: 50%; left: 0.85rem;
                transform: translateY(-50%);
                color: #94a3b8;
                font-size: 0.8rem;
                pointer-events: none;
            }
            .dt-search input {
                appearance: none;
                border: 1px solid #e2e8f0;
                background: #f8fafc;
                border-radius: 0.65rem;
                font-size: 0.8rem;
                padding: 0.55rem 0.85rem 0.55rem 2.15rem;
                width: 220px;
                color: #0f172a;
                transition: all 0.2s ease;
            }
            .dt-search input:focus {
                outline: none;
                background: #ffffff;
                border-color: #a5b4fc;
                box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
            }
            .dt-search input::placeholder { color: #94a3b8; }

            /* Card body */
            .dt-card__body { padding: 0.25rem 1.35rem 1.35rem; }

            /* ============================================================
               DATATABLE — TABLE
            ============================================================ */
            table.dataTable {
                width: 100% !important;
                border-collapse: separate !important;
                border-spacing: 0;
                font-size: 0.85rem;
            }
            table.dataTable thead th {
                text-align: left;
                padding: 0.85rem 1rem;
                font-size: 0.7rem;
                font-weight: 700;
                letter-spacing: 0.06em;
                text-transform: uppercase;
                color: #64748b;
                background: #f8fafc;
                border-bottom: 1px solid #e2e8f0;
                white-space: nowrap;
                position: relative;
            }
            table.dataTable thead th:first-child { border-top-left-radius: 0.65rem; }
            table.dataTable thead th:last-child  { border-top-right-radius: 0.65rem; }

            table.dataTable tbody td {
                padding: 0.9rem 1rem;
                color: #334155;
                border-bottom: 1px solid #f1f5f9;
                vertical-align: middle;
            }
            table.dataTable tbody tr {
                transition: background-color 0.15s ease;
            }
            table.dataTable tbody tr:hover {
                background-color: #f8fafc;
            }
            table.dataTable tbody tr:last-child td {
                border-bottom: 0;
            }

            /* User cell */
            .dt-user {
                display: flex;
                align-items: center;
                gap: 0.65rem;
                min-width: 0;
            }
            .dt-user__avatar {
                width: 34px; height: 34px;
                border-radius: 0.6rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-weight: 700;
                font-size: 0.72rem;
                color: #ffffff;
                flex-shrink: 0;
                letter-spacing: 0.02em;
            }
            .dt-user__meta { min-width: 0; }
            .dt-user__name {
                font-weight: 600;
                color: #0f172a;
                font-size: 0.83rem;
                line-height: 1.2;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 180px;
            }
            .dt-user__email {
                font-size: 0.72rem;
                color: #94a3b8;
                line-height: 1.2;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 180px;
            }

            /* Status badges */
            .dt-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.35rem;
                padding: 0.3rem 0.7rem;
                border-radius: 999px;
                font-size: 0.72rem;
                font-weight: 600;
                line-height: 1;
                white-space: nowrap;
            }
            .dt-badge::before {
                content: '';
                width: 6px; height: 6px;
                border-radius: 50%;
                background: currentColor;
            }
            .dt-badge--active    { background: #ecfdf5; color: #059669; }
            .dt-badge--pending   { background: #fffbeb; color: #d97706; }
            .dt-badge--inactive  { background: #fef2f2; color: #dc2626; }

            /* Action buttons */
            .dt-actions {
                display: inline-flex;
                align-items: center;
                gap: 0.35rem;
            }
            .dt-action {
                width: 30px; height: 30px;
                border-radius: 0.5rem;
                border: 1px solid #e2e8f0;
                background: #ffffff;
                color: #64748b;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 0.72rem;
                cursor: pointer;
                transition: all 0.15s ease;
                text-decoration: none;
            }
            .dt-action:hover {
                background: #eef2ff;
                border-color: #c7d2fe;
                color: #4f46e5;
                transform: translateY(-1px);
            }
            .dt-action--danger:hover {
                background: #fef2f2;
                border-color: #fecaca;
                color: #dc2626;
            }

            /* Pagination */
            .dataTables_wrapper .dataTables_info {
                font-size: 0.75rem;
                color: #64748b;
                padding-top: 1rem !important;
            }
            .dataTables_wrapper .dataTables_paginate {
                padding-top: 1rem !important;
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button {
                border-radius: 0.5rem !important;
                border: 1px solid transparent !important;
                font-size: 0.78rem !important;
                font-weight: 600 !important;
                padding: 0.4rem 0.8rem !important;
                margin-left: 0.2rem !important;
                background: transparent !important;
                color: #64748b !important;
                transition: all 0.15s ease;
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
                background: #eef2ff !important;
                color: #4f46e5 !important;
                border-color: #c7d2fe !important;
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button.current,
            .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
                background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
                color: #ffffff !important;
                border-color: transparent !important;
                box-shadow: 0 4px 10px -4px rgba(99, 102, 241, 0.55) !important;
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
            .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
                color: #cbd5e1 !important;
                cursor: not-allowed !important;
                background: transparent !important;
            }

            /* Hide default DT search */
            .dataTables_wrapper .dataTables_filter { display: none; }

            /* ============================================================
               DARK MODE
            ============================================================ */
            .dark-mode .dt-card,
            .dark .dt-card {
                background: #111827;
                border-color: rgba(255, 255, 255, 0.06);
            }
            .dark-mode .dt-card__header,
            .dark .dt-card__header {
                border-bottom-color: rgba(255, 255, 255, 0.06);
            }
            .dark-mode .dt-card__title,
            .dark .dt-card__title { color: #f1f5f9; }
            .dark-mode .dt-card__subtitle,
            .dark .dt-card__subtitle { color: #94a3b8; }
            .dark-mode .dt-card__icon,
            .dark .dt-card__icon {
                background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);
                color: #6ee7b7;
            }

            .dark-mode .dt-search input,
            .dark .dt-search input {
                background: #1e293b;
                border-color: #334155;
                color: #e2e8f0;
            }
            .dark-mode .dt-search input:focus,
            .dark .dt-search input:focus {
                background: #0f172a;
                border-color: #6366f1;
                box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
            }

            .dark-mode table.dataTable thead th,
            .dark table.dataTable thead th {
                background: #0f172a;
                color: #94a3b8;
                border-bottom-color: #1e293b;
            }
            .dark-mode table.dataTable tbody td,
            .dark table.dataTable tbody td {
                color: #cbd5e1;
                border-bottom-color: #1e293b;
            }
            .dark-mode table.dataTable tbody tr:hover,
            .dark table.dataTable tbody tr:hover {
                background-color: #1e293b;
            }
            .dark-mode .dt-user__name,
            .dark .dt-user__name { color: #f1f5f9; }
            .dark-mode .dt-user__email,
            .dark .dt-user__email { color: #64748b; }

            .dark-mode .dt-action,
            .dark .dt-action {
                background: #1e293b;
                border-color: #334155;
                color: #94a3b8;
            }
            .dark-mode .dt-action:hover,
            .dark .dt-action:hover {
                background: #312e81;
                border-color: #4338ca;
                color: #c7d2fe;
            }
            .dark-mode .dt-action--danger:hover,
            .dark .dt-action--danger:hover {
                background: #7f1d1d;
                border-color: #991b1b;
                color: #fecaca;
            }

            .dark-mode .dt-badge--active,
            .dark .dt-badge--active    { background: rgba(16,185,129,0.12); color: #34d399; }
            .dark-mode .dt-badge--pending,
            .dark .dt-badge--pending   { background: rgba(245,158,11,0.12); color: #fbbf24; }
            .dark-mode .dt-badge--inactive,
            .dark .dt-badge--inactive  { background: rgba(239,68,68,0.12); color: #f87171; }

            .dark-mode .dataTables_wrapper .dataTables_info,
            .dark .dataTables_wrapper .dataTables_info { color: #94a3b8; }
            .dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button,
            .dark .dataTables_wrapper .dataTables_paginate .paginate_button {
                color: #94a3b8 !important;
            }

            @media (max-width: 767.98px) {
                .dt-card__header { padding: 1rem; }
                .dt-card__body   { padding: 0.25rem 1rem 1rem; }
                .dt-search input { width: 100%; }
                .dt-search       { width: 100%; }
                .dt-card__actions { width: 100%; }
                table.dataTable thead th,
                table.dataTable tbody td { padding: 0.7rem 0.65rem; }
            }
        </style>
    @endonce
@endpush


{{-- ============================================================
     MARKUP
============================================================ --}}
<div class="dt-card" id="recent-activity-card">
    <div class="dt-card__header">
        <div class="dt-card__title-group">
            <span class="dt-card__icon">
                <i class="fas fa-table-list" aria-hidden="true"></i>
            </span>
            <div>
                <h3 class="dt-card__title">Recent Transactions</h3>
                <p class="dt-card__subtitle">Latest activity across your organization</p>
            </div>
        </div>

        <div class="dt-card__actions">
            <div class="dt-search">
                <i class="fas fa-search dt-search__icon" aria-hidden="true"></i>
                <input type="text" id="dtCustomSearch" placeholder="Search transactions..." aria-label="Search">
            </div>
        </div>
    </div>

    <div class="dt-card__body">
        <table id="transactionsTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th style="width:110px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                {{-- Sample rows — replace with @foreach ($transactions as $tx) --}}
                <tr>
                    <td>
                        <div class="dt-user">
                            <span class="dt-user__avatar" style="background:linear-gradient(135deg,#6366f1,#4f46e5)">JD</span>
                            <div class="dt-user__meta">
                                <div class="dt-user__name">John Doe</div>
                                <div class="dt-user__email">john@example.com</div>
                            </div>
                        </div>
                    </td>
                    <td><strong>$1,250.00</strong></td>
                    <td><span class="dt-badge dt-badge--active">Active</span></td>
                    <td>Jan 15, 2025</td>
                    <td>
                        <div class="dt-actions">
                            <a href="#" class="dt-action" title="View"><i class="fas fa-eye"></i></a>
                            <a href="#" class="dt-action" title="Edit"><i class="fas fa-pen"></i></a>
                            <a href="#" class="dt-action dt-action--danger" title="Delete"><i class="fas fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="dt-user">
                            <span class="dt-user__avatar" style="background:linear-gradient(135deg,#10b981,#059669)">SA</span>
                            <div class="dt-user__meta">
                                <div class="dt-user__name">Sarah Adams</div>
                                <div class="dt-user__email">sarah@example.com</div>
                            </div>
                        </div>
                    </td>
                    <td><strong>$3,420.50</strong></td>
                    <td><span class="dt-badge dt-badge--pending">Pending</span></td>
                    <td>Jan 14, 2025</td>
                    <td>
                        <div class="dt-actions">
                            <a href="#" class="dt-action"><i class="fas fa-eye"></i></a>
                            <a href="#" class="dt-action"><i class="fas fa-pen"></i></a>
                            <a href="#" class="dt-action dt-action--danger"><i class="fas fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="dt-user">
                            <span class="dt-user__avatar" style="background:linear-gradient(135deg,#f59e0b,#d97706)">MK</span>
                            <div class="dt-user__meta">
                                <div class="dt-user__name">Michael Kim</div>
                                <div class="dt-user__email">m.kim@example.com</div>
                            </div>
                        </div>
                    </td>
                    <td><strong>$890.00</strong></td>
                    <td><span class="dt-badge dt-badge--inactive">Inactive</span></td>
                    <td>Jan 13, 2025</td>
                    <td>
                        <div class="dt-actions">
                            <a href="#" class="dt-action"><i class="fas fa-eye"></i></a>
                            <a href="#" class="dt-action"><i class="fas fa-pen"></i></a>
                            <a href="#" class="dt-action dt-action--danger"><i class="fas fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="dt-user">
                            <span class="dt-user__avatar" style="background:linear-gradient(135deg,#ec4899,#db2777)">EW</span>
                            <div class="dt-user__meta">
                                <div class="dt-user__name">Emma Wilson</div>
                                <div class="dt-user__email">emma.w@example.com</div>
                            </div>
                        </div>
                    </td>
                    <td><strong>$2,150.75</strong></td>
                    <td><span class="dt-badge dt-badge--active">Active</span></td>
                    <td>Jan 12, 2025</td>
                    <td>
                        <div class="dt-actions">
                            <a href="#" class="dt-action"><i class="fas fa-eye"></i></a>
                            <a href="#" class="dt-action"><i class="fas fa-pen"></i></a>
                            <a href="#" class="dt-action dt-action--danger"><i class="fas fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="dt-user">
                            <span class="dt-user__avatar" style="background:linear-gradient(135deg,#0ea5e9,#0284c7)">RC</span>
                            <div class="dt-user__meta">
                                <div class="dt-user__name">Robert Chen</div>
                                <div class="dt-user__email">r.chen@example.com</div>
                            </div>
                        </div>
                    </td>
                    <td><strong>$560.00</strong></td>
                    <td><span class="dt-badge dt-badge--pending">Pending</span></td>
                    <td>Jan 11, 2025</td>
                    <td>
                        <div class="dt-actions">
                            <a href="#" class="dt-action"><i class="fas fa-eye"></i></a>
                            <a href="#" class="dt-action"><i class="fas fa-pen"></i></a>
                            <a href="#" class="dt-action dt-action--danger"><i class="fas fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="dt-user">
                            <span class="dt-user__avatar" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed)">LT</span>
                            <div class="dt-user__meta">
                                <div class="dt-user__name">Lisa Taylor</div>
                                <div class="dt-user__email">lisa.t@example.com</div>
                            </div>
                        </div>
                    </td>
                    <td><strong>$4,780.25</strong></td>
                    <td><span class="dt-badge dt-badge--active">Active</span></td>
                    <td>Jan 10, 2025</td>
                    <td>
                        <div class="dt-actions">
                            <a href="#" class="dt-action"><i class="fas fa-eye"></i></a>
                            <a href="#" class="dt-action"><i class="fas fa-pen"></i></a>
                            <a href="#" class="dt-action dt-action--danger"><i class="fas fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


{{-- ============================================================
     SCRIPTS — DataTables
============================================================ --}}
@push('dashboard_script')
    {{-- jQuery (required by DataTables) --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- DataTables core --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.min.js"></script>

    <script>
        (function () {
            'use strict';

            function initDataTable() {
                if (!window.jQuery || !jQuery.fn.DataTable) {
                    // Retry once libs are loaded
                    return setTimeout(initDataTable, 100);
                }

                const table = jQuery('#transactionsTable').DataTable({
                    responsive: true,
                    pageLength: 5,
                    lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
                    order: [[3, 'desc']],
                    columnDefs: [
                        { orderable: false, targets: [0, 4] }, // avatar and actions
                        { className: 'align-middle', targets: '_all' },
                    ],
                    language: {
                        search: '',
                        searchPlaceholder: 'Search...',
                        lengthMenu: '_MENU_ per page',
                        info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                        infoEmpty: 'No entries found',
                        infoFiltered: '(filtered from _MAX_ total)',
                        zeroRecords: 'No matching records found',
                        paginate: {
                            previous: '<i class="fas fa-chevron-left"></i>',
                            next: '<i class="fas fa-chevron-right"></i>',
                        },
                    },
                    dom:
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    drawCallback: function () {
                        // Re-apply dark theme if active
                        if (document.documentElement.classList.contains('dark')
                            || document.body.classList.contains('dark-mode')) {
                            // CSS already handles it via .dark selectors
                        }
                    },
                });

                // Custom search box -> DataTable search
                const customSearch = document.getElementById('dtCustomSearch');
                if (customSearch) {
                    customSearch.addEventListener('input', function () {
                        table.search(this.value).draw();
                    });
                }

                // Expose globally
                window.transactionsTable = table;
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initDataTable);
            } else {
                initDataTable();
            }
        })();
    </script>
@endpush