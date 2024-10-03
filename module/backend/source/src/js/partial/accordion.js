document.addEventListener('DOMContentLoaded', () => {
  document.addEventListener('click', (event) => {
    const accordion = event.target.closest('.accordion');
    const isBodyClick = event.target.closest('.accordion__body');

    if (!accordion || isBodyClick) {
      return false;
    }

    const header = accordion.querySelector('.accordion__header');
    const body = accordion.querySelector('.accordion__body');

    if (!header || !body) {
      return false;
    }

    event.preventDefault();

    // eslint-disable-next-line
    const isCollapse = (accordion.parentElement && (accordion.parentElement.hasAttribute('data-collapse') || accordion.parentElement.getAttribute('data-collapse') == true));
    if (isCollapse) {
      accordion.parentElement.querySelectorAll('.accordion').forEach((a) => {
        if (a === accordion) {
          return false;
        }

        a.classList.remove('accordion_active');

        const b = a.querySelector('.accordion__body');
        if (b) {
          b.style.height = '0px';
        }
      });
    }

    const bodyHeight = body.scrollHeight;
    if (accordion.classList.contains('accordion_active')) {
      body.style.height = '0px';
      accordion.classList.remove('accordion_active');
    } else {
      body.style.height = `${bodyHeight}px`;
      accordion.classList.add('accordion_active');
    }
  });

  document.querySelectorAll('.accordion').forEach((accordion) => {
    const body = accordion.querySelector('.accordion__body');

    if (!body || !accordion.hasAttribute('data-active')) {
      return false;
    }

    const bodyHeight = body.scrollHeight;
    body.style.height = `${bodyHeight}px`;
    body.style.transition = 'none';
    setTimeout(() => {
      body.style.transition = '';
    }, 100);
    accordion.classList.add('accordion_active');
  });
});
