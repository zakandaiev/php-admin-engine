document.querySelectorAll('select').forEach(select => {
	if (select.hasAttribute('data-native')) {
		return false;
	}

	const s = new SlimSelect({
		select: select,
		settings: {
			alwaysOpen: select.hasAttribute('data-always-open') ? true : false,
			placeholderText: select.hasAttribute('data-placeholder') ? select.getAttribute('data-placeholder') : null,
			allowDeselect: select.querySelector('option[data-placeholder]') ? true : false,
			minSelected: select.hasAttribute('data-min') ? select.getAttribute('data-min') : null,
    	maxSelected: select.hasAttribute('data-max') ? select.getAttribute('data-max') : null,
			showSearch: (select.querySelectorAll('option').length > 10 || select.hasAttribute('data-show-search') || select.hasAttribute('data-addable')) ? true : false,
			searchText: select.hasAttribute('data-placeholder-search-text') ? select.getAttribute('data-placeholder-search-text') : null,
			searchPlaceholder: select.hasAttribute('data-placeholder-search') ? select.getAttribute('data-placeholder-search') : null,
			searchHighlight: true,
			closeOnSelect: select.multiple ? false : true,
			hideSelected: select.hasAttribute('data-hide-selected') ? (select.getAttribute('data-hide-selected') == 'true' ? true : false) : (select.multiple ? true : false),
			maxValuesShown: select.hasAttribute('data-max-values') ? select.getAttribute('data-max-values') : 100,
			maxValuesMessage: select.hasAttribute('data-max-values-text') ? select.getAttribute('data-max-values-text') : null
		},
		events: {
			addable: value => {
				if (!select.hasAttribute('data-addable') && !select.instance.events.addable) {
					return false;
				}

				let val = value;

				switch (select.getAttribute('data-addable')) {
					case 'tag': {
						val = value.replaceAll(/[^\p{L}\d ]+/giu, '');
						val = getSlug(val).toLowerCase();
						break;
					}
				}

				val = val.replaceAll(/[\s]+/g, ' ').trim();

				return val;
			}
		}
	});

	select.instance = s;
});
