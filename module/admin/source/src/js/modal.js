// INIT
document.addEventListener('DOMContentLoaded', () => {
  let container = document.querySelector('.modals');

	if (!container) {
		container = document.createElement('div');
		container.classList.add('modals');
		document.body.appendChild(container);
	}

  document.querySelectorAll('.modal').forEach(modal => {
    const instance = {
      open: () => openModal(modal),
      close: () => closeModal(modal),
      destroy: () => {
        closeModal(modal);
        modal.remove();
        return true;
      }
    }

    modal.instance = instance;

    container.appendChild(modal);
  });

  // document.documentElement.style.setProperty('--scrollbar-width', `${getScrollbarWidth()}px`);

  // function getScrollbarWidth() {
  //   // Creating invisible container
  //   const outer = document.createElement('div');
  //   outer.style.visibility = 'hidden';
  //   outer.style.overflow = 'scroll'; // forcing scrollbar to appear
  //   outer.style.msOverflowStyle = 'scrollbar'; // needed for WinJS apps
  //   document.body.appendChild(outer);

  //   // Creating inner element and placing it in the container
  //   const inner = document.createElement('div');
  //   outer.appendChild(inner);

  //   // Calculating difference between container's full width and the child width
  //   const scrollbarWidth = (outer.offsetWidth - inner.offsetWidth);

  //   // Removing temporary elements from the DOM
  //   outer.parentNode.removeChild(outer);

  //   return scrollbarWidth;
  // }
});

// OPEN
document.addEventListener('click', event => {
  const trigger = event.target.closest('[data-modal]');

  if (!trigger) {
    return false;
  }

  const modal = document.querySelector(trigger.getAttribute('data-modal'));

  if (!modal) {
    return false;
  }

  openModal(modal);
});

// CLOSE
document.addEventListener('click', event => {
  if (event.target.closest('[data-modal]')) {
    return false;
  }

  const close = event.target.closest('[data-modal-close]');
  const modal = event.target.closest('.modal') || (close ? document.querySelector(close.getAttribute('data-modal-close')) : null);

  if ((close && modal) || (!close && !modal)) {
    closeModal(modal);
  }
});

function openModal(modal) {
  if (!modal) {
    return false;
  }

  const modal_body = modal.querySelector('.modal__body');
  if (modal_body) {
    modal_body.scrollTop = 0;
  }

  document.querySelectorAll('.modal').forEach(m => {
    if (m === modal) {
      m.classList.add('active');
    }
    else {
      m.classList.remove('active');
    }
  });

  document.body.classList.add('modal-open');

  const posX = window.scrollX;
  const posY = window.scrollY;
  window.onscroll = () => window.scroll(posX, posY);

  return true;
}

function closeModal(modal = null) {
  if (modal) {
    modal.classList.remove('active');
  }
  else {
    document.querySelectorAll('.modal').forEach(m => m.classList.remove('active'));
  }

  document.body.classList.remove('modal-open');
  window.onscroll = '';

  return true;
}
