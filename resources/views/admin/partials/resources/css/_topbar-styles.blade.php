<style>
    .tb-icon-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem;
        border-radius: 0.75rem;
        color: #6b7280;
        background: transparent;
        transition: background-color 0.2s ease,
                    color 0.2s ease,
                    transform 0.2s ease,
                    box-shadow 0.2s ease;
    }
    .tb-icon-btn:hover { background-color: rgba(243, 244, 246, 0.8); color: #111827; }
    .tb-icon-btn:active { transform: scale(0.95); }
    .tb-icon-btn:focus-visible { outline: none; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.35); }
    .dark .tb-icon-btn { color: #9ca3af; }
    .dark .tb-icon-btn:hover { background-color: rgba(31, 41, 55, 0.7); color: #f9fafb; }

    .tb-search {
        display: none;
        align-items: center;
        gap: 0.625rem;
        padding: 0.5rem 0.75rem;
        min-width: 240px;
        font-size: 0.8125rem;
        border-radius: 0.75rem;
        background: rgba(249, 250, 251, 0.9);
        border: 1px solid rgba(229, 231, 235, 0.9);
        color: #6b7280;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @media (min-width: 768px) { .tb-search { display: inline-flex; } }
    .tb-search:hover {
        background: #ffffff;
        border-color: rgba(191, 219, 254, 1);
        color: #374151;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.08),
                    0 4px 12px -6px rgba(59, 130, 246, 0.15);
    }
    .tb-search:focus-visible {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
    }
    .dark .tb-search { background: rgba(31, 41, 55, 0.6); border-color: rgba(55, 65, 81, 0.8); color: #9ca3af; }
    .dark .tb-search:hover { background: rgba(31, 41, 55, 0.9); border-color: rgba(59, 130, 246, 0.4); color: #e5e7eb; }

    .tb-kbd {
        font-size: 10px;
        font-weight: 600;
        padding: 2px 6px;
        border-radius: 6px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        color: #6b7280;
        font-family: inherit;
        letter-spacing: 0.02em;
    }
    .dark .tb-kbd { background: rgba(17, 24, 39, 0.9); border-color: #374151; color: #9ca3af; }

    .tb-search-panel {
        position: absolute;
        top: calc(100% + 0.5rem);
        right: 0;
        width: 22rem;
        max-width: calc(100vw - 2rem);
        z-index: 60;
        border-radius: 1rem;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        box-shadow: 0 0 0 1px rgba(0,0,0,0.04), 0 4px 6px -1px rgba(0,0,0,0.05), 0 20px 40px -12px rgba(0,0,0,0.18);
        overflow: hidden;
        animation: tb-search-in 0.18s cubic-bezier(0.16, 1, 0.3, 1);
        transform-origin: top right;
    }
    .dark .tb-search-panel {
        background: rgba(17, 24, 39, 0.98);
        box-shadow: 0 0 0 1px rgba(255,255,255,0.06), 0 4px 6px -1px rgba(0,0,0,0.3), 0 20px 40px -12px rgba(0,0,0,0.5);
    }
    @keyframes tb-search-in {
        from { opacity: 0; transform: translateY(-6px) scale(0.96); }
        to   { opacity: 1; transform: translateY(0)    scale(1); }
    }

    .tb-search-panel-header {
        display: flex; align-items: center; gap: 0.5rem;
        padding: 0.625rem 0.75rem;
        border-bottom: 1px solid rgba(243, 244, 246, 1);
    }
    .dark .tb-search-panel-header { border-bottom-color: rgba(55, 65, 81, 0.6); }

    .tb-search-panel-input {
        flex: 1; border: 0; background: transparent;
        font-size: 0.8125rem; color: #111827; outline: none; font-family: inherit;
    }
    .dark .tb-search-panel-input { color: #f9fafb; }
    .tb-search-panel-input::placeholder { color: #9ca3af; }

    .tb-search-panel-kbd, .tb-mini-kbd {
        font-size: 10px; font-weight: 600;
        padding: 2px 6px; border-radius: 6px;
        background: #f3f4f6; border: 1px solid #e5e7eb; color: #6b7280;
        font-family: inherit;
    }
    .dark .tb-search-panel-kbd, .dark .tb-mini-kbd { background: #1f2937; border-color: #374151; color: #9ca3af; }

    .tb-search-panel-body { max-height: 20rem; overflow-y: auto; padding: 0.25rem; scrollbar-width: thin; }
    .tb-search-panel-body::-webkit-scrollbar { width: 6px; }
    .tb-search-panel-body::-webkit-scrollbar-thumb { background: rgba(156, 163, 175, 0.4); border-radius: 9999px; }

    .tb-search-group-label {
        padding: 0.5rem 0.625rem 0.25rem;
        font-size: 10px; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.08em;
        color: #9ca3af;
    }
    .dark .tb-search-group-label { color: #6b7280; }

    .tb-search-item {
        display: flex; align-items: center; gap: 0.625rem;
        padding: 0.5rem 0.625rem;
        border-radius: 0.5rem;
        font-size: 0.8125rem;
        color: #374151;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.12s ease, color 0.12s ease;
    }
    .tb-search-item:hover, .tb-search-item.is-active { background: #eff6ff; color: #1d4ed8; }
    .dark .tb-search-item { color: #d1d5db; }
    .dark .tb-search-item:hover, .dark .tb-search-item.is-active { background: rgba(59, 130, 246, 0.12); color: #93c5fd; }

    .tb-search-item-icon { display: inline-flex; color: #6b7280; flex-shrink: 0; }
    .tb-search-item.is-active .tb-search-item-icon { color: #3b82f6; }
    .tb-search-item-label { flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .tb-search-item-arrow { opacity: 0; transform: translateX(-2px); transition: all 0.15s ease; }
    .tb-search-item.is-active .tb-search-item-arrow { opacity: 1; transform: translateX(0); }

    .tb-search-panel-footer {
        display: flex; justify-content: space-between; align-items: center;
        padding: 0.5rem 0.75rem;
        border-top: 1px solid rgba(243, 244, 246, 1);
        font-size: 0.6875rem; color: #9ca3af;
    }
    .dark .tb-search-panel-footer { border-top-color: rgba(55, 65, 81, 0.6); }

    .tb-search-empty { display: flex; flex-direction: column; align-items: center; padding: 1.75rem 1rem; text-align: center; color: #9ca3af; font-size: 0.8125rem; }

    .tb-menu {
        z-index: 50;
        margin-top: 0.5rem;
        border-radius: 1rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        box-shadow: 0 0 0 1px rgba(0,0,0,0.04), 0 4px 6px -1px rgba(0,0,0,0.05), 0 20px 40px -12px rgba(0,0,0,0.12);
        overflow: hidden;
    }
    .dark .tb-menu {
        background: rgba(17, 24, 39, 0.95);
        box-shadow: 0 0 0 1px rgba(255,255,255,0.06), 0 4px 6px -1px rgba(0,0,0,0.3), 0 20px 40px -12px rgba(0,0,0,0.5);
    }
    .tb-menu.tb-menu-fixed { position: fixed !important; }
    .tb-menu:not(.hidden) {
        animation: tb-menu-in 0.18s cubic-bezier(0.16, 1, 0.3, 1);
        transform-origin: top right;
        will-change: opacity, transform;
    }
    @keyframes tb-menu-in {
        from { opacity: 0; transform: translateY(-6px) scale(0.96); }
        to   { opacity: 1; transform: translateY(0)    scale(1); }
    }

    .tb-menu-header { padding: 0.75rem 1rem; border-bottom: 1px solid rgba(243, 244, 246, 1); }
    .dark .tb-menu-header { border-bottom-color: rgba(55, 65, 81, 0.6); }
    .tb-menu-title { font-size: 0.8125rem; font-weight: 600; color: #111827; letter-spacing: -0.01em; }
    .dark .tb-menu-title { color: #ffffff; }

    .tb-menu-section {
        font-size: 10px; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.08em;
        color: #9ca3af; padding: 0 0.25rem; margin-bottom: 0.5rem;
    }
    .dark .tb-menu-section { color: #6b7280; }

    .tb-menu-list { padding: 0.375rem 0; }
    .tb-menu-item {
        display: flex; align-items: center; gap: 0.75rem;
        padding: 0.5rem 1rem;
        font-size: 0.8125rem; font-weight: 500;
        color: #374151; text-align: left;
        transition: background-color 0.15s ease, color 0.15s ease;
        cursor: pointer;
    }
    .tb-menu-item:hover { background-color: rgba(249, 250, 251, 1); color: #111827; }
    .tb-menu-item:active { background-color: rgba(243, 244, 246, 1); }
    .dark .tb-menu-item { color: #d1d5db; }
    .dark .tb-menu-item:hover { background-color: rgba(31, 41, 55, 0.6); color: #ffffff; }

    .tb-menu-icon {
        flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center;
        width: 1.75rem; height: 1.75rem; border-radius: 0.5rem;
        transition: transform 0.2s ease;
    }
    .tb-menu-item:hover .tb-menu-icon { transform: scale(1.08); }

    .tb-notification-item { display: flex; gap: 0.75rem; padding: 0.75rem 1rem; transition: background-color 0.15s ease; }
    .tb-notification-item:hover { background-color: rgba(249, 250, 251, 0.8); }
    .dark .tb-notification-item:hover { background-color: rgba(31, 41, 55, 0.5); }
    .tb-notification-icon {
        flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center;
        width: 2rem; height: 2rem; border-radius: 9999px;
    }

    .tb-scroll::-webkit-scrollbar { width: 6px; }
    .tb-scroll::-webkit-scrollbar-thumb { background: rgba(156, 163, 175, 0.4); border-radius: 9999px; }
    .tb-scroll::-webkit-scrollbar-thumb:hover { background: rgba(156, 163, 175, 0.6); }

    .lang-btn {
        flex: 1; padding: 0.375rem 0;
        font-size: 0.6875rem; font-weight: 600;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
        color: #374151; background: transparent;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .lang-btn:hover { border-color: #93c5fd; background: rgba(239, 246, 255, 0.6); }
    .lang-btn.is-active {
        border-color: #3b82f6;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #1d4ed8;
        box-shadow: 0 2px 6px -2px rgba(59, 130, 246, 0.3);
    }
    .dark .lang-btn { border-color: #374151; color: #d1d5db; }
    .dark .lang-btn:hover { border-color: rgba(59, 130, 246, 0.5); background: rgba(59, 130, 246, 0.08); }
    .dark .lang-btn.is-active {
        border-color: #3b82f6;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(99, 102, 241, 0.15) 100%);
        color: #93c5fd;
    }

    .tb-checkbox {
        width: 1rem; height: 1rem; border-radius: 0.375rem;
        border: 1.5px solid #d1d5db; color: #3b82f6; background: #ffffff;
        cursor: pointer; transition: all 0.15s ease;
    }
    .tb-checkbox:checked {
        background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
        border-color: #3b82f6;
    }
    .tb-checkbox:focus { outline: none; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25); }
    .dark .tb-checkbox { background: #374151; border-color: #4b5563; }

    #sidebar > div {
        overflow-y: auto; overflow-x: hidden;
        scrollbar-width: thin;
        scrollbar-color: rgba(156, 163, 175, 0.35) transparent;
    }
    #sidebar > div::-webkit-scrollbar { width: 6px; }
    #sidebar > div::-webkit-scrollbar-thumb { background: rgba(156, 163, 175, 0.3); border-radius: 9999px; }
    .dark #sidebar > div { scrollbar-color: rgba(75, 85, 99, 0.5) transparent; }

    #sidebar-backdrop { opacity: 0; transition: opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    #sidebar-backdrop:not(.hidden) { opacity: 1; }

    body.compact-mode nav a,
    body.compact-mode nav button { padding-top: 0.375rem !important; padding-bottom: 0.375rem !important; }
    body.compact-mode table td,
    body.compact-mode table th { padding-top: 0.375rem !important; padding-bottom: 0.375rem !important; }

    @media (prefers-reduced-motion: reduce) {
        .tb-menu, .tb-menu:not(.hidden),
        .tb-search-panel, .tb-icon-btn, .tb-search, #sidebar-backdrop {
            animation: none !important;
            transition: none !important;
        }
    }
</style>