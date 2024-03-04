class DataAction {
	constructor(node, options = {}) {
		this.node = node;
		this.options = options;

		this.action = this.node.getAttribute('data-action');
		this.method = this.node.getAttribute('data-method') || 'POST';
		if (this.method.toLowerCase() === 'get') {
			this.method = 'POST';
		}
		this.data_event = this.node.getAttribute('data-event') || 'click';
		this.data_confirm = this.node.getAttribute('data-confirm');
		this.data_fields = this.node.getAttribute('data-fields') || '';
		this.data_unset_null = this.node.hasAttribute('data-unset-null') ? true : false;
		this.data_class = this.node.getAttribute('data-class') || 'submit';
		this.data_class_target = this.node.getAttribute('data-class-target');
		this.data_redirect = this.node.getAttribute('data-redirect');
		this.data_increment = this.node.getAttribute('data-increment');
		this.data_decrement = this.node.getAttribute('data-decrement');
		this.data_remove = this.node.getAttribute('data-remove');
		this.data_message = this.node.getAttribute('data-message');
		this.toast = console.log;
		if (typeof toast !== 'undefined') {
			this.toast = toast;
		}
		this.confirmation = confirm;
		if (typeof confirmation !== 'undefined') {
			this.confirmation = confirmation;
		}
		this.fade_out = (e) => e ? e.remove() : false;
		if (typeof fadeOut !== 'undefined') {
			this.fade_out = fadeOut;
		}
		this.api = {
			delay_ms: this.node.getAttribute('data-delay')
				? parseInt(this.node.getAttribute('data-delay'))
				: this.options?.api?.delay_ms || 500,

			timeout_ms: this.node.getAttribute('data-timeout')
				? parseInt(this.node.getAttribute('data-timeout'))
				: this.options?.api?.timeout_ms || 15000
		};

		if (this.action) {
			this.initialize();
		}
	}

	initialize() {
		this.node.addEventListener(this.data_event, async (event) => {
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
					method: this.method,
					body: this.getFormData()
				},
				this.api.timeout_ms,
				this.api.delay_ms
			);

			if (data.status === 'success') {
				this.successRedirect(data);
				this.successCounters();
				this.successRemoveNodes();
				this.toast(data.status, this.data_message || data.message);
			}
			else {
				this.toast(data.status, data.message);
			}

			this.enableNodes();
		});
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
		let data = new FormData();
		const fields = this.data_fields.split('|') || [];

		fields.forEach(field => {
			const [key, value] = field.split(':');

			if (!key) {
				return false;
			}

			data.set(key, value || null);
		});

		if (this.options?.csrf) {
			data.set(this.options.csrf.key, this.options.csrf.token);
		}

		if (this.data_unset_null) {
			this.unsetNullFormData(data);
		}

		return data;
	}

	unsetNullFormData(data) {
		for (const pair of data.entries()) {
			const name = pair[0];
			const value = pair[1];

			if (!value || !value.length) {
				data.delete(name);
			}
		}
	}

	disableNodes() {
		// DISABLE SELF
		this.node.setAttribute('disabled', 'disabled');
		this.node.classList.add('submit');

		// ADD CLASS TO TARGETS
		if (this.data_class && this.data_class_target) {
			document.querySelectorAll(this.data_class_target).forEach(target => {
				target.classList.add(this.data_class);
			});
		}
		else if (this.data_class) {
			this.node.classList.add(this.data_class);
		}

		return true;
	}

	enableNodes() {
		// ENABLE SELF
		this.node.removeAttribute('disabled', 'disabled');
		this.node.classList.remove('submit');

		// REMOVE CLASS FROM TARGETS
		if (this.data_class && this.data_class_target) {
			document.querySelectorAll(this.data_class_target).forEach(target => {
				target.classList.remove(this.data_class);
			});
		}
		else if (this.data_class) {
			this.node.classList.remove(this.data_class);
		}

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
	}

	successCounters() {
		if (this.data_increment) {
			document.querySelectorAll(this.data_increment).forEach(target => {
				const target_value = parseInt(target.textContent);
				target.textContent = target_value + 1;
			});
		}

		if (this.data_decrement) {
			document.querySelectorAll(this.data_decrement).forEach(target => {
				const target_value = parseInt(target.textContent);
				target.textContent = target_value - 1;
			});
		}
	}

	successRemoveNodes() {
		if (!this.data_remove) {
			return false;
		}

		if (this.data_remove === 'this') {
			this.fade_out(this.node);

			return true;
		}

		if (this.data_remove === 'trow') {
			const trow = this.node.closest('tr');

			this.fade_out(trow, (tr) => {
				const tbody = tr.parentNode;

				tr.remove();

				if (tbody.childElementCount === 0) {
					window.location.reload();
				}
			});

			return true;
		}

		document.querySelectorAll(this.data_remove).forEach(target => {
			this.fade_out(target);
		});

		return true;
	}
}

document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('[data-action]').forEach(element => {
		new DataAction(element, ENGINE);
	});
});
