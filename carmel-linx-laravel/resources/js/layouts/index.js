/**
 * CampusLynk Layouts Module
 * Handles AppShell, responsive sidebar toggle, and mobile drawers.
 */
export function initLayouts() {
  const sidebar = document.getElementById('campuslynk-sidebar');
  const sidebarToggle = document.getElementById('sidebar-toggle-btn');

  if (sidebar && sidebarToggle) {
    sidebarToggle.addEventListener('click', () => {
      sidebar.classList.toggle('hidden');
    });
  }
}

if (typeof window !== 'undefined') {
  document.addEventListener('DOMContentLoaded', initLayouts);
}
