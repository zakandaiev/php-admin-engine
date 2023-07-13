const DATA_THEME = {
	body_attribute_key: 'data-theme',
	storage_key: 'data-theme',
	value_default: 'light',
  value_dark: 'dark',

  getTheme: () => localStorage.getItem(DATA_THEME.storage_key) || document.documentElement.getAttribute(DATA_THEME.body_attribute_key),

  setTheme: (theme = null, storage = true) => {
    if (theme !== DATA_THEME.value_default && theme !== DATA_THEME.value_dark) {
      theme = DATA_THEME.value_default;
    }

    document.documentElement.setAttribute(DATA_THEME.body_attribute_key, theme);

    if (storage) {
      localStorage.setItem(DATA_THEME.storage_key, theme);
    }

    return true;
  },

  toggleTheme: () => {
    let theme = DATA_THEME.getTheme();

    if (theme === DATA_THEME.value_default) {
      theme = DATA_THEME.value_dark;
    }
    else {
      theme = DATA_THEME.value_default;
    }

    DATA_THEME.setTheme(theme);

    return true;
  }
};

const initial_theme = DATA_THEME.getTheme();
if (initial_theme) {
  DATA_THEME.setTheme(initial_theme);
}
else {
  let theme = DATA_THEME.value_default;

  if (window.matchMedia) {
    theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? DATA_THEME.value_dark : DATA_THEME.value_default;

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
      const t = event.matches ? DATA_THEME.value_dark : DATA_THEME.value_default;
      DATA_THEME.setTheme(t, false);
    });
  }

  DATA_THEME.setTheme(theme, false);
}

