<style>
    /* =========================================================
       SEARCH BOX
       ========================================================= */
    .sb-search2 {
        position: relative;
        display: block;
        width: 100%;
        padding: 0;
        border: 0;
        background: transparent;
        cursor: pointer;
        text-align: left;
        border-radius: 0.75rem;
        outline: none;
    }

    .sb-search2-ring {
        position: absolute;
        inset: -1px;
        border-radius: 0.85rem;
        background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 25%, #ec4899 50%, #f59e0b 75%, #3b82f6 100%);
        background-size: 300% 300%;
        opacity: 0;
        transition: opacity 0.3s ease;
        animation: sb-search2-gradient 6s ease infinite;
        z-index: 0;
    }

    .sb-search2:hover .sb-search2-ring,
    .sb-search2:focus-visible .sb-search2-ring {
        opacity: 1;
    }

    @keyframes sb-search2-gradient {
        0%   { background-position: 0% 50%; }
        50%  { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .sb-search2-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        width: 100%;
        padding: 0.5rem 0.6rem;
        border-radius: 0.75rem;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }
    .dark .sb-search2-inner { background: #111827; border-color: #1f2937; }
    .sb-search2:hover .sb-search2-inner { border-color: transparent; }
    .dark .sb-search2:hover .sb-search2-inner { background: #111827; }

    .sb-search2-icon {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 1.75rem;
        height: 1.75rem;
        border-radius: 0.5rem;
        background: linear-gradient(135deg, #eff6ff 0%, #e0e7ff 100%);
        color: #4f46e5;
        transition: transform 0.2s ease, background 0.2s ease;
    }
    .dark .sb-search2-icon {
        background: linear-gradient(135deg, #1e3a8a 0%, #312e81 100%);
        color: #a5b4fc;
    }
    .sb-search2:hover .sb-search2-icon { transform: scale(1.05); }

    .sb-search2-text { flex: 1; min-width: 0; display: flex; flex-direction: column; line-height: 1.1; }
    .sb-search2-label { font-size: 0.8125rem; font-weight: 500; color: #6b7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .dark .sb-search2-label { color: #9ca3af; }
    .sb-search2-hint { font-size: 0.6875rem; color: #9ca3af; }
    .dark .sb-search2-hint { color: #6b7280; }

    .sb-search2-kbd { flex-shrink: 0; display: inline-flex; align-items: center; gap: 1px; }
    .sb-search2-kbd kbd {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 1.25rem; height: 1.25rem; padding: 0 0.25rem;
        font-size: 0.6875rem; font-weight: 600; font-family: inherit;
        color: #6b7280; background: #f3f4f6; border: 1px solid #e5e7eb;
        border-bottom-width: 2px; border-radius: 0.375rem;
    }
    .dark .sb-search2-kbd kbd { color: #9ca3af; background: #1f2937; border-color: #374151; }

    /* =========================================================
        SIDEBAR COLLAPSED — data-attribute mirror
        (works even if something strips the class from body)
    ========================================================= */
    body[data-sidebar-collapsed="true"] #sidebar { width: 4.5rem !important; }

    body[data-sidebar-collapsed="true"] .sidebar-label,
    body[data-sidebar-collapsed="true"] .sidebar-label-wrap,
    body[data-sidebar-collapsed="true"] .sidebar-dropdown {
        display: none !important;
    }

    body[data-sidebar-collapsed="true"] .sb-search2-text,
    body[data-sidebar-collapsed="true"] .sb-search2-kbd { display: none !important; }

    body[data-sidebar-collapsed="true"] .sb-search2-inner {
        justify-content: center !important;
        padding: 0.5rem !important;
    }

    body[data-sidebar-collapsed="true"] .sb-brand {
        justify-content: center !important;
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }

    body[data-sidebar-collapsed="true"] .sb-nav-item,
    body[data-sidebar-collapsed="true"] button.sb-nav-item {
        justify-content: center !important;
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
        gap: 0 !important;
    }

    body[data-sidebar-collapsed="true"] .sb-user {
        justify-content: center !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    body[data-sidebar-collapsed="true"] .sb-collapse-btn {
        justify-content: center !important;
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
        gap: 0 !important;
    }

    body[data-sidebar-collapsed="true"] #sidebar-collapse-icon {
        transform: rotate(180deg) !important;
    }

    /* =========================================================
       THEME ACTIVE STATES
       ========================================================= */
    .theme-mode-btn.is-active,
    .theme-density-btn.is-active,
    .theme-layout-btn.is-active,
    .theme-sidebar-btn.is-active {
        border-color: #3b82f6;
        background-color: #eff6ff;
        color: #1d4ed8;
    }
    .dark .theme-mode-btn.is-active,
    .dark .theme-density-btn.is-active,
    .dark .theme-layout-btn.is-active,
    .dark .theme-sidebar-btn.is-active {
        border-color: #3b82f6;
        background-color: rgba(59, 130, 246, 0.1);
        color: #93c5fd;
    }
    .theme-color-btn.is-active {
        box-shadow: 0 0 0 2px #ffffff, 0 0 0 4px #3b82f6;
        transform: scale(1.1);
    }

    /* =========================================================
       ACCENT TOKENS
       ========================================================= */
    body[data-accent="blue"]   { --accent: #3b82f6; }
    body[data-accent="indigo"] { --accent: #6366f1; }
    body[data-accent="violet"] { --accent: #8b5cf6; }
    body[data-accent="pink"]   { --accent: #ec4899; }
    body[data-accent="rose"]   { --accent: #f43f5e; }
    body[data-accent="amber"]  { --accent: #f59e0b; }
    body[data-accent="emerald"]{ --accent: #10b981; }
    body[data-accent="teal"]   { --accent: #14b8a6; }
    body[data-accent="cyan"]   { --accent: #06b6d4; }
    body[data-accent="slate"]  { --accent: #64748b; }

    /* =========================================================
       DENSITY
       ========================================================= */
    body[data-density="compact"] nav a,
    body[data-density="compact"] nav button { padding-top: 0.375rem; padding-bottom: 0.375rem; }
    body[data-density="spacious"] nav a,
    body[data-density="spacious"] nav button { padding-top: 0.625rem; padding-bottom: 0.625rem; }

    /* =========================================================
       GRADIENT SIDEBAR STYLE
       ========================================================= */
    body[data-sidebar="gradient"] #sidebar {
        background: linear-gradient(180deg, #ffffff 0%, #f9fafb 100%);
    }
    .dark body[data-sidebar="gradient"] #sidebar,
    body[data-sidebar="gradient"].dark #sidebar {
        background: linear-gradient(180deg, #111827 0%, #0b1220 100%);
    }

    /* =========================================================
       NO-ANIM
       ========================================================= */
    .no-anim, .no-anim * { transition: none !important; animation: none !important; }

    /* =========================================================
       INLINE SEARCH PANEL
       ========================================================= */
    .sb-panel {
        position: absolute;
        top: calc(100% + 0.5rem);
        left: 0;
        right: 0;
        z-index: 50;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        box-shadow: 0 10px 30px -5px rgba(0,0,0,0.15);
        overflow: hidden;
    }
    .dark .sb-panel { background: #111827; border-color: #1f2937; }
    .sb-panel-header { display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.75rem; border-bottom: 1px solid #e5e7eb; }
    .dark .sb-panel-header { border-color: #1f2937; }
    .sb-panel-input { flex: 1; border: 0; background: transparent; font-size: 0.8125rem; color: #111827; outline: none; }
    .dark .sb-panel-input { color: #f9fafb; }
    .sb-panel-kbd, .sb-mini-kbd {
        font-size: 0.625rem; font-weight: 600; padding: 1px 5px; border-radius: 4px;
        background: #f3f4f6; border: 1px solid #e5e7eb; color: #6b7280;
    }
    .dark .sb-panel-kbd, .dark .sb-mini-kbd { background: #1f2937; border-color: #374151; color: #9ca3af; }
    .sb-panel-body { max-height: 20rem; overflow-y: auto; padding: 0.25rem; }
    .sb-group-label {
        padding: 0.375rem 0.5rem 0.125rem;
        font-size: 0.625rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.05em;
        color: #9ca3af;
    }
    .sb-item {
        display: flex; align-items: center; gap: 0.5rem;
        padding: 0.4rem 0.5rem; border-radius: 0.5rem;
        font-size: 0.8125rem; color: #374151;
        text-decoration: none; transition: background 0.12s;
    }
    .dark .sb-item { color: #d1d5db; }
    .sb-item:hover, .sb-item.is-active { background: #eff6ff; color: #1d4ed8; }
    .dark .sb-item:hover, .dark .sb-item.is-active { background: rgba(59,130,246,0.12); color: #93c5fd; }
    .sb-item-icon { display: inline-flex; color: #6b7280; flex-shrink: 0; }
    .sb-item.is-active .sb-item-icon { color: #3b82f6; }
    .sb-item-label { flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .sb-item-arrow { opacity: 0; transform: translateX(-2px); transition: all 0.15s; }
    .sb-item.is-active .sb-item-arrow { opacity: 1; transform: translateX(0); }
    .sb-panel-footer {
        display: flex; justify-content: space-between; align-items: center;
        padding: 0.4rem 0.75rem; border-top: 1px solid #e5e7eb;
        font-size: 0.6875rem; color: #9ca3af;
    }
    .dark .sb-panel-footer { border-color: #1f2937; }
    .sb-empty { display: flex; flex-direction: column; align-items: center; padding: 1.5rem 1rem; text-align: center; color: #9ca3af; font-size: 0.8125rem; }
</style>