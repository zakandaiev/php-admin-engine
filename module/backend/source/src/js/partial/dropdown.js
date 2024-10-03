document.addEventListener('DOMContentLoaded', () => {
  document.addEventListener('click', (event) => {
    const dropdown = event.target.closest('.dropdown');
    const dropdownItem = event.target.closest('.dropdown__item:not(:disabled):not(.disabled)');
    const dropdownHeader = event.target.closest('.dropdown__header');
    const dropdownText = event.target.closest('.dropdown__text');
    const dropdownSeparator = event.target.closest('.dropdown__separator');

    if (!dropdown) {
      document.querySelectorAll('.dropdown.active').forEach((dd) => dd.classList.remove('active'));

      return false;
    }

    if (dropdownItem && dropdownItem.tagName !== 'A') {
      event.preventDefault();
    }

    document.querySelectorAll('.dropdown').forEach((dd) => {
      if (dropdownHeader || dropdownText || dropdownSeparator || (dd.hasAttribute('data-keep-open') && dropdownItem)) {
        return false;
      }

      if (dd === dropdown) {
        dd.classList.toggle('active');
      } else {
        dd.classList.remove('active');
      }
    });

    if (!dropdownItem) {
      return false;
    }

    dropdown.querySelectorAll('.dropdown__item').forEach((di) => {
      if (di === dropdownItem) {
        di.classList.toggle('active');
      } else {
        di.classList.remove('active');
      }
    });
  });
});
