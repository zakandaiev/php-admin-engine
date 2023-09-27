// TEXTAREA AUTOHEIGHT
document.querySelectorAll('textarea').forEach(t => {
	const height = t.scrollHeight;

	t.style.height = height + 'px';
	t.initial_height = height;
});

document.addEventListener('input', event => {
	const element = event.target;

	if (element.tagName === 'TEXTAREA') {
		if ((element.initial_height || 0) > element.scrollHeight) {
			return false;
		}

		element.style.height = 0;
		element.style.height = element.scrollHeight + 'px';
	}
});
