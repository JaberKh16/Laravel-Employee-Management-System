{{-- Palette items passed to JS --}}
<script>
    window.SIDEBAR_PALETTE_ITEMS = [
        { label: @json(__('Dashboard')),  url: @json(route('home')),           icon: 'home',     group: @json(__('Main')) },
        { label: @json(__('Employees')),  url: @json('/employees'),            icon: 'users',    group: @json(__('Main')) },

        @if (Route::has('users.index'))
            { label: @json(__('Users')),        url: @json(route('users.index')),        icon: 'user-plus',  group: @json(__('Administration')) },
        @endif
        @if (Route::has('roles.index'))
            { label: @json(__('Roles')),        url: @json(route('roles.index')),        icon: 'shield',     group: @json(__('Administration')) },
        @endif
        @if (Route::has('permissions.index'))
            { label: @json(__('Permissions')),  url: @json(route('permissions.index')),  icon: 'key',        group: @json(__('Administration')) },
        @endif
        @if (Route::has('social.services.index'))
            { label: @json(__('Social Services')), url: @json(route('social.services.index')), icon: 'heart', group: @json(__('Administration')) },
        @endif

        @if (Route::has('countries.index'))
            { label: @json(__('Countries')),    url: @json(route('countries.index')),    icon: 'globe',      group: @json(__('System')) },
        @endif
        @if (Route::has('states.index'))
            { label: @json(__('States')),       url: @json(route('states.index')),       icon: 'map',        group: @json(__('System')) },
        @endif
        @if (Route::has('cities.index'))
            { label: @json(__('Cities')),       url: @json(route('cities.index')),       icon: 'building',   group: @json(__('System')) },
        @endif
        @if (Route::has('departments.index'))
            { label: @json(__('Departments')),  url: @json(route('departments.index')),  icon: 'briefcase',  group: @json(__('System')) },
        @endif
        @if (Route::has('branches.index'))
            { label: @json(__('Branches')),     url: @json(route('branches.index')),     icon: 'git-branch', group: @json(__('System')) },
        @endif

        { label: @json(__('Theme Options')), url: '#theme',   icon: 'palette', group: @json(__('Tools')), action: 'theme-drawer' },
        { label: @json(__('My Profile')),    url: @json(Route::has('users.profile') ? route('users.profile') : '#'), icon: 'user', group: @json(__('Tools')) },
        { label: @json(__('Settings')),      url: '#settings', icon: 'cog', group: @json(__('Tools')), action: 'settings' },
    ];

    /* =========================================================
       SIDEBAR JS — collapse + accordion + inline search + theme
       ========================================================= */
    (function () {
        'use strict';

        const ICONS = {
            home:        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10.5L12 3l9 7.5M5 9.5V21h14V9.5"/>',
            users:       '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M13 7a4 4 0 11-8 0 4 4 0 018 0zM23 20v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>',
            'user-plus': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M13 7a4 4 0 11-8 0 4 4 0 018 0zM19 8v6M22 11h-6"/>',
            shield:      '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
            key:         '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 11-7.778 7.778 5.5 5.5 0 017.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/>',
            heart:       '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>',
            globe:       '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 2a10 10 0 100 20 10 10 0 000-20zM2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>',
            map:         '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M1 6l7-3 8 3 7-3v15l-7 3-8-3-7 3V6zM8 3v15M16 6v15"/>',
            building:    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16M9 9h.01M9 13h.01M9 17h.01M15 9h.01M15 13h.01M15 17h.01"/>',
            briefcase:   '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
            'git-branch':'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 3v12M18 9a3 3 0 100-6 3 3 0 000 6zM6 21a3 3 0 100-6 3 3 0 000 6zM18 9a9 9 0 01-9 9"/>',
            palette:     '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>',
            user:        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/>',
            cog:         '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15a3 3 0 100-6 3 3 0 000 6z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 11-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 11-4 0v-.09a1.65 1.65 0 00-1.08-1.51 1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 11-2.83-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 110-4h.09a1.65 1.65 0 001.51-1.08 1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 112.83-2.83l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 114 0v.09a1.65 1.65 0 001.08 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 112.83 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 110 4h-.09a1.65 1.65 0 00-1.51 1.08z"/>',
            search:      '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>',
            'arrow-right':'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M13 6l6 6-6 6"/>',
            'file-text': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/>',
        };

        const svg = (name, cls = 'w-4 h-4') =>
            `<svg class="${cls}" fill="none" stroke="currentColor" viewBox="0 0 24 24">${ICONS[name] || ICONS['file-text']}</svg>`;

        function escapeHtml(str) {
            return String(str ?? '').replace(/[&<>"']/g, m => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;',
                '"': '&quot;', "'": '&#39;'
            }[m]));
        }
        const escapeAttr = escapeHtml;

        function boot() {
            const body    = document.body;
            const sidebar = document.getElementById('sidebar');
            if (!sidebar) return;

            const toggle      = document.getElementById('sidebar-collapse-toggle');
            const backdrop    = document.getElementById('sidebar-backdrop');
            const mainContent = document.getElementById('main-content');
            const STORAGE_KEY = 'sb.sidebar.collapsed';

            /* =========================================================
            1. SIDEBAR COLLAPSE — bulletproof
            ========================================================= */
            // console.log('[sidebar] collapse init. stored=', localStorage.getItem(STORAGE_KEY));
            if (!mainContent) return;

            const applyCollapsed = (collapsed, skipAnim = false) => {
                if (skipAnim) body.classList.add('no-anim');

                // Use BOTH class on body AND a data-attribute — belt and braces.
                // Some Tailwind preflight resets or third-party CSS may strip classes.
                body.classList.toggle('sidebar-collapsed', collapsed);
                body.setAttribute('data-sidebar-collapsed', collapsed ? 'true' : 'false');


                // ✅ Always swap the main-content margin — both directions.
                mainContent.classList.toggle('lg:ml-64', !collapsed);
                mainContent.classList.toggle('lg:ml-[6rem]', collapsed);

                if (skipAnim) {
                    void body.offsetWidth;
                    requestAnimationFrame(() => requestAnimationFrame(() =>
                        body.classList.remove('no-anim')));
                }

                // Auto-close accordion dropdowns when collapsing.
                if (collapsed) {
             
                    document.querySelectorAll('.sidebar-dropdown').forEach(ul => ul.classList.add('hidden'));
                    document.querySelectorAll('[data-collapse-toggle]').forEach(btn =>
                        btn.setAttribute('aria-expanded', 'false'));
                }

                // console.log('[sidebar] applied collapsed =', collapsed);
            };

            // Restore on load
            if (localStorage.getItem(STORAGE_KEY) === 'true') {
                applyCollapsed(true, true);
            }

            // Toggle button
            toggle?.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                const collapsed = !body.classList.contains('sidebar-collapsed');
                applyCollapsed(collapsed);
                localStorage.setItem(STORAGE_KEY, String(collapsed));
                window.dispatchEvent(new Event('resize'));
                // console.log('[sidebar] toggle clicked, collapsed =', collapsed);
            });

            // Fallback: if `toggle` is null, warn loudly
            if (!toggle) {
                // console.warn('[sidebar] #sidebar-collapse-toggle NOT FOUND. Collapse will not work.');
            }
            toggle?.addEventListener('click', () => {
                const collapsed = !body.classList.contains('sidebar-collapsed');
                applyCollapsed(collapsed);
                localStorage.setItem(STORAGE_KEY, collapsed ? 'true' : 'false');
                window.dispatchEvent(new Event('resize'));
            });

            /* =========================================================
               2. MOBILE DRAWER
               ========================================================= */
            const openDrawer = () => {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('-translate-x-0');
                backdrop?.classList.remove('hidden');
            };
            const closeDrawer = () => {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('-translate-x-0');
                backdrop?.classList.add('hidden');
            };
            const isDrawerOpen = () => sidebar.classList.contains('-translate-x-0');

            // NOTE: The sidebar script already binds the drawer toggle.
            // Do NOT re-bind here — that causes a double-toggle bug.
            // We only expose a fallback if the sidebar script failed to load.

            if (!window.SidebarDrawer) {
                document.querySelectorAll('[data-drawer-toggle="sidebar"]').forEach(el => {
                    el.addEventListener('click', () => isDrawerOpen() ? closeDrawer() : openDrawer());
                });
                backdrop?.addEventListener('click', closeDrawer);
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && isDrawerOpen()) closeDrawer();
                });
                window.addEventListener('resize', () => {
                    if (window.innerWidth >= 1024) closeDrawer();
                });
            }

            window.SidebarDrawer = { open: openDrawer, close: closeDrawer, isOpen: isDrawerOpen };

            /* =========================================================
               3. ACCORDION TOGGLES
               ========================================================= */
            document.querySelectorAll('[data-collapse-toggle]').forEach(btn => {
                const target = document.getElementById(btn.getAttribute('data-collapse-toggle'));
                if (!target) return;
                btn.setAttribute('aria-expanded', target.classList.contains('hidden') ? 'false' : 'true');
                btn.addEventListener('click', () => {
                    const opened = !target.classList.toggle('hidden');
                    btn.setAttribute('aria-expanded', opened ? 'true' : 'false');
                });
            });

            /* =========================================================
               4. INLINE SEARCH
               ========================================================= */
            (function initInlineSearch() {
                const wrap    = document.getElementById('sidebar-search-wrap');
                const trigger = document.getElementById('sidebar-search-trigger');
                const panel   = document.getElementById('sidebar-search-panel');
                const input   = document.getElementById('sidebar-search-input');
                const results = document.getElementById('sidebar-search-results');

                if (!trigger || !panel || !input || !results) return;

                const items = window.SIDEBAR_PALETTE_ITEMS || [];
                let activeIndex = -1;
                let flatList    = [];

                const render = (query = '') => {
                    const q = query.trim().toLowerCase();
                    const filtered = q
                        ? items.filter(i => i.label.toLowerCase().includes(q))
                        : items.slice();

                    flatList = filtered;
                    activeIndex = filtered.length ? 0 : -1;

                    if (!filtered.length) {
                        results.innerHTML = `
                            <div class="sb-empty">
                                ${svg('search', 'w-5 h-5')}
                                <p>No results for "<span class="font-medium">${escapeHtml(query)}</span>"</p>
                                <p class="text-[11px] opacity-70 mt-1">Try a different keyword.</p>
                            </div>`;
                        return;
                    }

                    const grouped = {};
                    filtered.forEach(item => {
                        const g = item.group || 'Other';
                        (grouped[g] = grouped[g] || []).push(item);
                    });

                    let html = '';
                    let idx  = 0;

                    Object.keys(grouped).forEach(groupName => {
                        html += `<div class="sb-group-label">${escapeHtml(groupName)}</div>`;
                        grouped[groupName].forEach(item => {
                            const isActive = idx === activeIndex ? 'is-active' : '';
                            html += `
                                <a href="${escapeAttr(item.url || '#')}"
                                   class="sb-item ${isActive}"
                                   data-index="${idx}"
                                   data-url="${escapeAttr(item.url || '#')}"
                                   data-action="${escapeAttr(item.action || '')}">
                                    <span class="sb-item-icon">${svg(item.icon || 'file-text', 'w-4 h-4')}</span>
                                    <span class="sb-item-label">${escapeHtml(item.label)}</span>
                                    <span class="sb-item-arrow">${svg('arrow-right', 'w-3.5 h-3.5')}</span>
                                </a>`;
                            idx++;
                        });
                    });

                    results.innerHTML = html;

                    results.querySelectorAll('.sb-item').forEach(el => {
                        el.addEventListener('mouseenter', () => {
                            activeIndex = parseInt(el.dataset.index, 10);
                            highlight();
                        });
                        el.addEventListener('click', e => {
                            const action = el.dataset.action;
                            const url    = el.dataset.url;
                            if (action) {
                                e.preventDefault();
                                handleAction(action);
                                closePanel();
                            } else if (!url || url === '#') {
                                e.preventDefault();
                            }
                        });
                    });

                    scrollActiveIntoView();
                };

                const highlight = () => {
                    results.querySelectorAll('.sb-item').forEach(el => {
                        el.classList.toggle('is-active',
                            parseInt(el.dataset.index, 10) === activeIndex);
                    });
                    scrollActiveIntoView();
                };

                const scrollActiveIntoView = () => {
                    const active = results.querySelector('.sb-item.is-active');
                    active?.scrollIntoView({ block: 'nearest' });
                };

                const handleAction = (action) => {
                    if (action === 'theme-drawer') {
                        document.getElementById('open-theme-drawer')?.click();
                    }
                };

                const openPanel = () => {
                    if (body.classList.contains('sidebar-collapsed')) return;
                    panel.classList.remove('hidden');
                    trigger.setAttribute('aria-expanded', 'true');
                    render(input.value);
                    requestAnimationFrame(() => input.focus());
                    document.dispatchEvent(new CustomEvent('sidebar:opened'));
                };

                const closePanel = () => {
                    panel.classList.add('hidden');
                    trigger.setAttribute('aria-expanded', 'false');
                    input.value = '';
                    activeIndex = -1;
                    document.dispatchEvent(new CustomEvent('sidebar:closed'));
                };

                const isOpen = () => !panel.classList.contains('hidden');

                trigger.addEventListener('click', e => {
                    e.stopPropagation();
                    isOpen() ? closePanel() : openPanel();
                });

                input.addEventListener('input', e => render(e.target.value));

                input.addEventListener('keydown', e => {
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        if (!flatList.length) return;
                        activeIndex = (activeIndex + 1) % flatList.length;
                        highlight();
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        if (!flatList.length) return;
                        activeIndex = (activeIndex - 1 + flatList.length) % flatList.length;
                        highlight();
                    } else if (e.key === 'Enter') {
                        e.preventDefault();
                        if (activeIndex < 0) return;
                        const item = flatList[activeIndex];
                        if (item.action) { handleAction(item.action); closePanel(); }
                        else if (item.url && item.url !== '#') window.location.href = item.url;
                    } else if (e.key === 'Escape') {
                        e.preventDefault();
                        closePanel();
                        trigger.focus();
                    } else if (e.key === 'Tab') {
                        closePanel();
                    }
                });

                document.addEventListener('click', e => {
                    if (!isOpen()) return;
                    if (!wrap.contains(e.target)) closePanel();
                });

                // Auto-close when the sidebar collapses
                const observer = new MutationObserver(() => {
                    if (body.classList.contains('sidebar-collapsed') && isOpen()) closePanel();
                });
                observer.observe(body, { attributes: true, attributeFilter: ['class'] });

                // DO NOT bind ⌘K here — the topbar owns the shortcut.
                window.SidebarSearch = { open: openPanel, close: closePanel, isOpen, render };
            })();

            /* =========================================================
               5. LEGACY MODAL PALETTE (kept for compatibility)
               ========================================================= */
            const palette    = document.getElementById('command-palette');
            const palInput   = document.getElementById('command-input');
            const palResults = document.getElementById('command-results');
            const palOpener  = document.getElementById('open-command-palette');

            if (palette && palInput && palResults && palOpener) {
                const items = window.SIDEBAR_PALETTE_ITEMS || [];

                const renderPalette = (filter = '') => {
                    const q = filter.toLowerCase();
                    const list = items.filter(i => i.label.toLowerCase().includes(q));
                    palResults.innerHTML = list.length
                        ? list.map(i => `
                            <li>
                                <a href="${escapeAttr(i.url || '#')}"
                                   class="flex items-center gap-2 px-4 py-2 text-gray-700 dark:text-gray-200
                                          hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                    ${svg(i.icon || 'file-text', 'w-4 h-4 opacity-60')}
                                    <span>${escapeHtml(i.label)}</span>
                                </a>
                            </li>`).join('')
                        : `<li class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">No matches</li>`;
                };

                const openPalette  = () => {
                    palette.classList.remove('hidden');
                    renderPalette();
                    palInput.value = '';
                    palInput.focus();
                };
                const closePalette = () => palette.classList.add('hidden');

                palOpener.addEventListener('click', openPalette);
                palInput.addEventListener('input', e => renderPalette(e.target.value));
                palette.querySelectorAll('[data-palette-close]').forEach(el =>
                    el.addEventListener('click', closePalette));

                document.addEventListener('keydown', e => {
                    if (e.key === 'Escape' && !palette.classList.contains('hidden')) closePalette();
                });
            }

            /* =========================================================
               6. THEME DRAWER
               ========================================================= */
            const drawer  = document.getElementById('theme-drawer');
            const panel   = document.getElementById('theme-panel');
            const opener2 = document.getElementById('open-theme-drawer');

            if (drawer && panel) {
                const TKEY = 'sb.theme';

                const defaults = {
                    mode: 'light', accent: 'blue', density: 'comfortable',
                    layout: 'fluid', sidebar: 'default'
                };

                const readState = () => {
                    try { return { ...defaults, ...JSON.parse(localStorage.getItem(TKEY) || '{}') }; }
                    catch (_) { return { ...defaults }; }
                };
                const writeState = (s) => {
                    try { localStorage.setItem(TKEY, JSON.stringify(s)); } catch (_) {}
                };

                const applyState = (s) => {
                    document.documentElement.classList.toggle('dark', s.mode === 'dark');

                    body.dataset.accent  = s.accent;
                    body.dataset.density = s.density;
                    body.dataset.layout  = s.layout;
                    body.dataset.sidebar = s.sidebar;

                    document.querySelectorAll('.theme-mode-btn').forEach(b =>
                        b.classList.toggle('is-active', b.dataset.themeMode === s.mode));
                    document.querySelectorAll('.theme-color-btn').forEach(b =>
                        b.classList.toggle('is-active', b.dataset.themeColor === s.accent));
                    document.querySelectorAll('.theme-density-btn').forEach(b =>
                        b.classList.toggle('is-active', b.dataset.themeDensity === s.density));
                    document.querySelectorAll('.theme-layout-btn').forEach(b =>
                        b.classList.toggle('is-active', b.dataset.themeLayout === s.layout));
                    document.querySelectorAll('.theme-sidebar-btn').forEach(b =>
                        b.classList.toggle('is-active', b.dataset.themeSidebar === s.sidebar));

                    // Broadcast so the topbar can sync its dark-mode icon.
                    document.dispatchEvent(new CustomEvent('theme:state', { detail: s }));
                };

                let state = readState();
                applyState(state);

                const openDrawer = () => {
                    drawer.classList.remove('hidden');
                    requestAnimationFrame(() => panel.classList.remove('translate-x-full'));
                    body.classList.add('overflow-hidden');
                };
                const closeDrawer = () => {
                    panel.classList.add('translate-x-full');
                    setTimeout(() => {
                        drawer.classList.add('hidden');
                        body.classList.remove('overflow-hidden');
                    }, 300);
                };

                opener2?.addEventListener('click', openDrawer);
                drawer.querySelectorAll('[data-theme-close]').forEach(el =>
                    el.addEventListener('click', closeDrawer));
                document.addEventListener('keydown', e => {
                    if (e.key === 'Escape' && !drawer.classList.contains('hidden')) closeDrawer();
                });

                document.querySelectorAll('.theme-mode-btn').forEach(b =>
                    b.addEventListener('click', () => {
                        state.mode = b.dataset.themeMode; writeState(state); applyState(state);
                    }));
                document.querySelectorAll('.theme-color-btn').forEach(b =>
                    b.addEventListener('click', () => {
                        state.accent = b.dataset.themeColor; writeState(state); applyState(state);
                    }));
                document.querySelectorAll('.theme-density-btn').forEach(b =>
                    b.addEventListener('click', () => {
                        state.density = b.dataset.themeDensity; writeState(state); applyState(state);
                    }));
                document.querySelectorAll('.theme-layout-btn').forEach(b =>
                    b.addEventListener('click', () => {
                        state.layout = b.dataset.themeLayout; writeState(state); applyState(state);
                    }));
                document.querySelectorAll('.theme-sidebar-btn').forEach(b =>
                    b.addEventListener('click', () => {
                        state.sidebar = b.dataset.themeSidebar; writeState(state); applyState(state);
                    }));

                document.getElementById('theme-reset')?.addEventListener('click', () => {
                    state = { ...defaults };
                    writeState(state);
                    applyState(state);
                });

                // React to topbar's theme toggle
                document.addEventListener('theme:changed', (e) => {
                    const nextMode = e.detail?.mode;
                    if (!nextMode || state.mode === nextMode) return;
                    state.mode = nextMode;
                    writeState(state);
                    applyState(state);
                });
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', boot);
        } else {
            boot();
        }
    })();
</script>
