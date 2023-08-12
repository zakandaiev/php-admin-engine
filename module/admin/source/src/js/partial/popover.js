// POPOVER
document.addEventListener('click', event => {
	const popover = event.target.closest('[data-popover]');

	if (!popover) {
		document.querySelectorAll('.popover-wrapper.active').forEach(wr => wr.classList.remove('active'));

		return false;
	}

	event.preventDefault();

	const placement = popover.getAttribute('data-popover') || 'top';
	const title = popover.getAttribute('title');
	const content = popover.getAttribute('data-content');

	document.querySelectorAll('.popover-wrapper').forEach(wr => {
		if (wr === popover.parentElement) {
			wr.classList.toggle('active');
		}
		else {
			wr.classList.remove('active');
		}
	});

	if (popover.parentElement.classList.contains('popover-wrapper')) {
		return false;
	}

	const wrapper = document.createElement('div');
	wrapper.classList.add('popover-wrapper', 'active');
	wrapper.style.display = getComputedStyle(popover).getPropertyValue('display');

	const pop = document.createElement('div');
	pop.classList.add('popover', `popover_${placement}`);

	const pop_header = document.createElement('div');
	pop_header.classList.add('popover__header');
	pop_header.textContent = title;

	const pop_body = document.createElement('div');
	pop_body.classList.add('popover__body');
	pop_body.textContent = content;

	pop.appendChild(pop_header);
	pop.appendChild(pop_body);

	popover.parentNode.insertBefore(wrapper, popover);
  wrapper.appendChild(popover);
  wrapper.appendChild(pop);
});
