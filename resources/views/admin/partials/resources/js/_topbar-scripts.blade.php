<script>
(function () {
    'use strict';

    function boot() {
        const body = document.body;

        /* =========================================================
           1. MOBILE SIDEBAR DRAWER — delegate to sidebar if present
           ========================================================= */
        const sidebar  = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');

        const openDrawer = () => {
            if (window.SidebarDrawer?.open) return window.SidebarDrawer.open();
            if (!sidebar) return;
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('-translate-x-0');
            backdrop?.classList.remove('hidden');
            body.classList.add('overflow-hidden');
        };

        const closeDrawer = () => {
            if (window.SidebarDrawer?.close) return window.SidebarDrawer.close();
            if (!sidebar) return;
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('-translate-x-0');
            backdrop?.classList.add('hidden');
            body.classList.remove('overflow-hidden');
        };

        const isDrawerOpen = () =>
            window.SidebarDrawer?.isOpen ? window.SidebarDrawer.isOpen()
            : !!sidebar?.classList.contains('-translate-x-0');

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

        /* =========================================================
           2. GENERIC DROPDOWN SYSTEM
           ========================================================= */
        const dropdowns = new Map();

        document.querySelectorAll('[data-dropdown-toggle]').forEach(btn => {
            const id   = btn.getAttribute('data-dropdown-toggle');
            const menu = document.getElementById(id);
            if (!menu) return;

            menu.classList.add('tb-menu-fixed');

            const position = () => {
                const rect      = btn.getBoundingClientRect();
                const menuRect  = menu.getBoundingClientRect();
                const placement = btn.getAttribute('data-dropdown-placement') || 'bottom-end';

                const top = rect.bottom + 10;
                let left;

                if (placement.endsWith('end'))         left = rect.right - menuRect.width;
                else if (placement.endsWith('start'))  left = rect.left;
                else left = rect.left + (rect.width / 2) - (menuRect.width / 2);

                const maxLeft = window.innerWidth - menuRect.width - 8;
                left = Math.max(8, Math.min(left, maxLeft));

                menu.style.top  = top + 'px';
                menu.style.left = left + 'px';
            };

            dropdowns.set(btn, { menu, position });

            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isOpen = !menu.classList.contains('hidden');

                dropdowns.forEach(({ menu: m, btn: b }) => {
                    if (m !== menu) { m.classList.add('hidden'); b?.setAttribute('aria-expanded', 'false'); }
                });

                if (!isOpen) {
                    menu.classList.remove('hidden');
                    btn.setAttribute('aria-expanded', 'true');
                    position();
                    menu.style.animation = 'none';
                    void menu.offsetWidth;
                    menu.style.animation = '';
                } else {
                    btn.setAttribute('aria-expanded', 'false');
                }
            });
        });

        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-dropdown-toggle]')) return;
            if (e.target.closest('[id$="-menu"]')) return;
            dropdowns.forEach(({ menu }) => menu.classList.add('hidden'));
        });

        const repositionAll = () => {
            dropdowns.forEach(({ menu, position }) => {
                if (!menu.classList.contains('hidden')) position();
            });
        };
        window.addEventListener('resize', repositionAll);
        window.addEventListener('scroll', repositionAll, true);

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') dropdowns.forEach(({ menu }) => menu.classList.add('hidden'));
        });

        /* =========================================================
           3. INLINE TOPBAR SEARCH
           ========================================================= */
        (function initTopbarSearch() {
            const wrap     = document.getElementById('topbar-search-wrap');
            const trigger  = document.getElementById('topbar-search-trigger');
            const triggerM = document.getElementById('topbar-search-trigger-mobile');
            const panel    = document.getElementById('topbar-search-panel');
            const input    = document.getElementById('topbar-search-input');
            const results  = document.getElementById('topbar-search-results');

            if (!wrap || !trigger || !panel || !input || !results) return;

            const items = window.SIDEBAR_PALETTE_ITEMS || [];
            let flatList    = [];
            let activeIndex = -1;

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
                'file-text': '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/>',
                search:      '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>',
                'arrow-right':'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M13 6l6 6-6 6"/>',
            };

            const svg = (name, cls = 'w-4 h-4') =>
                `<svg class="${cls}" fill="none" stroke="currentColor" viewBox="0 0 24 24">${ICONS[name] || ICONS['file-text']}</svg>`;

            const escapeHtml = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
            }[c]));

            const render = (query = '') => {
                const q = query.trim().toLowerCase();
                const filtered = q ? items.filter(i => i.label.toLowerCase().includes(q)) : items.slice();

                flatList    = filtered;
                activeIndex = filtered.length ? 0 : -1;

                if (!filtered.length) {
                    results.innerHTML = `
                        <div class="tb-search-empty">
                            ${svg('search', 'w-5 h-5 opacity-50')}
                            <p class="mt-2">No results for "<span class="font-medium">${escapeHtml(query)}</span>"</p>
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
                    html += `<div class="tb-search-group-label">${escapeHtml(groupName)}</div>`;
                    grouped[groupName].forEach(item => {
                        const isActive = idx === activeIndex ? 'is-active' : '';
                        html += `
                            <a href="${escapeHtml(item.url || '#')}"
                               class="tb-search-item ${isActive}"
                               data-index="${idx}"
                               data-url="${escapeHtml(item.url || '#')}"
                               data-action="${escapeHtml(item.action || '')}">
                                <span class="tb-search-item-icon">${svg(item.icon || 'file-text', 'w-4 h-4')}</span>
                                <span class="tb-search-item-label">${escapeHtml(item.label)}</span>
                                <span class="tb-search-item-arrow">${svg('arrow-right', 'w-3.5 h-3.5')}</span>
                            </a>`;
                        idx++;
                    });
                });

                results.innerHTML = html;

                results.querySelectorAll('.tb-search-item').forEach(el => {
                    el.addEventListener('mouseenter', () => {
                        activeIndex = parseInt(el.dataset.index, 10);
                        highlight();
                    });
                    el.addEventListener('click', (e) => {
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
                results.querySelectorAll('.tb-search-item').forEach(el => {
                    el.classList.toggle('is-active', parseInt(el.dataset.index, 10) === activeIndex);
                });
                scrollActiveIntoView();
            };

            const scrollActiveIntoView = () => {
                results.querySelector('.tb-search-item.is-active')?.scrollIntoView({ block: 'nearest' });
            };

            const handleAction = (action) => {
                if (action === 'theme-drawer') {
                    const t = document.getElementById('open-theme-drawer')
                        || document.getElementById('open-theme-drawer-topbar');
                    t?.click();
                }
            };

            const openPanel = () => {
                panel.classList.remove('hidden');
                trigger.setAttribute('aria-expanded', 'true');
                triggerM?.setAttribute('aria-expanded', 'true');
                render(input.value);
                requestAnimationFrame(() => input.focus());
            };

            const closePanel = () => {
                panel.classList.add('hidden');
                trigger.setAttribute('aria-expanded', 'false');
                triggerM?.setAttribute('aria-expanded', 'false');
                input.value = '';
                activeIndex = -1;
            };

            const isOpen = () => !panel.classList.contains('hidden');

            trigger.addEventListener('click', (e) => { e.stopPropagation(); isOpen() ? closePanel() : openPanel(); });
            triggerM?.addEventListener('click', (e) => { e.stopPropagation(); isOpen() ? closePanel() : openPanel(); });

            input.addEventListener('input', (e) => render(e.target.value));

            input.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowDown') {
                    e.preventDefault(); if (!flatList.length) return;
                    activeIndex = (activeIndex + 1) % flatList.length; highlight();
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault(); if (!flatList.length) return;
                    activeIndex = (activeIndex - 1 + flatList.length) % flatList.length; highlight();
                } else if (e.key === 'Enter') {
                    e.preventDefault(); if (activeIndex < 0) return;
                    const item = flatList[activeIndex];
                    if (item.action) { handleAction(item.action); closePanel(); }
                    else if (item.url && item.url !== '#') window.location.href = item.url;
                } else if (e.key === 'Escape') {
                    e.preventDefault(); closePanel(); trigger.focus();
                }
            });

            document.addEventListener('click', (e) => {
                if (!isOpen()) return;
                if (!wrap.contains(e.target)) closePanel();
            });

            window.TopbarSearch = { open: openPanel, close: closePanel, isOpen, render };
        })();

        /* =========================================================
           4. THEME DRAWER (delegate to sidebar)
           ========================================================= */
        document.getElementById('open-theme-drawer-topbar')?.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();

            dropdowns.forEach(({ menu }) => menu.classList.add('hidden'));

            const sidebarTrigger = document.getElementById('open-theme-drawer');
            if (sidebarTrigger) { sidebarTrigger.click(); return; }

            const drawer = document.getElementById('theme-drawer');
            const panel  = document.getElementById('theme-panel');
            if (drawer && panel) {
                drawer.classList.remove('hidden');
                requestAnimationFrame(() => panel.classList.remove('translate-x-full'));
                body.classList.add('overflow-hidden');
            }
        });

        /* =========================================================
           5. THEME TOGGLE (dark mode) — syncs with sidebar drawer
           ========================================================= */
        (function initThemeToggle() {
            const toggle    = document.getElementById('theme-toggle');
            const darkIcon  = document.getElementById('theme-toggle-dark-icon');
            const lightIcon = document.getElementById('theme-toggle-light-icon');
            if (!toggle) return;

            const TKEY = 'sb.theme';
            const root = document.documentElement;

            const readState = () => {
                try { return JSON.parse(localStorage.getItem(TKEY) || '{}'); }
                catch (_) { return {}; }
            };
            const writeState = (s) => {
                try { localStorage.setItem(TKEY, JSON.stringify(s)); } catch (_) {}
            };

            const isDark = () => root.classList.contains('dark');

            const syncIcons = () => {
                const dark = isDark();
                darkIcon?.classList.toggle('hidden', !dark);
                lightIcon?.classList.toggle('hidden', dark);
            };

            syncIcons();

            toggle.addEventListener('click', () => {
                const nextDark = !isDark();
                root.classList.remove('no-theme-transition');
                root.classList.toggle('dark', nextDark);

                const state = readState();
                state.mode = nextDark ? 'dark' : 'light';
                writeState(state);

                syncIcons();

                // Notify the sidebar theme drawer + inline search
                document.dispatchEvent(new CustomEvent('theme:changed', {
                    detail: { mode: state.mode }
                }));
            });

            // React to sidebar drawer's theme mode changes
            document.addEventListener('theme:state', (e) => {
                const nextDark = e.detail?.mode === 'dark';
                if (nextDark !== isDark()) {
                    root.classList.toggle('dark', nextDark);
                    syncIcons();
                }
            });
        })();

        /* =========================================================
           6. COMPACT MODE
           ========================================================= */
        const COMPACT_KEY = 'sb.compact';
        const compactBtn  = document.getElementById('compact-mode-toggle');
        const compactInd  = document.getElementById('compact-mode-indicator');

        const applyCompact = (on) => {
            body.classList.toggle('compact-mode', on);
            body.dataset.density = on ? 'compact' : 'comfortable';
            if (compactInd) {
                compactInd.textContent = on ? 'On' : 'Off';
                compactInd.classList.toggle('bg-blue-50', on);
                compactInd.classList.toggle('text-blue-700', on);
                compactInd.classList.toggle('dark:bg-blue-500/10', on);
                compactInd.classList.toggle('dark:text-blue-400', on);
            }
        };

        let compactOn = localStorage.getItem(COMPACT_KEY) === 'true';
        applyCompact(compactOn);

        compactBtn?.addEventListener('click', () => {
            compactOn = !compactOn;
            localStorage.setItem(COMPACT_KEY, String(compactOn));
            applyCompact(compactOn);
        });

        /* =========================================================
           7. LANGUAGE SELECTOR
           ========================================================= */
        document.querySelectorAll('.lang-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const lang = btn.dataset.lang;
                document.querySelectorAll('.lang-btn').forEach(b =>
                    b.classList.toggle('is-active', b === btn));
                localStorage.setItem('sb.lang', lang);
                document.documentElement.setAttribute('lang', lang);
                document.documentElement.setAttribute('dir', lang === 'ar' ? 'rtl' : 'ltr');
            });
        });

        const savedLang = localStorage.getItem('sb.lang');
        if (savedLang) {
            document.querySelector(`.lang-btn[data-lang="${savedLang}"]`)?.classList.add('is-active');
            document.documentElement.setAttribute('lang', savedLang);
            document.documentElement.setAttribute('dir', savedLang === 'ar' ? 'rtl' : 'ltr');
        }

        /* =========================================================
           8. PREFERENCES
           ========================================================= */
        const bindPref = (id, key, defaultVal = true) => {
            const el = document.getElementById(id);
            if (!el) return;
            const stored = localStorage.getItem(key);
            el.checked = stored === null ? defaultVal : stored === 'true';
            el.addEventListener('change', () => {
                localStorage.setItem(key, String(el.checked));
                body.dispatchEvent(new CustomEvent('pref:change', {
                    detail: { key, value: el.checked }
                }));
            });
        };

        bindPref('pref-notifications', 'sb.pref.notifications', true);
        bindPref('pref-sounds',        'sb.pref.sounds',        false);

        /* =========================================================
           9. NOTIFICATIONS
           ========================================================= */
        (function initNotifications() {
            const cfg = window.NOTIFICATION_ENDPOINTS;
            if (!cfg) return;

            const list       = document.getElementById('notifications-list');
            const badge      = document.getElementById('notifications-badge');
            const badgeCount = document.getElementById('notifications-badge-count');
            const menuCount  = document.getElementById('notifications-menu-count');
            const markAllBtn = document.getElementById('notifications-mark-all');
            const trigger    = document.getElementById('notifications-button');
            if (!list || !trigger) return;

            let hasLoaded = false;
            let isLoading = false;

            const iconMap = {
                success: { bg: 'bg-emerald-50 dark:bg-emerald-500/10', text: 'text-emerald-600 dark:text-emerald-400',
                           outline: true,  svg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>' },
                warning: { bg: 'bg-amber-50 dark:bg-amber-500/10',     text: 'text-amber-600 dark:text-amber-400',
                           outline: true,  svg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5 19h14a2 2 0 001.84-2.75L13.74 4a2 2 0 00-3.48 0L3.16 16.25A2 2 0 005 19z"/>' },
                error:   { bg: 'bg-red-50 dark:bg-red-500/10',         text: 'text-red-600 dark:text-red-400',
                           outline: true,  svg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>' },
                info:    { bg: 'bg-blue-50 dark:bg-blue-500/10',       text: 'text-blue-600 dark:text-blue-400',
                           outline: false, svg: '<path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" fill-rule="evenodd" clip-rule="evenodd"/>' },
                action:  { bg: 'bg-violet-50 dark:bg-violet-500/10',   text: 'text-violet-600 dark:text-violet-400',
                           outline: true,  svg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>' },
                general: { bg: 'bg-gray-50 dark:bg-gray-700/50',       text: 'text-gray-600 dark:text-gray-400',
                           outline: false, svg: '<path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6z"/>' },
            };

            const escapeHtml = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
            }[c]));

            const render = (items) => {
                if (!items.length) {
                    list.innerHTML = `
                        <li class="px-4 py-10 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full
                                        bg-gray-100 dark:bg-gray-700/50 mb-3 mx-auto">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('All caught up') }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ __('No new notifications') }}</p>
                        </li>`;
                    return;
                }

                list.innerHTML = items.map(n => {
                    const c = iconMap[n.category] || iconMap.general;
                    const icon = c.outline
                        ? `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">${c.svg}</svg>`
                        : `<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">${c.svg}</svg>`;

                    const groupBadge = (n.group_count > 1)
                        ? `<span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-full
                                        bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300
                                        ml-1.5 align-middle">×${n.group_count}</span>`
                        : '';

                    const unreadClass = !n.is_read ? 'bg-blue-50/40 dark:bg-blue-500/5' : '';

                    const unreadDot = !n.is_read
                        ? `<span class="absolute left-2 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-blue-500"></span>`
                        : '';

                    return `
                    <li>
                        <div class="tb-notification-item group relative ${unreadClass}"
                             data-notification-id="${n.id}"
                             data-action-url="${escapeHtml(n.action_url || '')}"
                             data-is-read="${n.is_read ? '1' : '0'}">
                            ${unreadDot}
                            <span class="tb-notification-icon ${c.bg} ${c.text}">
                                ${icon}
                            </span>
                            <div class="flex-1 min-w-0 cursor-pointer" data-open>
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">
                                    ${escapeHtml(n.title)}${groupBadge}
                                </p>
                                ${n.message ? `<p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate">${escapeHtml(n.message)}</p>` : ''}
                                <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1">
                                    ${escapeHtml(n.time_ago || '')}
                                </p>
                            </div>
                            <button type="button"
                                    data-dismiss
                                    aria-label="{{ __('Dismiss') }}"
                                    class="flex-shrink-0 self-start p-1 rounded text-gray-300
                                           hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10
                                           opacity-0 group-hover:opacity-100
                                           transition-all duration-150">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </li>`;
                }).join('');
            };

            const updateCounts = (unreadCount) => {
                if (badge && badgeCount) {
                    if (unreadCount > 0) {
                        badgeCount.textContent = unreadCount > 99 ? '99+' : unreadCount;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }

                if (menuCount) {
                    if (unreadCount > 0) {
                        menuCount.textContent = `${unreadCount} {{ __('new') }}`;
                        menuCount.classList.remove('hidden');
                    } else {
                        menuCount.classList.add('hidden');
                    }
                }

                if (markAllBtn) {
                    markAllBtn.classList.toggle('opacity-0', unreadCount === 0);
                    markAllBtn.classList.toggle('pointer-events-none', unreadCount === 0);
                }

                // Notify page-level listeners (e.g. status cards)
                document.dispatchEvent(new CustomEvent('notifications:count', { detail: { count: unreadCount } }));
            };

            const fetchList = async () => {
                if (isLoading) return;
                isLoading = true;
                try {
                    const res = await fetch(`${cfg.index}?per_page=10`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': cfg.csrf,
                        },
                        credentials: 'same-origin',
                    });
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    const json = await res.json();
                    render(json.data || []);
                    updateCounts(json.meta?.unread_count ?? 0);
                    hasLoaded = true;
                } catch (e) {
                    console.warn('[notifications] fetch failed', e);
                    list.innerHTML = `
                        <li class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Could not load notifications.') }}
                        </li>`;
                } finally {
                    isLoading = false;
                }
            };

            const fetchCount = async () => {
                try {
                    const res = await fetch(cfg.unread, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        credentials: 'same-origin',
                    });
                    if (!res.ok) return;
                    const json = await res.json();
                    updateCounts(json.count ?? 0);
                } catch (_) {}
            };

            const markRead = async (id) => {
                try {
                    await fetch(cfg.markOne.replace('__ID__', id), {
                        method: 'PATCH',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': cfg.csrf,
                        },
                        credentials: 'same-origin',
                    });
                } catch (_) {}
            };

            const markAllRead = async () => {
                try {
                    await fetch(cfg.markAll, {
                        method: 'PATCH',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': cfg.csrf,
                        },
                        credentials: 'same-origin',
                    });
                    await fetchList();
                } catch (_) {}
            };

            const dismiss = async (id) => {
                try {
                    await fetch(cfg.dismissOne.replace('__ID__', id), {
                        method: 'PATCH',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': cfg.csrf,
                        },
                        credentials: 'same-origin',
                    });
                } catch (_) {}
            };

            list.addEventListener('click', async (e) => {
                const row = e.target.closest('[data-notification-id]');
                if (!row) return;
                const id = row.dataset.notificationId;

                if (e.target.closest('[data-dismiss]')) {
                    e.preventDefault(); e.stopPropagation();
                    row.closest('li')?.remove();
                    await dismiss(id);
                    await fetchCount();
                    return;
                }

                if (e.target.closest('[data-open]')) {
                    if (row.dataset.isRead !== '1') {
                        await markRead(id);
                        row.classList.remove('bg-blue-50/40', 'dark:bg-blue-500/5');
                        row.querySelector('.bg-blue-500')?.remove();
                        row.dataset.isRead = '1';
                        await fetchCount();
                    }
                    const url = row.dataset.actionUrl;
                    if (url) window.location.href = url;
                }
            });

            markAllBtn?.addEventListener('click', (e) => {
                e.preventDefault(); e.stopPropagation();
                markAllRead();
            });

            trigger.addEventListener('click', () => {
                setTimeout(() => { if (!hasLoaded) fetchList(); }, 0);
            });

            fetchCount();
            setInterval(() => {
                if (document.visibilityState === 'visible') fetchCount();
            }, 60_000);
            document.addEventListener('visibilitychange', () => {
                if (document.visibilityState === 'visible') fetchCount();
            });

            window.refreshNotifications = () => { fetchList(); fetchCount(); };
        })();

        /* =========================================================
           10. GLOBAL ⌘K / Ctrl+K — topbar search (single owner)
           ========================================================= */
        document.addEventListener('keydown', (e) => {
            const isMac = navigator.platform.toLowerCase().includes('mac');
            const cmdK  = (isMac ? e.metaKey : e.ctrlKey) && e.key.toLowerCase() === 'k';
            if (!cmdK) return;

            // Ignore if user is typing somewhere that isn't our search
            const tag = (e.target.tagName || '').toLowerCase();
            if (['input', 'textarea', 'select'].includes(tag) &&
                !e.target.closest('#topbar-search-panel, #sidebar-search-panel, #command-palette')) {
                return;
            }

            e.preventDefault();

            // If the sidebar's inline search is open, close it first.
            if (window.SidebarSearch?.isOpen?.()) window.SidebarSearch.close();

            const api = window.TopbarSearch;
            if (!api) return;
            api.isOpen() ? api.close() : api.open();
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();
</script>
