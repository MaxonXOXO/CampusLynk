/**
 * CampusLynk Tables Module
 * Client-side sorting, pagination, and multi-select handling.
 */
export const TableHelper = {
  filter(tableId, query) {
    const table = document.getElementById(tableId);
    if (!table) return;
    const rows = table.querySelectorAll('tbody tr');
    const q = query.toLowerCase();
    rows.forEach(row => {
      row.style.display = row.innerText.toLowerCase().includes(q) ? '' : 'none';
    });
  }
};
