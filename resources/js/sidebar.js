// Persist collapsed state for the sidebar rail
export function initSidebar() {
    const root    = document.documentElement;
    const sidebar = document.getElementById('sidebar');
    const toggle  = document.getElementById('sidebar-collapse-toggle');
    const main    = document.getElementById('main-content');

    if (!sidebar) return;

    // Restore saved state on load
    if (localStorage.getItem('sidebar-collapsed') === 'true') {
        root.classList.add('sidebar-collapsed');
        document.body.classList.add('sidebar-collapsed');
    }

    toggle?.addEventListener('click', () => {
        const collapsed = document.body.classList.toggle('sidebar-collapsed');
        localStorage.setItem('sidebar-collapsed', collapsed);
    });
}