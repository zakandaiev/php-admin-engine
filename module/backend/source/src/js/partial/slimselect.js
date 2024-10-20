import SlimSelect from 'slim-select';
import { getSlug } from '@/js/util/cyr-to-lat';

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('select').forEach((select) => {
    if (select.hasAttribute('data-native')) {
      return false;
    }

    const wrapper = document.createElement('div');
    wrapper.classList.add('select', 'select_custom');

    select.after(wrapper);

    wrapper.appendChild(select);

    const events = {
      afterChange: () => select.dispatchEvent(new CustomEvent('change', { bubbles: true })),
    };

    if (select.hasAttribute('data-addable')) {
      events.addable = (value) => {
        value = value.replaceAll(/[\s]+/g, ' ').trim();

        const addableType = select.getAttribute('data-addable');

        if (addableType === 'slug') {
          value = value.replaceAll(/[^\p{L}\d ]+/giu, '');
          value = getSlug(value).toLowerCase();

          return value;
        }

        let isRegexTest = false;
        const isRegex = typeof addableType === 'string' && addableType[0] === '/';
        if (isRegex) {
          // eslint-disable-next-line
          const regexMatch = addableType.match(new RegExp('^/(.*?)/([a-z]*)$'));
          const regex = new RegExp(regexMatch[1], regexMatch[2]);

          isRegexTest = regex.test(value);
        }

        if (isRegex && !isRegexTest) {
          return false;
        }

        return value;
      };
    }

    const s = new SlimSelect({
      select,
      settings: {
        contentLocation: wrapper,
        contentPosition: 'relative',
        alwaysOpen: select.hasAttribute('data-always-open') ? true : false,
        allowDeselect: select.querySelector('option[data-placeholder]') ? true : false,
        minSelected: select.hasAttribute('data-min') ? select.getAttribute('data-min') : null,
        maxSelected: select.hasAttribute('data-max') ? select.getAttribute('data-max') : null,
        // eslint-disable-next-line
        showSearch: (select.querySelectorAll('option').length > 10 || select.hasAttribute('data-show-search') || select.hasAttribute('data-addable')) && select.getAttribute('data-show-search') != false ? true : false,
        placeholderText: select.hasAttribute('placeholder') ? select.getAttribute('placeholder') : null,
        searchText: select.hasAttribute('data-placeholder-search-text') ? select.getAttribute('data-placeholder-search-text') : null,
        searchPlaceholder: select.hasAttribute('data-placeholder-search') ? select.getAttribute('data-placeholder-search') : null,
        searchHighlight: select.hasAttribute('data-search-highlight') ? true : false,
        closeOnSelect: select.multiple ? false : true,
        // eslint-disable-next-line
        hideSelected: select.hasAttribute('data-hide-selected') ? (select.getAttribute('data-hide-selected') == 'true' ? true : false) : (select.multiple ? true : false),
        maxValuesShown: select.hasAttribute('data-max-values') ? select.getAttribute('data-max-values') : 100,
        maxValuesMessage: select.hasAttribute('data-max-values-text') ? select.getAttribute('data-max-values-text') : null,
      },
      events,
    });

    select.instance = s;
    select.removeAttribute('style');
    wrapper.instance = s;
  });
});
