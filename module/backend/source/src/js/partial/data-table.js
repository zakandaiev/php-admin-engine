import DataTable from '@/js/util/data-table';

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-table]').forEach((element) => {
    const dataTable = new DataTable(element, window.Engine);

    element.dataTable = dataTable;
  });
});
