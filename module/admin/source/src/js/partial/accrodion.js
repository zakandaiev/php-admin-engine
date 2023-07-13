document.addEventListener('click', event => {
	const accordion = event.target.closest('.accordion');

	if (!accordion) {
		return false;
	}

	const header = accordion.querySelector('.accordion__header');
	const body = accordion.querySelector('.accordion__body');

	if (!header || !body) {
		return false;
	}

	event.preventDefault();

	const is_collapse = (accordion.parentElement && (accordion.parentElement.hasAttribute('data-collapse') || accordion.parentElement.getAttribute('data-collapse') == true));
	if (is_collapse) {
		accordion.parentElement.querySelectorAll('.accordion').forEach(a => {
			if(a === accordion) {
				return false;
			}

			a.classList.remove('active');

			const b = a.querySelector('.accordion__body');
			if(b) {
				b.style.height = '0px';
			}
		});
	}

	const body_height = body.scrollHeight;
	if (accordion.classList.contains('active')) {
		body.style.height = '0px';
		accordion.classList.remove('active');
	}
	else {
		body.style.height = `${body_height}px`;
		accordion.classList.add('active');
	}
});
