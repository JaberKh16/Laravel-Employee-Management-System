<style>
    :root {
        --brand-50: #eef2ff;
        --brand-100: #e0e7ff;
        --brand-500: #6366f1;
        --brand-600: #4f46e5;
        --brand-700: #4338ca;
    }
    * { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, sans-serif; }

    [x-cloak] { display: none !important; }

    /* ============ PREMIUM DARK MODE — FULL SCREEN ============ */
    html, body {
        transition: background-color 0.35s ease, color 0.35s ease;
    }

    /* Light mode base */
    body {
        background-color: #f8fafc;
        color: #1e293b;
    }

    /* Dark mode base — full screen premium */
    .dark body {
        background-color: #0a0e1a !important;
        color: #e2e8f0 !important;
    }

    /* Ensure the entire viewport is dark */
    .dark {
        background-color: #0a0e1a;
        color-scheme: dark;
    }

    .dark html {
        background-color: #0a0e1a;
    }

    /* Force dark backgrounds on all sections */
    .dark nav,
    .dark main,
    .dark footer,
    .dark section,
    .dark div {
        border-color: rgba(51, 65, 85, 0.5);
    }

    /* Glassmorphism navigation */
    .glass-nav {
        backdrop-filter: saturate(180%) blur(14px);
        -webkit-backdrop-filter: saturate(180%) blur(14px);
        background-color: rgba(255, 255, 255, 0.72);
        border-bottom: 1px solid rgba(229, 231, 235, 0.6);
        transition: background-color 0.35s ease, border-color 0.35s ease;
    }
    .dark .glass-nav {
        background-color: rgba(10, 14, 26, 0.85) !important;
        border-bottom: 1px solid rgba(51, 65, 85, 0.6) !important;
    }

    /* Animated gradient background orbs */
    .bg-orb {
        position: fixed;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.35;
        pointer-events: none;
        z-index: -1;
        transition: opacity 0.5s ease, background 0.5s ease;
    }
    .bg-orb-1 {
        width: 500px; height: 500px;
        background: radial-gradient(circle, #a5b4fc, transparent 70%);
        top: -180px; right: -120px;
    }
    .bg-orb-2 {
        width: 420px; height: 420px;
        background: radial-gradient(circle, #c7d2fe, transparent 70%);
        bottom: -160px; left: -120px;
    }
    .dark .bg-orb-1 {
        background: radial-gradient(circle, #4338ca, transparent 70%);
        opacity: 0.3;
    }
    .dark .bg-orb-2 {
        background: radial-gradient(circle, #312e81, transparent 70%);
        opacity: 0.3;
    }

    /* Button shine effect */
    .btn-shine {
        position: relative;
        overflow: hidden;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-shine::after {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s ease;
    }
    .btn-shine:hover::after { left: 100%; }
    .btn-shine:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.5);
    }

    /* Icon button hover ring */
    .icon-btn {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .icon-btn:hover {
        transform: scale(1.08);
    }
    .icon-btn:active {
        transform: scale(0.95);
    }

    /* Dropdown animation */
    .dropdown-anim {
        animation: dropdownIn 0.18s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes dropdownIn {
        from { opacity: 0; transform: translateY(-6px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* Card hover */
    .card-hover { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
    .card-hover:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.15);
    }
    .dark .card-hover:hover {
        box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.6);
    }

    /* Avatar glow */
    .avatar-glow {
        box-shadow: 0 0 0 2px rgba(255,255,255,0.9), 0 0 0 4px rgba(99,102,241,0.25);
    }
    .dark .avatar-glow {
        box-shadow: 0 0 0 2px rgba(10,14,26,0.9), 0 0 0 4px rgba(99,102,241,0.5);
    }

    /* Scrollbar */
    ::-webkit-scrollbar { width: 8px; height: 8px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: rgba(148,163,184,0.5); border-radius: 8px; }
    ::-webkit-scrollbar-thumb:hover { background: rgba(100,116,139,0.7); }
    .dark ::-webkit-scrollbar-thumb { background: rgba(71,85,105,0.6); }
    .dark ::-webkit-scrollbar-thumb:hover { background: rgba(100,116,139,0.8); }

    /* ============ DARK MODE TEXT OVERRIDES ============ */
    .dark .text-gray-900 { color: #f1f5f9 !important; }
    .dark .text-gray-800 { color: #e2e8f0 !important; }
    .dark .text-gray-700 { color: #cbd5e1 !important; }
    .dark .text-gray-600 { color: #94a3b8 !important; }
    .dark .text-gray-500 { color: #64748b !important; }
    .dark .text-gray-400 { color: #475569 !important; }

    /* ============ DARK MODE BACKGROUND OVERRIDES ============ */
    .dark .bg-white { background-color: #111827 !important; }
    .dark .bg-gray-50 { background-color: #0f172a !important; }
    .dark .bg-gray-100 { background-color: #1e293b !important; }

    /* Premium dark card */
    .dark .dark-card {
        background: linear-gradient(145deg, #111827, #0f172a);
        border: 1px solid rgba(51, 65, 85, 0.5);
        box-shadow: 0 4px 20px -4px rgba(0, 0, 0, 0.5);
    }

    /* Dark mode form elements */
    .dark input,
    .dark textarea,
    .dark select {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        color: #e2e8f0 !important;
    }
    .dark input::placeholder,
    .dark textarea::placeholder {
        color: #64748b !important;
    }

    /* Dark mode table */
    .dark table {
        color: #e2e8f0;
    }
    .dark thead {
        background-color: #1e293b !important;
    }
    .dark tbody tr {
        border-color: #334155 !important;
    }
    .dark tbody tr:hover {
        background-color: #1e293b !important;
    }

    /* Dark mode borders */
    .dark .border-gray-200 { border-color: #334155 !important; }
    .dark .border-gray-100 { border-color: #1e293b !important; }
    .dark .divide-gray-100 > * + * { border-color: #1e293b !important; }
    .dark .divide-gray-200 > * + * { border-color: #334155 !important; }

    /* Dark mode ring */
    .dark .ring-black\/5 { --tw-ring-color: rgba(255, 255, 255, 0.05) !important; }

    /* Dark mode shadow */
    .dark .shadow-sm,
    .dark .shadow,
    .dark .shadow-md,
    .dark .shadow-lg,
    .dark .shadow-xl,
    .dark .shadow-2xl {
        --tw-shadow-color: rgba(0, 0, 0, 0.6) !important;
    }

    /* Ensure full-height dark coverage */
    .dark #app,
    .dark .min-h-screen {
        background-color: #0a0e1a;
    }

    /* Smooth theme transition for all elements */
    .dark *,
    .dark *::before,
    .dark *::after {
        transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
    }

    /* Prevent transition flash on load */
    .no-transition * {
        transition: none !important;
    }
</style>