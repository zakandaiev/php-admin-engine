import { initModalContainer, openModal, closeModal } from '@/js/util/modal';

// INIT
document.addEventListener('DOMContentLoaded', () => {
  const container = initModalContainer();

  document.querySelectorAll('.modal').forEach((modal) => {
    const modalInstance = {
      open: () => openModal(modal),
      close: () => closeModal(modal),
      destroy: () => {
        closeModal(modal);

        modal.remove();
      },
    };

    modal.modal = modalInstance;

    container.appendChild(modal);

    if (modal.id.startsWith('modal-form-') && modal.hasAttribute('data-action')) {
      const formNode = document.createElement('form');

      formNode.modal = {
        open: () => openModal(formNode),
        close: () => closeModal(formNode),
        destroy: () => {
          closeModal(formNode);

          formNode.remove();
        },
      };

      [...modal.attributes].forEach((attr) => {
        if (attr.name === 'data-action') {
          formNode.setAttribute('action', attr.value);
        } else {
          formNode.setAttribute(attr.name, attr.value);
        }
      });

      while (modal.firstChild) {
        formNode.appendChild(modal.firstChild);
      }

      modal.parentNode.replaceChild(formNode, modal);
    }
  });
});

// OPEN
document.addEventListener('click', (event) => {
  const trigger = event.target.closest('[data-modal]');
  if (!trigger) {
    return false;
  }

  const modal = document.getElementById(trigger.getAttribute('data-modal'));
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
  const datepicker = event.target.closest('.air-datepicker-global-container');

  if ((close && modal) || (!close && !modal && !datepicker)) {
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
