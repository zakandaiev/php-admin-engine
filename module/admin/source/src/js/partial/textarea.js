// TEXTAREA AUTOHEIGHT
document.querySelectorAll('textarea').forEach(t => {
	const height = t.scrollHeight;

	t.style.height = height + 'px';
	t.setAttribute('data-initial-height', height);
});

document.addEventListener('input', event => {
	const element = event.target;

	if (element.tagName === 'TEXTAREA') {
		// AUTOHEIGHT
		const initial_height = element.getAttribute('data-initial-height') || 0;
		const height = element.scrollHeight;

		if (parseInt(initial_height) > height) {
			return false;
		}

		element.style.height = 0;
		element.style.height = height + 'px';
	}
});
