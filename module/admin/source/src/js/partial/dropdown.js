// DROPDOWN
document.addEventListener('click', event => {
	const dropdown = event.target.closest('.dropdown');
	const dropdown_item = event.target.closest('.dropdown__item:not(:disabled):not(.disabled)');
	const dropdown_header = event.target.closest('.dropdown__header');
	const dropdown_text = event.target.closest('.dropdown__text');
	const dropdown_separator = event.target.closest('.dropdown__separator');

	if (!dropdown) {
		document.querySelectorAll('.dropdown.active').forEach(dd => dd.classList.remove('active'));

		return false;
	}

	if (dropdown_item && dropdown_item.tagName !== 'A') {
		event.preventDefault();
	}

	document.querySelectorAll('.dropdown').forEach(dd => {
		if (dropdown_header || dropdown_text || dropdown_separator || (dd.hasAttribute('data-keep-open') && dropdown_item)) {
			return false;
		}

		if (dd === dropdown) {
			dd.classList.toggle('active');
		}
		else {
			dd.classList.remove('active');
		}
	});

	if (!dropdown_item) {
		return false;
	}

	dropdown.querySelectorAll('.dropdown__item').forEach(di => {
		if (di === dropdown_item) {
			di.classList.toggle('active');
		}
		else {
			di.classList.remove('active');
		}
	});
});
