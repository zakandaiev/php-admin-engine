// INIT
document.addEventListener('DOMContentLoaded', () => {
  const container = initModalContainer();

  document.querySelectorAll('.modal').forEach(modal => {
    const instance = {
      open: () => openModal(modal),
      close: () => closeModal(modal),
      destroy: () => {
        closeModal(modal);

        modal.remove();
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

function createModal(title, options = {}) {
  return new Promise((resolve, reject) => {
    const modal = document.createElement('div');
    modal.setAttribute('id', Math.random().toString(32).replace('0.', ''));
    modal.classList.add('modal');
    if (options.class && options.class.length) {
      options.class.split(' ').forEach(c => modal.classList.add(c));
    }
    else {
      modal.classList.add('modal_center');
    }

    const modal_header = document.createElement('header');
    modal_header.classList.add('modal__header');

    const modal_title = document.createElement('span');
    modal_title.textContent = title;

    const modal_close = document.createElement('button');
    modal_close.setAttribute('type', 'button');
    modal_close.classList.add('modal__close');
    modal_close.innerHTML = '<i class="icon icon-x"></i>';
    modal_close.addEventListener('click', event => {
      closeModal(modal);
      modal.remove();
      resolve(false);
    });

    modal_header.appendChild(modal_title);
    modal_header.appendChild(modal_close);

    modal.appendChild(modal_header);

    if (options.text) {
      const modal_body = document.createElement('div');
      modal_body.classList.add('modal__body');
      modal_close.textContent = options.text;
      modal.appendChild(modal_body);
    }

    const modal_footer = document.createElement('footer');
    modal_footer.classList.add('modal__footer');

    const actions = options.actions || [{
      text: ENGINE?.translation?.modal?.ok || 'Ok',
      class: 'btn_primary'
    }]

    actions.forEach(action => {
      const modal_action = document.createElement('button');
      modal_action.setAttribute('type', 'button');
      modal_action.classList.add('btn');
      if (action.class && action.class.length) {
        action.class.split(' ').forEach(c => modal_action.classList.add(c));
      }
      modal_action.textContent = action.text;
      modal_action.addEventListener('click', event => {
        if (typeof action.callback === 'function') {
          resolve(action.callback(modal, event));
        }
        else {
          closeModal(modal);
          modal.remove();
          resolve(true);
        }
      });
      modal_footer.appendChild(modal_action);
    });

    modal.appendChild(modal_footer);

    modal.instance = {
      open: () => openModal(modal),
      close: () => closeModal(modal),
      destroy: () => {
        closeModal(modal);

        modal.remove();
      }
    };

    const container = initModalContainer();
    container.appendChild(modal);

    openModal(modal);
  });
}

async function confirmation(title, text = null) {
  return await createModal(title, {
    text,
    class: 'modal_sm modal_center',
    actions: [
      {
        text: ENGINE?.translation?.modal?.ok || 'Ok',
        class: 'btn_primary',
        callback: (modal) => {
          closeModal(modal);

          modal.remove();

          return true;
        }
      },
      {
        text: ENGINE?.translation?.modal?.cancel || 'Cancel',
        class: 'btn_cancel',
        callback: (modal) => {
          closeModal(modal);

          modal.remove();

          return false;
        }
      }
    ]
  });
}
