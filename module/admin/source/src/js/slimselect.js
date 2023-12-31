@@include("../../node_modules/slim-select/dist/slimselect.min.js")

document.addEventListener('DOMContentLoaded', () => {

	document.querySelectorAll('select').forEach(select => {
		if (select.hasAttribute('data-native')) {
			return false;
		}

		const wrapper = document.createElement('div');
		wrapper.classList.add('select', 'select_custom');

		select.after(wrapper);

		wrapper.appendChild(select);

		const events = {
			afterChange: () => select.dispatchEvent(new CustomEvent('change', { bubbles: true }))
		};

		if (select.hasAttribute('data-addable')) {
			events.addable = (value) => {
				const addable_type = select.getAttribute('data-addable');
				const is_regex = typeof addable_type === 'string' && addable_type[0] === '/';
				let is_regex_test = false;
				try {
					const regex_match = addable_type.match(new RegExp('^/(.*?)/([a-z]*)$'));
					const regex = new RegExp(regex_match[1], regex_match[2]);

					if (regex.test(value)) {
						is_regex_test = true;
					}
				}
				catch (e) { }

				if (addable_type === 'slug') {
					value = value.replaceAll(/[^\p{L}\d ]+/giu, '');
					value = getSlug(value).toLowerCase();
				}
				else if (is_regex && !is_regex_test) {
					return false;
				}

				value = value.replaceAll(/[\s]+/g, ' ').trim();

				return value;
			}
		}

		const s = new SlimSelect({
			select: select,
			settings: {
				contentLocation: wrapper,
				contentPosition: 'relative',
				alwaysOpen: select.hasAttribute('data-always-open') ? true : false,
				placeholderText: select.hasAttribute('data-placeholder') ? select.getAttribute('data-placeholder') : null,
				allowDeselect: select.querySelector('option[data-placeholder]') ? true : false,
				minSelected: select.hasAttribute('data-min') ? select.getAttribute('data-min') : null,
				maxSelected: select.hasAttribute('data-max') ? select.getAttribute('data-max') : null,
				showSearch: (select.querySelectorAll('option').length > 10 || select.hasAttribute('data-show-search') || select.hasAttribute('data-addable')) && select.getAttribute('data-show-search') != false ? true : false,
				searchText: select.hasAttribute('data-placeholder-search-text') ? select.getAttribute('data-placeholder-search-text') : null,
				searchPlaceholder: select.hasAttribute('data-placeholder-search') ? select.getAttribute('data-placeholder-search') : null,
				searchHighlight: select.hasAttribute('data-search-highlight') ? true : false,
				closeOnSelect: select.multiple ? false : true,
				hideSelected: select.hasAttribute('data-hide-selected') ? (select.getAttribute('data-hide-selected') == 'true' ? true : false) : (select.multiple ? true : false),
				maxValuesShown: select.hasAttribute('data-max-values') ? select.getAttribute('data-max-values') : 100,
				maxValuesMessage: select.hasAttribute('data-max-values-text') ? select.getAttribute('data-max-values-text') : null
			},
			events
		});

		select.instance = s;
		select.removeAttribute('style');
		wrapper.instance = s;
	});

});
