document.addEventListener('DOMContentLoaded', () => {
  document.addEventListener('click', (event) => {
    const tab = event.target.closest('.tab');
    const tabNav = event.target.closest('.tab__nav-item');

    if (!tab || !tabNav) {
      return false;
    }

    event.preventDefault();

    const { hash } = tabNav;
    const id = hash.substring(1);

    if (tab.hasAttribute('data-save')) {
      if (window.history.pushState) {
        window.history.pushState(null, null, hash);
      } else {
        window.location.hash = hash;
      }
    }

    tab.querySelectorAll('.tab__nav-item').forEach((nav) => {
      if (nav.hash === hash) {
        nav.classList.add('active');
      } else {
        nav.classList.remove('active');
      }
    });

    tab.querySelectorAll('.tab__body').forEach((body) => {
      if (body.id === id) {
        body.classList.add('active');
      } else {
        body.classList.remove('active');
      }
    });
  });

  if (window.location.hash) {
    document.querySelectorAll('.tab__nav-item,.tab__body').forEach((item) => {
      if (!item.closest('.tab').hasAttribute('data-save')) {
        return false;
      }

      const { hash } = window.location;
      const id = hash.substring(1);

      if (item.classList.contains('tab__nav-item')) {
        if (item.hash === hash) {
          item.classList.add('active');
        } else {
          item.classList.remove('active');
        }
      } else if (item.classList.contains('tab__body')) {
        if (item.id === id) {
          item.classList.add('active');
        } else {
          item.classList.remove('active');
        }
      }
    });
  }
});
