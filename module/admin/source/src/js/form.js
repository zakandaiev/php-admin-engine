const sleep = (ms) => {
  return new Promise(resolve => setTimeout(resolve, ms));
}

async function fetchWithTimeout(resource, options, timeout = 15000) {
  const controller = new AbortController();
  const id = setTimeout(() => controller.abort(), timeout);

  const response = await fetch(resource, {
    ...options,
    signal: controller.signal
  });

  clearTimeout(id);

  return response;
}

class Form {
	constructor(node, options = {}) {
		this.node = node;
		this.options = options;

		this.submit = node.querySelector('[type="submit"]');
		if (!this.submit) {
			this.submit = document.createElement('button');
			this.submit.classList.add('hidden');
			this.node.appendChild(this.submit);
		}
		this.data_submit_native = this.node.hasAttribute('data-submit-native') ? true : false;
		this.data_submit_onchange = this.node.hasAttribute('data-submit-onchange') ? true : false;
		this.data_unset_null = this.node.hasAttribute('data-unset-null') ? true : false;

		if (this.node.hasAttribute('data-native')) {
			return false;
		}

		this.action = this.node.action;
		this.method = this.node.method || 'POST';
		if (this.method.toLowerCase() === 'get') {
			this.method = 'POST';
		}
		this.data_confirm = this.node.getAttribute('data-confirm');
		this.data_reset = this.node.hasAttribute('data-reset') ? true : false;
		this.data_redirect = this.node.getAttribute('data-redirect');
		this.data_message = this.node.getAttribute('data-message');
		this.toast = console.log;
		if (typeof toast !== 'undefined') {
			this.toast = toast;
		}
		this.confirmation = confirm;
		if (typeof confirmation !== 'undefined') {
			this.confirmation = confirmation;
		}
		this.api = {
			delay_ms: this.options?.api?.delay_ms || 1000,
			timeout_ms: this.options?.api?.timeout_ms || 15000
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
			this.node.querySelectorAll(':invalid').forEach(item => {
				item.classList.remove('valid');
				item.classList.add('invalid');
			});
			this.node.querySelectorAll(':valid').forEach(item => {
				item.classList.remove('invalid');
				item.classList.add('valid');
			});
		});

		this.node.querySelectorAll('input, textarea, select').forEach(input => {
			const events = ['input', 'change'];

			events.forEach(e => {
				input.addEventListener(e, () => {
					if (!input.validity.valid) {
						input.classList.remove('valid');
						input.classList.add('invalid');
					}
					else {
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
			this.node.insertAdjacentHTML('beforeend', loader);

			return true;
		}

		return false;
	}

	initSubmit() {
		if (this.data_submit_onchange) {
			this.node.querySelectorAll('[name]').forEach(input => {
				input.onchange = () => {
					this.node.requestSubmit(this.submit);
				};
			});
		}
	}

	listenSubmit() {
		this.node.addEventListener('submit', async (event) => {
			if (this.data_submit_native) {
				this.node.querySelectorAll('[name]').forEach(input => {
					if (input.hasAttribute('data-picker') && input.instance && input.instance.selectedDates) {
						const picker_type = input.getAttribute('data-picker');

						if (!['date', 'datetime', 'month'].includes(picker_type)) {
							return false;
						}

						if (input.hasAttribute('data-multiple') || input.hasAttribute('data-range')) {
							input.value = input.instance.selectedDates.map(d => this.getFormattedDate(picker_type, d)).join(' - ');
						}
						else {
							input.value = input.value.length && input.instance.selectedDates.length ? this.getFormattedDate(picker_type, input.instance.selectedDates[0]) : '';
						}
					}

					if (this.data_unset_null && !input.value.length) {
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

			const data = await this.doRequest();

			if (data.status === 'success') {
				this.successRedirect(data);
				this.successResetForm();
				this.toast(data.status, this.data_message || data.message);
			}
			else {
				this.toast(data.status, data.message);
			}

			this.enableForm();
		});
	}

	async doRequest() {
		const startTime = performance.now();

		const options = {
			method: this.method,
			body: this.getFormData()
		};

		let data = {
			code: null,
			status: null,
			message: null,
			data: null
		};

		try {
			const response = await fetchWithTimeout(this.action, options, this.api.timeout_ms);
			const responseData = await response.json() ?? {};

			data.code = response.status;
			data.status = responseData.status;
			data.message = responseData.message;
			data.data = responseData.data;
		}
		catch (error) {
			data.status = 'error';
			data.message = error;
		}

		const endTime = performance.now();

		const differenceTime = endTime - startTime;

		if (differenceTime < this.api.delay_ms) {
			await sleep(this.api.delay_ms - differenceTime);
		}

		return data;
	}

	async checkConfirmation() {
		if (!this.data_confirm) {
			return true;
		}

		let confirmation = true;

		if (this.confirmation === confirm) {
			confirmation = confirm(this.data_confirm);
		}
		else {
			confirmation = await this.confirmation(this.data_confirm);
		}

		return confirmation;
	}

	getFormData() {
		let data = new FormData(this.node);

		if (this.options?.csrf) {
			data.set(this.options.csrf.key, this.options.csrf.token);
		}

		this.formatDates(data);

		if (this.data_unset_null) {
			this.unsetNullFormData(data);
		}

		return data;
	}

	formatDates(data) {
		for (const pair of data.entries()) {
			const name = pair[0];
			const input = this.node.querySelector(`[name="${name}"]`);

			if (input && input.hasAttribute('data-picker') && input.instance && input.instance.selectedDates) {
				const picker_type = input.getAttribute('data-picker');

				if (!['date','datetime','month'].includes(picker_type)) {
					return false;
				}

				if (input.hasAttribute('data-multiple') || input.hasAttribute('data-range')) {
					data.delete(name);
					input.instance.selectedDates.forEach(d => data.append(name, this.getFormattedDate(picker_type, d)));
				}
				else {
					data.set(
						name,
						input.value.length && input.instance.selectedDates.length ? this.getFormattedDate(picker_type, input.instance.selectedDates[0]) : ''
					);
				}
			}
		}
	}

	unsetNullFormData(data) {
		for (const pair of data.entries()) {
			const name = pair[0];
			const value = pair[1];

			if (!value.length) {
				data.delete(name);
			}
		}
	}

	getFormattedDate(type, date) {
		const d = new Date(date.valueOf());
		d.setMinutes(d.getMinutes() - d.getTimezoneOffset());

		switch(type) {
			case 'date': {
				return d.toJSON().slice(0, 10);
			}
			case 'datetime': {
				return d.toJSON().slice(0, 19).replace('T', ' ');
			}
			case 'month': {
				return d.toJSON().slice(0, 7);
			}
		}

		return false;
	}

	disableForm() {
		this.node.setAttribute('disabled', 'disabled');
		this.node.classList.add('submit');
		this.submit.disabled = true;

		return true;
	}

	enableForm() {
		this.node.removeAttribute('disabled', 'disabled');
		this.node.classList.remove('submit');
		this.submit.disabled = false;

		return true;
	}

	successRedirect(data) {
		if (this.data_redirect) {
			if (this.data_redirect === 'this') {
				document.location.reload();
			}
			else {
				window.location.href = decodeURI(this.data_redirect).replaceAll(/({\w+})/g, data?.data);
			}
		}

		return false;
	}

	successResetForm() {
		if (this.data_reset) {
			this.node.reset();

			return true;
		}

		return false;
	}
}

document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('form').forEach(form => {
		new Form(form, ENGINE);
	});
});
