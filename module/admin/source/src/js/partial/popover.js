// POPOVER
document.addEventListener('click', event => {
	const trigger = event.target.closest('[data-popover]');

	document.querySelectorAll('.popover').forEach(popover => popover.remove());

	if (!trigger) {
		return false;
	}

	const trigger_rect = trigger.getBoundingClientRect();
	const offset = 15;

	const placement = trigger.getAttribute('data-popover') || 'top';
	const title = trigger.getAttribute('data-title');
	const content = trigger.getAttribute('data-content');

	const popover = document.createElement('div');
	popover.classList.add('popover');
	popover.classList.add(`popover_${placement}`);

	const popover_header = document.createElement('div');
	popover_header.classList.add('popover__header');
	popover_header.textContent = title;

	const popover_body = document.createElement('div');
	popover_body.classList.add('popover__body');
	popover_body.textContent = content;

	popover.appendChild(popover_header);
	popover.appendChild(popover_body);

	document.body.appendChild(popover);

	const popover_rect = popover.getBoundingClientRect();

	setPopoverPosition();

	function setPopoverPosition() {
		let top = trigger_rect.top - popover_rect.height - offset;
		let left = trigger_rect.left + (trigger_rect.width / 2) - (popover_rect.width / 2);

		popover.style.top = top + 'px';
		popover.style.left = left + 'px';

		switch (placement) {
			case 'left': {
				top = trigger_rect.top + (trigger_rect.height / 2) - (popover_rect.height / 2);
				left = trigger_rect.left - popover_rect.width - offset;

				popover.style.top = top + 'px';
				popover.style.left = left + 'px';

				break;
			}
			case 'right': {
				top = trigger_rect.top + (trigger_rect.height / 2) - (popover_rect.height / 2);
				left = trigger_rect.right + offset;

				popover.style.top = top + 'px';
				popover.style.left = left + 'px';

				break;
			}
			case 'bottom': {
				top = trigger_rect.bottom + offset;
				left = trigger_rect.left + (trigger_rect.width / 2) - (popover_rect.width / 2);

				popover.style.top = top + 'px';
				popover.style.left = left + 'px';

				break;
			}
		}
	}
});
