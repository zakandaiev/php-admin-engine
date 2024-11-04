import { fadeOut } from '@/js/util/fade';
import { randomString } from '@/js/util/random';
import route from '@/js/util/route';

class DataTable {
  constructor(node, options = {}) {
    this.node = node;
    this.options = options;

    this.initialize();
  }

  initialize() {
    this.modalId = this.node.getAttribute('data-table') || null;
    if (!this.modalId) {
      return false;
    }

    this.modalNode = document.getElementById(this.modalId);
    if (!this.modalNode || !this.modalNode.modal || !this.modalNode.form) {
      return false;
    }

    this.modalNode.addEventListener('close', () => {
      this.editingTrow = false;
    });

    this.modal = this.modalNode.modal;

    this.form = this.modalNode.form;
    this.form.addEventListener('submit', (data) => {
      this.formSubmit(data);
    });

    this.column = [];
    this.initialValue = [];
    this.stateValue = [];
    try {
      this.column = JSON.parse(this.node.getAttribute('data-column') || '[]');
      this.initialValue = JSON.parse(this.node.value || '[]');
    } catch (error) {
      // do nothing
    }

    if (!this.column.length) {
      return false;
    }

    if (!this.initTable()) {
      return false;
    }

    this.editingTrow = false;

    this.node.after(this.table);
    this.node.classList.add('hidden');

    this.node.dataTable = this;

    return true;
  }

  initTable() {
    this.table = document.createElement('table');
    this.thead = document.createElement('thead');
    this.tbody = document.createElement('tbody');

    this.table.classList.add('table');

    this.createThead();
    this.createTbody();

    this.initialValue.forEach((value) => {
      this.createTrow(value);
    });

    this.table.appendChild(this.tbody);

    return true;
  }

  createThead() {
    if (!this.thead) {
      return false;
    }

    const trow = document.createElement('tr');

    this.column.forEach((col) => {
      const tcol = document.createElement('th');
      tcol.innerText = col.label;
      trow.appendChild(tcol);
    });

    const tcolActions = document.createElement('th');
    tcolActions.classList.add('table__actions');

    const buttonAdd = document.createElement('button');
    buttonAdd.setAttribute('type', 'button');
    buttonAdd.setAttribute('data-modal', this.modalId);
    buttonAdd.classList.add('btn', 'btn_sm', 'btn_icon', 'btn_primary');
    buttonAdd.innerHTML = '<i class="ti ti-plus"></i>';
    buttonAdd.addEventListener('click', (event) => {
      event.preventDefault();

      this.form.resetForm();
    });

    tcolActions.appendChild(buttonAdd);
    trow.appendChild(tcolActions);
    this.thead.appendChild(trow);
    this.table.appendChild(this.thead);

    return true;
  }

  createTbody() {
    if (!this.tbody) {
      return false;
    }

    this.tbody.setAttribute('data-sortable', '.sortable__handle');
    this.tbody.onEnd = () => this.updateStateValue();

    this.table.appendChild(this.tbody);

    return true;
  }

