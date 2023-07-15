document.addEventListener('click', event => {
  const tab = event.target.closest('.tab');
  const tab_nav = event.target.closest('.tab__nav-item');

  if (!tab || !tab_nav) {
    return false;
  }

	event.preventDefault();

  const hash = tab_nav.hash;
  const id = hash.substring(1);

	if (tab.hasAttribute('data-save')) {
		if (history.pushState) {
			window.history.pushState(null, null, hash);
		}
		else {
			window.location.hash = hash;
		}
	}

  tab.querySelectorAll('.tab__nav-item').forEach(nav => {
    if (nav.hash === hash) {
      nav.classList.add('active');
    }
		else {
      nav.classList.remove('active');
    }
  });

  tab.querySelectorAll('.tab__body').forEach(body => {
    if (body.id === id) {
      body.classList.add('active');
    }
		else {
      body.classList.remove('active');
    }
  });
});

if (window.location.hash) {
	document.querySelectorAll('.tab__nav-item,.tab__body').forEach(item => {
		if (!item.closest('.tab').hasAttribute('data-save')) {
			return false;
		}

		const hash = window.location.hash;
		const id = hash.substring(1);

		if (item.classList.contains('tab__nav-item')) {
			if (item.hash === hash) {
				item.classList.add('active');
			}
			else {
				item.classList.remove('active');
			}
		}
		else if (item.classList.contains('tab__body')) {
			if (item.id === id) {
				item.classList.add('active');
			}
			else {
				item.classList.remove('active');
			}
		}

	});
}
