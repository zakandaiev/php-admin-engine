function initModalContainer() {
  let container = document.querySelector('.modals');

  if (!container) {
    container = document.createElement('div');
    container.classList.add('modals');
    document.body.appendChild(container);
  }

  return container;
}

function openModal(modal) {
  if (!modal) {
    return false;
  }

  const modalBody = modal.querySelector('.modal__body');
  if (modalBody) {
    modalBody.scrollTop = 0;
  }

  document.querySelectorAll('.modal').forEach((m) => {
    if (m.id === modal.id) {
      if (!m.classList.contains('active')) {
        m.dispatchEvent(new CustomEvent('open', { bubbles: true }));
      }

      m.classList.add('active');
    } else {
      if (m.classList.contains('active')) {
        m.dispatchEvent(new CustomEvent('close', { bubbles: true }));
      }

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
    if (modal.classList.contains('active')) {
      modal.dispatchEvent(new CustomEvent('close', { bubbles: true }));
    }

    modal.classList.remove('active');
  } else {
    document.querySelectorAll('.modal').forEach((m) => {
      if (m.classList.contains('active')) {
        m.dispatchEvent(new CustomEvent('close', { bubbles: true }));
      }

      m.classList.remove('active');
    });
  }

  document.body.classList.remove('modal-open');
  window.onscroll = '';

  return true;
}

function createModal(title, options = {}) {
  return new Promise((resolve) => {
    const modal = document.createElement('div');
    modal.setAttribute('id', Math.random().toString(32).replace('0.', ''));
    modal.classList.add('modal');
    if (options.class && options.class.length) {
      options.class.split(' ').forEach((c) => modal.classList.add(c));
    } else {
      modal.classList.add('modal_center');
    }

    const modalHeader = document.createElement('header');
    modalHeader.classList.add('modal__header');

    const modalTitle = document.createElement('span');
    modalTitle.textContent = title;

    const modalClose = document.createElement('button');
    modalClose.setAttribute('type', 'button');
    modalClose.classList.add('modal__close');
    modalClose.innerHTML = '<i class="ti ti-x"></i>';
    modalClose.addEventListener('click', () => {
      closeModal(modal);
      modal.remove();
      resolve(false);
    });

    modalHeader.appendChild(modalTitle);
    modalHeader.appendChild(modalClose);

    modal.appendChild(modalHeader);

    if (options.text) {
      const modalBody = document.createElement('div');
      modalBody.classList.add('modal__body');
      modalClose.textContent = options.text;
      modal.appendChild(modalBody);
    }

    const modalFooter = document.createElement('footer');
    modalFooter.classList.add('modal__footer');

    const actions = options.actions || [{
      text: window.Engine?.translation?.modal?.ok || 'Ok',
      class: 'btn_primary',
    }];

    actions.forEach((action) => {
      const modalAction = document.createElement('button');
      modalAction.setAttribute('type', 'button');
      modalAction.classList.add('btn');
      if (action.class && action.class.length) {
        action.class.split(' ').forEach((c) => modalAction.classList.add(c));
      }
      modalAction.textContent = action.text;
      modalAction.addEventListener('click', (event) => {
        if (typeof action.callback === 'function') {
          resolve(action.callback(modal, event));
        } else {
          closeModal(modal);
          modal.remove();
          resolve(true);
        }
      });
      modalFooter.appendChild(modalAction);
    });

    modal.appendChild(modalFooter);

    modal.modal = {
      open: () => openModal(modal),
      close: () => closeModal(modal),
      destroy: () => {
        closeModal(modal);

        modal.remove();
      },
    };

    const container = initModalContainer();
    container.appendChild(modal);

    openModal(modal);
  });
}

async function confirmModal(title, text = null) {
  const modal = await createModal(title, {
    text,
    class: 'modal_sm modal_center',
    actions: [
      {
        text: window.Engine?.translation?.modal?.ok || 'Ok',
        class: 'btn_primary',
        callback: (m) => {
          closeModal(m);

          m.remove();

          return true;
        },
      },
      {
        text: window.Engine?.translation?.modal?.cancel || 'Cancel',
        class: 'btn_cancel',
        callback: (m) => {
          closeModal(m);

          m.remove();

          return false;
        },
      },
    ],
  });

  return modal;
}

export {
  initModalContainer,
  openModal,
  closeModal,
  createModal,
  confirmModal,
};