document.addEventListener('click', event => {
  const theme_switcher = event.target.closest('[data-theme-set]');
  const theme_toggler = event.target.closest('[data-theme-toggle]');

	if(!theme_switcher && !theme_toggler) {
		return false;
	}

	event.preventDefault();

  const theme = theme_switcher.getAttribute('data-theme-set');

  if (theme_switcher) {
    DATA_THEME.setTheme(theme);
  }
  else if (theme_toggler) {
    DATA_THEME.toggleTheme();
  }
});
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiIiwic291cmNlcyI6WyJkYXRhLXRoZW1lLmpzIl0sInNvdXJjZXNDb250ZW50IjpbImNvbnN0IERBVEFfVEhFTUUgPSB7XHJcblx0Ym9keV9hdHRyaWJ1dGVfa2V5OiAnZGF0YS10aGVtZScsXHJcblx0c3RvcmFnZV9rZXk6ICdkYXRhLXRoZW1lJyxcclxuXHR2YWx1ZV9kZWZhdWx0OiAnbGlnaHQnLFxyXG4gIHZhbHVlX2Rhcms6ICdkYXJrJyxcclxuXHJcbiAgZ2V0VGhlbWU6ICgpID0+IGxvY2FsU3RvcmFnZS5nZXRJdGVtKERBVEFfVEhFTUUuc3RvcmFnZV9rZXkpIHx8IGRvY3VtZW50LmRvY3VtZW50RWxlbWVudC5nZXRBdHRyaWJ1dGUoREFUQV9USEVNRS5ib2R5X2F0dHJpYnV0ZV9rZXkpLFxyXG5cclxuICBzZXRUaGVtZTogKHRoZW1lID0gbnVsbCwgc3RvcmFnZSA9IHRydWUpID0+IHtcclxuICAgIGlmICh0aGVtZSAhPT0gREFUQV9USEVNRS52YWx1ZV9kZWZhdWx0ICYmIHRoZW1lICE9PSBEQVRBX1RIRU1FLnZhbHVlX2RhcmspIHtcclxuICAgICAgdGhlbWUgPSBEQVRBX1RIRU1FLnZhbHVlX2RlZmF1bHQ7XHJcbiAgICB9XHJcblxyXG4gICAgZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LnNldEF0dHJpYnV0ZShEQVRBX1RIRU1FLmJvZHlfYXR0cmlidXRlX2tleSwgdGhlbWUpO1xyXG5cclxuICAgIGlmIChzdG9yYWdlKSB7XHJcbiAgICAgIGxvY2FsU3RvcmFnZS5zZXRJdGVtKERBVEFfVEhFTUUuc3RvcmFnZV9rZXksIHRoZW1lKTtcclxuICAgIH1cclxuXHJcbiAgICByZXR1cm4gdHJ1ZTtcclxuICB9LFxyXG5cclxuICB0b2dnbGVUaGVtZTogKCkgPT4ge1xyXG4gICAgbGV0IHRoZW1lID0gREFUQV9USEVNRS5nZXRUaGVtZSgpO1xyXG5cclxuICAgIGlmICh0aGVtZSA9PT0gREFUQV9USEVNRS52YWx1ZV9kZWZhdWx0KSB7XHJcbiAgICAgIHRoZW1lID0gREFUQV9USEVNRS52YWx1ZV9kYXJrO1xyXG4gICAgfVxyXG4gICAgZWxzZSB7XHJcbiAgICAgIHRoZW1lID0gREFUQV9USEVNRS52YWx1ZV9kZWZhdWx0O1xyXG4gICAgfVxyXG5cclxuICAgIERBVEFfVEhFTUUuc2V0VGhlbWUodGhlbWUpO1xyXG5cclxuICAgIHJldHVybiB0cnVlO1xyXG4gIH1cclxufTtcclxuXHJcbmNvbnN0IGluaXRpYWxfdGhlbWUgPSBEQVRBX1RIRU1FLmdldFRoZW1lKCk7XHJcbmlmIChpbml0aWFsX3RoZW1lKSB7XHJcbiAgREFUQV9USEVNRS5zZXRUaGVtZShpbml0aWFsX3RoZW1lKTtcclxufVxyXG5lbHNlIHtcclxuICBsZXQgdGhlbWUgPSBEQVRBX1RIRU1FLnZhbHVlX2RlZmF1bHQ7XHJcblxyXG4gIGlmICh3aW5kb3cubWF0Y2hNZWRpYSkge1xyXG4gICAgdGhlbWUgPSB3aW5kb3cubWF0Y2hNZWRpYSgnKHByZWZlcnMtY29sb3Itc2NoZW1lOiBkYXJrKScpLm1hdGNoZXMgPyBEQVRBX1RIRU1FLnZhbHVlX2RhcmsgOiBEQVRBX1RIRU1FLnZhbHVlX2RlZmF1bHQ7XHJcblxyXG4gICAgd2luZG93Lm1hdGNoTWVkaWEoJyhwcmVmZXJzLWNvbG9yLXNjaGVtZTogZGFyayknKS5hZGRFdmVudExpc3RlbmVyKCdjaGFuZ2UnLCBldmVudCA9PiB7XHJcbiAgICAgIGNvbnN0IHQgPSBldmVudC5tYXRjaGVzID8gREFUQV9USEVNRS52YWx1ZV9kYXJrIDogREFUQV9USEVNRS52YWx1ZV9kZWZhdWx0O1xyXG4gICAgICBEQVRBX1RIRU1FLnNldFRoZW1lKHQsIGZhbHNlKTtcclxuICAgIH0pO1xyXG4gIH1cclxuXHJcbiAgREFUQV9USEVNRS5zZXRUaGVtZSh0aGVtZSwgZmFsc2UpO1xyXG59XHJcblxyXG5kb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGV2ZW50ID0+IHtcclxuICBjb25zdCB0aGVtZV9zd2l0Y2hlciA9IGV2ZW50LnRhcmdldC5jbG9zZXN0KCdbZGF0YS10aGVtZS1zZXRdJyk7XHJcbiAgY29uc3QgdGhlbWVfdG9nZ2xlciA9IGV2ZW50LnRhcmdldC5jbG9zZXN0KCdbZGF0YS10aGVtZS10b2dnbGVdJyk7XHJcblxyXG5cdGlmKCF0aGVtZV9zd2l0Y2hlciAmJiAhdGhlbWVfdG9nZ2xlcikge1xyXG5cdFx0cmV0dXJuIGZhbHNlO1xyXG5cdH1cclxuXHJcblx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcclxuXHJcbiAgY29uc3QgdGhlbWUgPSB0aGVtZV9zd2l0Y2hlci5nZXRBdHRyaWJ1dGUoJ2RhdGEtdGhlbWUtc2V0Jyk7XHJcblxyXG4gIGlmICh0aGVtZV9zd2l0Y2hlcikge1xyXG4gICAgREFUQV9USEVNRS5zZXRUaGVtZSh0aGVtZSk7XHJcbiAgfVxyXG4gIGVsc2UgaWYgKHRoZW1lX3RvZ2dsZXIpIHtcclxuICAgIERBVEFfVEhFTUUudG9nZ2xlVGhlbWUoKTtcclxuICB9XHJcbn0pO1xyXG4iXSwiZmlsZSI6ImRhdGEtdGhlbWUuanMifQ==
