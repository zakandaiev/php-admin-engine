import { request } from '@/js/util/request';
import toast from '@/js/util/toast';

// TODO
// form behavior

class Form {
  #registered = {};

  constructor(node, options = {}) {
    this.initializeVariables(node, options);
  }

  addEventListener(name, callback) {
    if (!this.#registered[name]) this.#registered[name] = [];
    this.#registered[name].push(callback);
  }

  triggerEvent(name, args) {
    this.#registered[name]?.forEach((fnc) => fnc.apply(this, args));
  }

  initializeVariables(node, options = {}) {
    this.node = node;
    this.options = options;

    this.submit = node.querySelector('[type="submit"]');
    if (!this.submit) {
      this.submit = document.createElement('button');
      this.submit.classList.add('hidden');
      this.node.appendChild(this.submit);
    }
    this.dataSubmitNative = this.node.hasAttribute('data-submit-native') ? true : false;
    this.dataSubmitOnchange = this.node.hasAttribute('data-submit-onchange') ? true : false;
    this.dataUnsetNull = this.node.hasAttribute('data-unset-null') ? true : false;

    if (this.node.hasAttribute('data-native')) {
      return this;
    }

    this.action = this.node.action;
    this.method = this.node.method || 'POST';
    if (this.method.toLowerCase() === 'get') {
      this.method = 'POST';
    }
    this.dataConfirm = this.node.getAttribute('data-confirm');
    this.dataClass = this.node.getAttribute('data-class') || 'form_loading';
    this.dataReset = this.node.hasAttribute('data-reset') ? true : false;
    this.dataRedirect = this.node.getAttribute('data-redirect');
    this.dataMessage = this.node.getAttribute('data-message');
    this.dataMessageError = this.node.getAttribute('data-message-error');
    this.api = {
      delayMs: this.node.getAttribute('data-delay')
        ? parseInt(this.node.getAttribute('data-delay'), 10)
        : this.options?.backend?.delayMs || 500,

      timeoutMs: this.node.getAttribute('data-timeout')
        ? parseInt(this.node.getAttribute('data-timeout'), 10)
        : this.options?.backend?.timeoutMs || 15000,
    };

    if (this.action) {
      this.initialize();
    }
  }

  initialize() {
    this.initValidation();
    this.insertLoader();
    this.initSubmit();
    this.listenSubmit();

    this.node.form = this;
  }

  initValidation() {
    if (!this.node.hasAttribute('data-validate')) {
      return false;
    }

    this.submit.addEventListener('click', () => {
      this.node.querySelectorAll(':invalid').forEach((item) => {
        if (item.tagName === 'FIELDSET') {
          return false;
        }

        const column = item.closest('.form__column');
        if (column) {
          column.classList.remove('form__column_valid');
          column.classList.add('form__column_invalid');

          return true;
        }

        item.classList.remove('valid');
        item.classList.add('invalid');
      });
      this.node.querySelectorAll(':valid').forEach((item) => {
        if (item.tagName === 'FIELDSET') {
          return false;
        }

        const column = item.closest('.form__column');
        if (column) {
          column.classList.remove('form__column_invalid');
          column.classList.add('form__column_valid');

          return true;
        }

        item.classList.remove('invalid');
        item.classList.add('valid');
      });
    });

    this.node.querySelectorAll('input, textarea, select').forEach((input) => {
      const events = ['input', 'change'];

      events.forEach((e) => {
        input.addEventListener(e, () => {
          if (!input.validity.valid) {
            const column = input.closest('.form__column');
            if (column) {
              column.classList.remove('form__column_valid');
              column.classList.add('form__column_invalid');

              return true;
            }

            input.classList.remove('valid');
            input.classList.add('invalid');
          } else {
            const column = input.closest('.form__column');
            if (column) {
              column.classList.remove('form__column_invalid');
              column.classList.add('form__column_valid');

              return true;
            }
            input.classList.remove('invalid');
            input.classList.add('valid');
          }
        });
      });
    });
  }

  insertLoader() {
    const loader = this.options?.theme?.loader;

    if (loader) {
      this.node.insertAdjacentHTML('beforeend', `<div class="form__loader">${loader}</div>`);

      return true;
    }

    return false;
  }

  initSubmit() {
    if (this.dataSubmitOnchange) {
      this.node.querySelectorAll('[name]').forEach((input) => {
        input.onchange = () => {
          this.node.requestSubmit(this.submit);
        };
      });
    }
  }

  listenSubmit() {
    this.node.addEventListener('submit', async (event) => {
      if (this.dataSubmitNative) {
        return false;
      }

      event.preventDefault();

      const confirmation = await this.checkConfirmation();
      if (!confirmation) {
        return false;
      }

      this.disableForm();

      const data = await request(
        this.action,
        {
          headers: {},
          method: this.method,
          body: this.getFormData(),
        },
        this.api.timeoutMs,
        this.api.delayMs,
      );

      const isSuccess = data.code === 200 && data.status === 'success';

      if (isSuccess) {
        const redirectResult = this.successRedirect(data.data);
        if (!redirectResult) {
          this.successResetForm();

          toast(this.dataMessage || data.message, data.status);
        }
      } else {
        this.showErrors(data.data || []);

        toast(this.dataMessageError || data.message, data.status);
      }

      this.enableForm(isSuccess);

      this.triggerEvent('submit', [data]);
    });
  }

  async checkConfirmation() {
    if (!this.dataConfirm) {
      return true;
    }

    let confirmation = false;

    confirmation = await confirmation(this.dataConfirm);

    return confirmation;
  }

  getFormData() {
    const data = new FormData(this.node);

    if (this.options?.csrf) {
      data.set(this.options.csrf.key, this.options.csrf.token);
    }

    if (this.dataUnsetNull) {
      this.unsetNullFormData(data);
    }

    return data;
  }

  // eslint-disable-next-line
  unsetNullFormData(data) {
    // eslint-disable-next-line
    for (const pair of data.entries()) {
      const name = pair[0];
      const value = pair[1];

      if (!value || !value.length) {
        data.delete(name);
      }
    }
  }

  disableForm() {
    this.node.setAttribute('disabled', 'disabled');
    this.node.classList.add(this.dataClass);
    this.submit.disabled = true;

    return true;
  }

  enableForm(isSuccess = false) {
    this.node.removeAttribute('disabled', 'disabled');
    this.node.classList.remove(this.dataClass);
    this.submit.disabled = false;

    if (!isSuccess) {
      return true;
    }

    this.node.querySelectorAll('input, textarea, select').forEach((input) => {
      const column = input.closest('.form__column');
      if (column) {
        column.classList.remove('form__column_valid', 'form__column_invalid');

        return true;
      }

      input.classList.remove('valid', 'invalid');
    });

    return true;
  }

  showErrors(data) {
    if (!this.node.hasAttribute('data-validate') || !data?.length) {
      return false;
    }

    data.forEach((validation) => {
      if (!validation.column) {
        return false;
      }

      const column = this.node.querySelector(`[data-column-name="${validation.column}"]`);
      if (!column) {
        return false;
      }

      const message = {};

      const input = column.querySelector('[name]');
      if (input && input.hasAttribute('data-message')) {
        try {
          const inputMessage = JSON.parse(input.getAttribute('data-message') || '{}') || {};
          Object.assign(message, inputMessage);
        } catch {
          // do nothing
        }
      }

      column.classList.remove('form__column_valid');
      column.classList.add('form__column_invalid');

      const errorNode = column.querySelector('.form__error');
      if (!errorNode) {
        return false;
      }

      errorNode.textContent = message[validation.validationName] || validation.validationMessage;
    });
  }

  successRedirect(data) {
    if (this.dataRedirect === 'this') {
      document.location.reload();
    } else if (this.dataRedirect) {
      let redirectLink = decodeURI(this.dataRedirect);

      if (typeof data === 'object' && data !== null) {
        redirectLink = redirectLink.replaceAll(/(\$[\w\d\-_]+)/g, (match) => data[match.slice(1)] || match);
      } else {
        redirectLink = redirectLink.replaceAll(/(\$[\w\d\-_]+)/g, data);
      }

      window.location.href = redirectLink;
    }

    return this.dataRedirect ? true : false;
  }

  successResetForm() {
    if (this.dataReset) {
      this.resetInputs();

      return true;
    }

    return false;
  }

  resetInputs() {
    this.node.reset();

    // RESET CUSTOM FILES
    this.node.querySelectorAll('.form__input').forEach((formInput) => {
      if (!formInput.pond) {
        return false;
      }

      formInput.pond.setOptions({
        files: [],
      });
    });

    // RESET CUSTOM SELECTS
    this.node.querySelectorAll('select').forEach((input) => {
      if (input.slim) {
        input.slim.setSelected([]);
        return true;
      }

      input.selectedIndex = 0;
    });

    // RESET CUSTOM WYSIWYGS
    this.node.querySelectorAll('[data-wysiwyg]').forEach((input) => {
      if (input.wysiwyg) {
        input.wysiwyg.root.innerHTML = '';

        return true;
      }

      input.value = '';
    });

    return true;
  }

  resetForm() {
    this.resetInputs();

    setTimeout(() => {
      this.enableForm(true);
    }, 100);

    return true;
  }

  populateInputs(data = []) {
    data.forEach((item) => {
      const input = this.node.querySelector(`[name*="${item.name}"]`);
      if (!input) {
        return false;
      }

      this.setInputValue(input, item.type, item.value);
    });

    setTimeout(() => {
      this.enableForm(true);
    }, 100);

    return true;
  }

  setInputValue(input, type, value = '') {
    if (type === 'boolean' || type === 'checkbox' || type === 'radio') {
      this.node.querySelectorAll(`[name*="${input.name}"]`).forEach((i) => {
        if (
          value === i.value
          || (value === true && i.value === 'true')
          || (Array.isArray(value) && value.includes(i.value))
        ) {
          i.checked = true;
        } else {
          i.checked = false;
        }
      });
    } else if (['date', 'datetime', 'month', 'time'].includes(type) && Array.isArray(value)) {
      input.value = value.join(' - ');
      input.dispatchEvent(new CustomEvent('change', { bubbles: true }));
    } else if (type === 'file') {
      const formInput = input.closest('.form__input');
      if (!formInput || !formInput.pond) {
        return false;
      }

      const files = [];

      try {
        const inputFiles = Array.isArray(value) ? value : [value];
        inputFiles.forEach((file) => {
          if (!file || file === 'null') {
            return false;
          }

          files.push({
            source: file,
            options: {
              type: 'local',
            },
          });
        });
      } catch (error) {
        // do nothing
      }

      formInput.pond.setOptions({
        files,
      });
    } else if (type === 'select') {
      if (input.slim) {
        input.slim.setSelected(value);
      } else {
        input.selectedIndex = value || 0;
      }
    } else if (type === 'wysiwyg') {
      if (input.wysiwyg) {
        input.wysiwyg.root.innerHTML = value;
      } else {
        input.value = value;
      }
    } else {
      input.value = value;
    }

    return true;
  }
}

export default Form;
