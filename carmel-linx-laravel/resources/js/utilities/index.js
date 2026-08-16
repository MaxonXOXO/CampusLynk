/**
 * CampusLynk Utilities Module
 * Shared helpers, formatting, and DOM utilities.
 */
export const DateFormatter = (dateStr) => {
  if (!dateStr) return '-';
  const d = new Date(dateStr);
  return d.toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' });
};