  createTrow(value) {
    const trow = this.editingTrow || document.createElement('tr');
    trow.value = [];
    if (this.editingTrow) {
      trow.innerHTML = '';
    } else {
      trow.dataId = randomString();
    }

    this.column.forEach((col) => {
      const tcol = document.createElement('td');
      const tcolValue = {
        type: col.type,
        name: col.name,
        value: value[col.name] || null,
      };

      Object.assign(tcol, tcolValue);

      tcol.setAttribute('data-name', tcol.name);

      tcol.innerHTML = this.getColHtml(tcol.type, tcol.value, col.options);

      trow.value.push(tcolValue);

      trow.appendChild(tcol);
    });

    const tcolActions = document.createElement('th');
    tcolActions.classList.add('table__actions');

    const buttonOrder = document.createElement('button');
    buttonOrder.setAttribute('type', 'button');
    buttonOrder.classList.add('table__action', 'sortable__handle');
    buttonOrder.innerHTML = '<i class="ti ti-menu-order"></i>';

    const buttonEdit = document.createElement('button');
    buttonEdit.setAttribute('type', 'button');
    buttonEdit.setAttribute('data-modal', this.modalId);
    buttonEdit.classList.add('table__action', 'sortable__handle');
    buttonEdit.innerHTML = '<i class="ti ti-edit"></i>';
    buttonEdit.addEventListener('click', (event) => {
      event.preventDefault();

      this.editingTrow = trow;

      this.form.resetForm();
      this.form.populateInputs(trow.value);
    });

    const buttonDelete = document.createElement('button');
    buttonDelete.setAttribute('type', 'button');
    buttonDelete.classList.add('table__action', 'sortable__handle');
    buttonDelete.innerHTML = '<i class="ti ti-trash"></i>';
    buttonDelete.addEventListener('click', (event) => {
      event.preventDefault();

      fadeOut(trow, (el) => {
        el.remove();
        this.updateStateValue();
      });
    });

    tcolActions.appendChild(buttonOrder);
    tcolActions.appendChild(buttonEdit);
    tcolActions.appendChild(buttonDelete);
    trow.appendChild(tcolActions);

    if (this.editingTrow) {
      this.stateValue = this.stateValue.filter((v) => v.id !== trow.dataId);
    } else {
      this.tbody.appendChild(trow);
    }

    this.stateValue.push({ ...value, id: trow.dataId });

    return trow;
  }

  // eslint-disable-next-line
  getColHtml(type, value, options = []) {
    let output = '';

    if (typeof value === 'string') {
      value = value
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
    }

    if (type === 'boolean') {
      const icon = value === true ? 'check' : 'x';
      output = `<span class="table__action cursor-default"><i class="ti ti-${icon}"></i></span>`;
    } else if (type === 'file') {
      output = '';

      const files = Array.isArray(value) ? value : [value];
      const galleryId = `gallery-${randomString()}`;

      files.forEach((fileName) => {
        const fileUrl = `${route.base}/${fileName}`;
        const isImage = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'].includes(fileName?.split('.')?.pop()?.toLowerCase());

        if (isImage) {
          output += `<a class="table__action" href="${fileUrl}" target="_blank" data-fancybox="${galleryId}"><i class="ti ti-photo"></i></a>`;
        } else if (value?.length) {
          output += `<a class="table__action" href="${fileUrl}" target="_blank"><i class="ti ti-file"></i></a>`;
        } else {
          output = '<span class="table__action cursor-default"><i class="ti ti-minus"></i></span>';
        }
      });
    } else if (Array.isArray(value) && value.length) {
      output = value.join(['date', 'datetime', 'month', 'time'].includes(type) ? ' - ' : ', ');
    } else {
      output = value?.length ? value : '<span class="table__action cursor-default"><i class="ti ti-minus"></i></span>';
    }

    options.forEach((option) => {
      if (!option.text || !option.value) {
        return false;
      }

      output = output.replace(option.value, option.text);
    });

    return output;
  }

  formSubmit(data) {
    const isSuccess = data.code === 200 && data.status === 'success';
    if (!isSuccess || !data.data) {
      return false;
    }

    this.createTrow(data.data);
    this.updateStateValue();

    this.modal.close();
  }

  updateStateValue() {
    const data = [];

    this.tbody.querySelectorAll('tr').forEach((tr) => {
      const obj = {};

      tr.querySelectorAll('td').forEach((td) => {
        if (!td.hasAttribute('data-name')) {
          return false;
        }

        obj[td.name] = td.value;
      });

      data.push(obj);
    });

    this.node.value = JSON.stringify(data);
    this.stateValue = data;

    this.node.dispatchEvent(new CustomEvent('change', { bubbles: true }));

    return true;
  }
}

export default DataTable;
