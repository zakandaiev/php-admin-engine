import { request } from '@/js/util/request';
import toast from '@/js/util/toast';

// TODO
// form behavior
// refactor all

class Form {
  constructor(node, options = {}) {
    this.initializeVariables(node, options);
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
  }

  initValidation() {
    if (!this.node.hasAttribute('data-validate')) {
      return false;
    }

    this.submit.addEventListener('click', () => {
      this.node.querySelectorAll(':invalid').forEach((item) => {
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
        this.node.querySelectorAll('[name]').forEach((input) => {
          if (input.hasAttribute('data-picker') && input.instance && input.instance.selectedDates) {
            const pickerType = input.getAttribute('data-picker');

            if (!['date', 'datetime', 'month'].includes(pickerType)) {
              return false;
            }

            if (input.hasAttribute('data-multiple') || input.hasAttribute('data-range')) {
              input.value = input.instance.selectedDates.map((d) => this.getFormattedDate(pickerType, d)).join(' - ');
            } else {
              input.value = input.value.length && input.instance.selectedDates.length ? this.getFormattedDate(pickerType, input.instance.selectedDates[0]) : '';
            }
          }

          if (this.dataUnsetNull && !input.value.length) {
            input.setAttribute('disabled', true);
          }
        });

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

      if (data.code === 200 && data.status === 'success') {
        this.successRedirect(data);
        this.successResetForm();

        toast(this.dataMessage || data.message, data.status);
      } else {
        this.showErrors(data.data || []);

        toast(data.message, data.status);
      }

      this.enableForm();
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

    this.formatDates(data);

    if (this.dataUnsetNull) {
      this.unsetNullFormData(data);
    }

    return data;
  }

  formatDates(data) {
    // eslint-disable-next-line
    for (const pair of data.entries()) {
      const name = pair[0];
      const input = this.node.querySelector(`[name="${name}"]`);

      if (input && input.hasAttribute('data-picker') && input.instance && input.instance.selectedDates) {
        const pickerType = input.getAttribute('data-picker');

        if (!['date', 'datetime', 'month'].includes(pickerType)) {
          return false;
        }

        if (input.hasAttribute('data-multiple') || input.hasAttribute('data-range')) {
          data.delete(name);
          input.instance.selectedDates.forEach((d) => data.append(name, this.getFormattedDate(pickerType, d)));
        } else {
          data.set(
            name,
            input.value.length && input.instance.selectedDates.length ? this.getFormattedDate(pickerType, input.instance.selectedDates[0]) : '',
          );
        }
      }
    }
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

  // eslint-disable-next-line
  getFormattedDate(type, date) {
    const d = new Date(date.valueOf());
    d.setMinutes(d.getMinutes() - d.getTimezoneOffset());

    if (type === 'date') {
      return d.toJSON().slice(0, 10);
    }

    if (type === 'datetime') {
      return d.toJSON().slice(0, 19).replace('T', ' ');
    }

    if (type === 'month') {
      return d.toJSON().slice(0, 7);
    }

    return false;
  }

  disableForm() {
    this.node.setAttribute('disabled', 'disabled');
    this.node.classList.add(this.dataClass);
    this.submit.disabled = true;

    return true;
  }

  enableForm() {
    this.node.removeAttribute('disabled', 'disabled');
    this.node.classList.remove(this.dataClass);
    this.submit.disabled = false;

    this.node.querySelectorAll('input, textarea, select').forEach((input) => input.classList.remove('valid', 'invalid'));

    return true;
  }

  showErrors(data) {
    if (!this.node.hasAttribute('data-validate') || !data?.length) {
      return false;
    }

    data.forEach((validation) => {
      if (!validation.column || !validation.validation) {
        return false;
      }

      const column = this.node.querySelector(`[data-column-name="${validation.column}"]`);
      if (!column) {
        return false;
      }

      column.classList.remove('form__column_valid');
      column.classList.add('form__column_invalid');

      const errorNode = column.querySelector('.form__error');
      if (!errorNode) {
        return false;
      }

      errorNode.textContent = validation.validation;
    });
  }

  successRedirect(data) {
    if (this.dataRedirect === 'this') {
      document.location.reload();
    } else if (this.dataRedirect) {
      window.location.href = decodeURI(this.dataRedirect).replaceAll(/(\$[\w\d\-_]+)/g, data?.data);
    }

    return false;
  }

  successResetForm() {
    if (this.dataReset) {
      this.node.reset();

      return true;
    }

    return false;
  }
}

export default Form;
