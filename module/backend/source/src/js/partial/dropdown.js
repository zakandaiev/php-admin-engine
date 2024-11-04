document.addEventListener('DOMContentLoaded', () => {
  // INIT
  document.querySelectorAll('.dropdown').forEach((dropdown) => {
    const menu = dropdown.querySelector('.dropdown__menu');

    menu.addEventListener('animationend', (e) => {
      if (e.animationName === 'dropdown-disappear') {
        menu.remove();
      }
    });

    const instance = {
      trigger: dropdown,
      menu,
      open: openDropdown,
      close: closeDropdown,
    };

    if (menu) {
      menu.remove();
    }

    dropdown.dropdown = instance;
  });

  // CLICK
  document.addEventListener('click', (event) => {
    closeAllDropdowns(event.target);

    const trigger = event.target.closest('.dropdown');
    if (!trigger || !trigger.dropdown) {
      return false;
    }

    trigger.dropdown.open();
  });

  // SCROLL
  document.addEventListener('scroll', () => closeAllDropdowns());

  // FUNCTIONS
  function openDropdown() {
    const { trigger, menu } = this;
    if (!trigger || !menu) {
      return false;
    }

    menu.classList.remove('dropdown__menu_disappear');

    document.body.appendChild(menu);

    const triggerRect = trigger.getBoundingClientRect();
    const placement = menu.getAttribute('data-position') || 'bottom-left';
    const offset = 5;

    document.body.appendChild(menu);
    const menuRect = menu.getBoundingClientRect();

    let top = triggerRect.bottom + offset;
    let { left } = triggerRect;

    menu.style.top = `${top}px`;
    menu.style.left = `${left}px`;

    if (placement === 'top-left') {
      top = triggerRect.top - offset - menuRect.height;
      left = triggerRect.left;
    } else if (placement === 'top-center') {
      top = triggerRect.top - offset - menuRect.height;
      left = triggerRect.left + (triggerRect.width / 2) - (menuRect.width / 2);
    } else if (placement === 'top-right') {
      top = triggerRect.top - offset - menuRect.height;
      left = triggerRect.right - menuRect.width;
    } else if (placement === 'bottom-left') {
      top = triggerRect.bottom + offset;
      left = triggerRect.left;
    } else if (placement === 'bottom-center') {
      top = triggerRect.bottom + offset;
      left = triggerRect.left + (triggerRect.width / 2) - (menuRect.width / 2);
    } else if (placement === 'bottom-right') {
      top = triggerRect.bottom + offset;
      left = triggerRect.right - menuRect.width;
    } else if (placement === 'left-top') {
      top = triggerRect.bottom - menuRect.height;
      left = triggerRect.left - offset - menuRect.width;
    } else if (placement === 'left-center') {
      top = triggerRect.top + (triggerRect.height / 2) - (menuRect.height / 2);
      left = triggerRect.left - offset - menuRect.width;
    } else if (placement === 'left-bottom') {
      top = triggerRect.top;
      left = triggerRect.left - offset - menuRect.width;
    } else if (placement === 'right-top') {
      top = triggerRect.bottom - menuRect.height;
      left = triggerRect.right + offset;
    } else if (placement === 'right-center') {
      top = triggerRect.top + (triggerRect.height / 2) - (menuRect.height / 2);
      left = triggerRect.right + offset;
    } else if (placement === 'right-bottom') {
      top = triggerRect.top;
      left = triggerRect.right + offset;
    }

    menu.style.top = `${top}px`;
    menu.style.left = `${left}px`;

    return true;
  }

  function closeDropdown(clickTarget) {
    const { trigger, menu } = this;
    if (!trigger || !menu) {
      return false;
    }

    if (!clickTarget) {
      menu.classList.add('dropdown__menu_disappear');

      return true;
    }

    const dropdownItem = clickTarget.closest('.dropdown__item');
    const dropdownHeader = clickTarget.closest('.dropdown__header');
    const dropdownText = clickTarget.closest('.dropdown__text');
    const dropdownDivider = clickTarget.closest('.dropdown__divider');

    const isKeepOpen = menu.hasAttribute('data-keep-open') ? true : false;

    if (dropdownHeader || dropdownText || dropdownDivider || (isKeepOpen && dropdownItem)) {
      menu.querySelectorAll('.dropdown__item').forEach((di) => {
        if (di === dropdownItem) {
          di.classList.toggle('active');
        } else {
          di.classList.remove('active');
        }
      });

      return false;
    }

    menu.classList.add('dropdown__menu_disappear');

    return true;
  }

  function closeAllDropdowns(clickTarget) {
    document.querySelectorAll('.dropdown').forEach((dropdown) => {
      if (dropdown.dropdown) {
        dropdown.dropdown.close(clickTarget);
      }
    });
  }
});
