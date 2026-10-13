<ul id="notifications-list"
    class="max-h-80 overflow-y-auto tb-scroll divide-y divide-gray-100/70 dark:divide-gray-700/50">
    {{-- Filled by JS --}}
    <li class="tb-notif-skeleton">
        <div class="flex gap-3 px-4 py-3">
            <span class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 animate-pulse"></span>
            <div class="flex-1 space-y-2">
                <div class="h-3 w-2/3 rounded bg-gray-100 dark:bg-gray-700 animate-pulse"></div>
                <div class="h-2 w-1/3 rounded bg-gray-100 dark:bg-gray-700 animate-pulse"></div>
            </div>
        </div>
    </li>
</ul>

<script>

/* =========================================================
   NOTIFICATIONS — fetch, render, mark read, dismiss
   ========================================================= */
(function () {
    const list       = document.getElementById('notifications-list');
    const badge      = document.querySelector('#notifications-button .absolute span.relative');
    const countBadge = document.querySelector('#notifications-menu .bg-blue-50, #notifications-menu .dark\\:bg-blue-500\\/10');
    if (!list) return;

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

    const iconMap = {
        success: { bg: 'bg-emerald-50 dark:bg-emerald-500/10', text: 'text-emerald-600 dark:text-emerald-400',
                   svg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>', outline: true },
        warning: { bg: 'bg-amber-50 dark:bg-amber-500/10', text: 'text-amber-600 dark:text-amber-400',
                   svg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5 19h14a2 2 0 001.84-2.75L13.74 4a2 2 0 00-3.48 0L3.16 16.25A2 2 0 005 19z"/>', outline: true },
        error:   { bg: 'bg-red-50 dark:bg-red-500/10',       text: 'text-red-600 dark:text-red-400',
                   svg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>', outline: true },
        info:    { bg: 'bg-blue-50 dark:bg-blue-500/10',     text: 'text-blue-600 dark:text-blue-400',
                   svg: '<path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" fill-rule="evenodd" clip-rule="evenodd"/>', outline: false },
        action:  { bg: 'bg-violet-50 dark:bg-violet-500/10', text: 'text-violet-600 dark:text-violet-400',
                   svg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>', outline: true },
        general: { bg: 'bg-gray-50 dark:bg-gray-700/50',     text: 'text-gray-600 dark:text-gray-400',
                   svg: '<path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6z"/>', outline: false },
    };

    const escapeHtml = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    }[c]));

    const render = (items) => {
        if (!items.length) {
            list.innerHTML = `
                <li class="px-4 py-10 text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full
                                bg-gray-100 dark:bg-gray-700/50 mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">All caught up</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">No new notifications</p>
                </li>`;
            return;
        }

        list.innerHTML = items.map(n => {
            const c = iconMap[n.category] || iconMap.general;
            const iconMarkup = c.outline
                ? `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">${c.svg}</svg>`
                : `<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">${c.svg}</svg>`;

            const unreadDot = !n.is_read
                ? `<span class="absolute left-1 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-blue-500"></span>`
                : '';

            const groupBadge = (n.group_count > 1)
                ? `<span class="text-[10px] font-semibold px-1.5 py-0.5 rounded-full
                                bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300 ml-1">×${n.group_count}</span>`
                : '';

            return `
            <li>
                <a href="${escapeHtml(n.action_url || '#')}"
                   data-notification-id="${n.id}"
                   data-action="${n.action_url ? 'navigate' : 'read'}"
                   class="tb-notification-item relative ${!n.is_read ? 'bg-blue-50/40 dark:bg-blue-500/5' : ''}">
                    ${unreadDot}
                    <span class="tb-notification-icon ${c.bg} ${c.text}">
                        ${iconMarkup}
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">
                            ${escapeHtml(n.title)}${groupBadge}
                        </p>
                        ${n.message ? `<p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate">${escapeHtml(n.message)}</p>` : ''}
                        <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1">
                            ${escapeHtml(n.time_ago || '')}
                        </p>
                    </div>
                    <button type="button" data-dismiss="${n.id}"
                            class="opacity-0 group-hover:opacity-100 text-gray-400 hover:text-red-500 p-1 self-start
                                   transition-opacity">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </a>
            </li>`;
        }).join('');
    };

    const updateBadge = (count) => {
        if (countBadge) {
            if (count > 0) {
                countBadge.textContent = `${count} new`;
                countBadge.classList.remove('hidden');
            } else {
                countBadge.classList.add('hidden');
            }
        }
        // Pulsing dot on bell
        const dot = document.querySelector('#notifications-button .absolute');
        if (dot) dot.classList.toggle('hidden', count === 0);
    };

    const fetchNotifications = async () => {
        try {
            const res = await fetch('{{ route('notifications.index') }}?per_page=10', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (!res.ok) throw new Error('fetch failed');
            const json = await res.json();
            render(json.data);
            updateBadge(json.meta.unread_count);
        } catch (e) {
            console.warn('[notifications] fetch failed', e);
        }
    };

    const markRead = (id) => fetch(`/notifications/${id}/read`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
    });

    const dismiss = (id) => fetch(`/notifications/${id}/dismiss`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
    });

    // Click handler: mark read, then navigate if there's a URL
    list.addEventListener('click', async (e) => {
        const dismissBtn = e.target.closest('[data-dismiss]');
        if (dismissBtn) {
            e.preventDefault();
            e.stopPropagation();
            const id = dismissBtn.dataset.dismiss;
            dismissBtn.closest('li')?.remove();
            await dismiss(id);
            fetchNotifications();
            return;
        }

        const item = e.target.closest('[data-notification-id]');
        if (!item) return;
        const id = item.dataset.notificationId;
        await markRead(id);
        if (item.dataset.action !== 'navigate') {
            e.preventDefault();
            fetchNotifications();
        }
    });

    // Load when the bell is clicked
    document.getElementById('notifications-button')
        ?.addEventListener('click', fetchNotifications);

    // Poll every 60s (only when tab visible)
    let pollTimer;
    const startPolling = () => {
        pollTimer = setInterval(() => {
            if (document.visibilityState === 'visible') {
                fetch('{{ route('notifications.unread-count') }}', {
                    headers: { 'Accept': 'application/json' }
                })
                .then(r => r.json())
                .then(j => updateBadge(j.count))
                .catch(() => {});
            }
        }, 60_000);
    };
    startPolling();

    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            fetch('{{ route('notifications.unread-count') }}')
                .then(r => r.json())
                .then(j => updateBadge(j.count))
                .catch(() => {});
        }
    });
})();

</script>