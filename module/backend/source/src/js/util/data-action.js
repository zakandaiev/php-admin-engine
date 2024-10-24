import { fadeOut } from '@/js/util/fade';
import { confirmModal } from '@/js/util/modal';
import { request } from '@/js/util/request';
import { isStringValidJSON, isArray } from '@/js/util/is-object';
import toast from '@/js/util/toast';

class DataAction {
  constructor(node, options = {}) {
    this.node = node;
    this.options = options;

    this.action = this.node.getAttribute('data-action');
    this.method = this.node.getAttribute('data-method') || 'POST';
    if (this.method.toLowerCase() === 'get') {
      this.method = 'POST';
    }
    this.dataEvent = this.node.getAttribute('data-event') || 'click';
    this.dataConfirm = this.node.getAttribute('data-confirm');
    this.dataBody = this.node.getAttribute('data-body') || '[]';
    this.dataUnsetNull = this.node.hasAttribute('data-unset-null') ? true : false;
    this.dataClass = this.node.getAttribute('data-class') || 'submit';
    this.dataClassTarget = this.node.getAttribute('data-class-target');
    this.dataRedirect = this.node.getAttribute('data-redirect');
    this.dataIncrement = this.node.getAttribute('data-increment');
    this.dataDecrement = this.node.getAttribute('data-decrement');
    this.dataRemove = this.node.getAttribute('data-remove');
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
    this.node.addEventListener(this.dataEvent, async (event) => {
      event.preventDefault();
      event.stopImmediatePropagation();

      const confirmation = await this.checkConfirmation();
      if (!confirmation) {
        return false;
      }

      this.disableNodes();

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

      if (data.status === 'success') {
        this.successRedirect(data);
        this.successCounters();
        this.successRemoveNodes();

        toast(this.dataMessage || data.message, data.status);
      } else {
        toast(data.message, data.status);
      }

      this.enableNodes();
    });
  }

  async checkConfirmation() {
    if (!this.dataConfirm) {
      return true;
    }

    let confirmation = false;

    confirmation = await confirmModal(this.dataConfirm);

    return confirmation;
  }

  getFormData() {
    const data = new FormData();

    let body = isStringValidJSON(this.dataBody) ? JSON.parse(this.dataBody) : [];
    if (isArray(body)) {
      body = { ...body };
    }

    Object.keys(body).forEach((columnName) => {
      const columnValue = body[columnName] || null;

      if (this.dataUnsetNull && columnValue === null) {
        return false;
      }

      data.set(columnName, columnValue);
    });

    if (this.options?.csrf) {
      data.set(this.options.csrf.key, this.options.csrf.token);
    }

    return data;
  }

  disableNodes() {
    // DISABLE SELF
    this.node.setAttribute('disabled', 'disabled');
    this.node.classList.add('submit');

    // ADD CLASS TO TARGETS
    if (this.dataClass && this.dataClassTarget) {
      document.querySelectorAll(this.dataClassTarget).forEach((target) => {
        target.classList.add(this.dataClass);
      });
    } else if (this.dataClass) {
      this.node.classList.add(this.dataClass);
    }

    return true;
  }

  enableNodes() {
    // ENABLE SELF
    this.node.removeAttribute('disabled', 'disabled');
    this.node.classList.remove('submit');

    // REMOVE CLASS FROM TARGETS
    if (this.dataClass && this.dataClassTarget) {
      document.querySelectorAll(this.dataClassTarget).forEach((target) => {
        target.classList.remove(this.dataClass);
      });
    } else if (this.dataClass) {
      this.node.classList.remove(this.dataClass);
    }

    return true;
  }

  successRedirect(data) {
    if (this.dataRedirect) {
      if (this.dataRedirect === 'this') {
        document.location.reload();
      } else {
        window.location.href = decodeURI(this.dataRedirect).replaceAll(/({\w+})/g, data?.data);
      }
    }
  }

  successCounters() {
    if (this.dataIncrement) {
      document.querySelectorAll(this.dataIncrement).forEach((target) => {
        const targetValue = parseInt(target.textContent, 10);
        target.textContent = targetValue + 1;
      });
    }

    if (this.dataDecrement) {
      document.querySelectorAll(this.dataDecrement).forEach((target) => {
        const targetValue = parseInt(target.textContent, 10);
        target.textContent = targetValue - 1;
      });
    }
  }

  successRemoveNodes() {
    if (!this.dataRemove) {
      return false;
    }

    if (this.dataRemove === 'this') {
      fadeOut(this.node);

      return true;
    }

    if (this.dataRemove === 'trow') {
      const trow = this.node.closest('tr');

      fadeOut(trow, (tr) => {
        const tbody = tr.parentNode;

        tr.remove();

        if (tbody.childElementCount === 0) {
          window.location.reload();
        }
      });

      return true;
    }

    document.querySelectorAll(this.dataRemove).forEach((target) => {
      fadeOut(target);
    });

    return true;
  }
}

export default DataAction;
