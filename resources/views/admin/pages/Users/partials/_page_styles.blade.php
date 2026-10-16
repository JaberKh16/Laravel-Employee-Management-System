<style>
    /* ══════════════════════════════════════════════════════════════
       DataTables + Tailwind alignment
       ══════════════════════════════════════════════════════════════ */
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 0.75rem;
        border: 1px solid #d1d5db;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        outline: none;
    }
    .dataTables_wrapper .dataTables_filter input:focus,
    .dataTables_wrapper .dataTables_length select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 2px rgba(99,102,241,0.3);
    }
    .dark .dataTables_wrapper .dataTables_length select,
    .dark .dataTables_wrapper .dataTables_filter input {
        background-color: #1f2937;
        border-color: #4b5563;
        color: #fff;
    }

    /* ══════════════════════════════════════════════════════════════
       Collapsible filter panel
       ══════════════════════════════════════════════════════════════ */
    .filter-panel {
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        transition: max-height 0.4s ease, opacity 0.3s ease;
    }
    .filter-panel.is-open {
        max-height: 900px;
        opacity: 1;
    }

    /* ══════════════════════════════════════════════════════════════
       Radio pill
       ══════════════════════════════════════════════════════════════ */
    .criteria-radio:checked + label {
        background-color: #4f46e5;
        color: #fff;
        border-color: #4f46e5;
        box-shadow: 0 4px 10px rgba(79,70,229,0.3);
    }
    .criteria-radio + label { transition: all 0.15s ease; }
    .criteria-radio { position: absolute; opacity: 0; pointer-events: none; }

    /* ══════════════════════════════════════════════════════════════
       Toolbar — perfect vertical alignment (force all to 42px)
       ══════════════════════════════════════════════════════════════ */
    .toolbar-row {
        display: flex;
        flex-wrap: wrap;
        align-items: stretch;
        gap: 0.5rem;
    }
    .toolbar-row > * { flex-shrink: 0; }
    .toolbar-row > form { flex: 1 1 auto; min-width: 260px; }

    /* Every toolbar control is forced to the same height */
    .toolbar-btn,
    .toolbar-row .btn-loading,
    .toolbar-row button[type="submit"],
    .toolbar-row > button,
    .toolbar-row > a,
    .toolbar-row > div > button {
        height: 42px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.5rem;
        line-height: 1;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    .toolbar-input,
    .toolbar-row input[type="search"] {
        height: 42px !important;
        line-height: 1;
    }

    /* Ensure icons inside toolbar controls render at a consistent size */
    .toolbar-row i[class^="ri-"],
    .toolbar-row i[class*=" ri-"] {
        font-size: 1.125rem;
        line-height: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* ══════════════════════════════════════════════════════════════
       Modal animations
       ══════════════════════════════════════════════════════════════ */
    .modal-backdrop {
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(4px);
        animation: fadeIn 0.2s ease;
    }
    .modal-card { animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(24px) scale(0.98); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    .badge-gradient {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
    }

    

    /* ══════════════════════════════════════════════════════════════
    Notification Modern delete confirmation
    ══════════════════════════════════════════════════════════════ */
    .modern-delete-modal {
        border-radius: 22px !important;
        padding: 30px 24px !important;
        font-family: Inter, system-ui, sans-serif !important;
        box-shadow: 0 24px 70px rgba(0, 0, 0, 0.18) !important;
    }

    .modern-delete-title {
        font-size: 23px !important;
        font-weight: 750 !important;
        color: #111827 !important;
        letter-spacing: -0.5px !important;
    }

    .modern-delete-text {
        font-size: 14px !important;
        color: #6b7280 !important;
        line-height: 1.7 !important;
    }

    .modern-delete-icon {
        border-width: 3px !important;
    }

    .modern-delete-confirm,
    .modern-delete-cancel {
        border: none !important;
        border-radius: 11px !important;
        padding: 12px 20px !important;
        font-size: 14px !important;
        font-weight: 650 !important;
        cursor: pointer !important;
        margin: 5px !important;
        transition: all 0.2s ease !important;
    }

    .modern-delete-confirm {
        background: #ef4444 !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.22) !important;
    }

    .modern-delete-confirm:hover {
        background: #dc2626 !important;
        transform: translateY(-1px);
    }

    .modern-delete-cancel {
        background: #f3f4f6 !important;
        color: #374151 !important;
    }

    .modern-delete-cancel:hover {
        background: #e5e7eb !important;
    }


    /* Bulk action row slides open */
    #bulkBar {
        animation: bulkBarIn 260ms cubic-bezier(0.16, 1, 0.3, 1);
    }
    #bulkBar.hidden {
        animation: none;
    }
    @keyframes bulkBarIn {
        from { opacity: 0; transform: translateY(-4px); }
        to   { opacity: 1; transform: translateY(0); }
    }


</style>