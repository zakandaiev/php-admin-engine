import { initModalContainer, openModal, closeModal } from '@/js/util/modal';

// INIT
document.addEventListener('DOMContentLoaded', () => {
  const container = initModalContainer();

  document.querySelectorAll('.modal').forEach((modal) => {
    const instance = {
      open: () => openModal(modal),
      close: () => closeModal(modal),
      destroy: () => {
        closeModal(modal);

        modal.remove();
      },
    };

    modal.instance = instance;

    container.appendChild(modal);
  });
});

// OPEN
document.addEventListener('click', (event) => {
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
document.addEventListener('click', (event) => {
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
document.addEventListener('keydown', (event) => {
  let isEscape = false;

  if ('key' in event) {
    isEscape = (event.key === 'Escape' || event.key === 'Esc');
  } else {
    isEscape = (event.keyCode === 27);
  }

  if (!isEscape) {
    return false;
  }

  document.querySelectorAll('.modal').forEach((modal) => closeModal(modal));
});
