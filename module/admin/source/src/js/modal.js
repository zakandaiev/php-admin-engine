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

// CLOSE BY ESC
document.addEventListener('keydown', event => {
  let is_escape = false;

  if ('key' in event) {
    is_escape = (event.key === 'Escape' || event.key === 'Esc');
  }
  else {
    is_escape = (event.keyCode === 27);
  }

  if (!is_escape) {
    return false;
  }

  document.querySelectorAll('.modal').forEach(modal => closeModal(modal));
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
