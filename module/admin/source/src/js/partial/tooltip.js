// TOOLTIP
document.addEventListener('mouseover', event => {
	const trigger = event.target.closest('[data-tooltip]');

	if (!trigger) {
		return false;
	}

	const trigger_rect = trigger.getBoundingClientRect();
	const offset = 10;

	const placement = trigger.getAttribute('data-tooltip') || 'top';
	const content = trigger.getAttribute('title');

	const tooltip = document.createElement('div');
	tooltip.classList.add('tooltip');
	tooltip.classList.add(`tooltip_${placement}`);
	tooltip.textContent = content;

	trigger.title_backup = content;
	trigger.removeAttribute('title');
	trigger.tooltip = tooltip;

	document.body.appendChild(tooltip);

	const tooltip_rect = tooltip.getBoundingClientRect();

	setTooltipPosition();

	function setTooltipPosition() {
		let top = trigger_rect.top - tooltip_rect.height - offset;
		let left = trigger_rect.left + (trigger_rect.width / 2) - (tooltip_rect.width / 2);

		tooltip.style.top = top + 'px';
		tooltip.style.left = left + 'px';

		switch (placement) {
			case 'left': {
				top = trigger_rect.top + (trigger_rect.height / 2) - (tooltip_rect.height / 2);
				left = trigger_rect.left - tooltip_rect.width - offset;

				tooltip.style.top = top + 'px';
				tooltip.style.left = left + 'px';

				break;
			}
			case 'right': {
				top = trigger_rect.top + (trigger_rect.height / 2) - (tooltip_rect.height / 2);
				left = trigger_rect.right + offset;

				tooltip.style.top = top + 'px';
				tooltip.style.left = left + 'px';

				break;
			}
			case 'bottom': {
				top = trigger_rect.bottom + offset;
				left = trigger_rect.left + (trigger_rect.width / 2) - (tooltip_rect.width / 2);

				tooltip.style.top = top + 'px';
				tooltip.style.left = left + 'px';

				break;
			}
		}
	}
});

document.addEventListener('mouseout', event => {
	const trigger = event.target.closest('[data-tooltip]');

	if (!trigger) {
		return false;
	}

	const tooltip = trigger.tooltip;

	if (!tooltip) {
		return false;
	}

	trigger.setAttribute('title', trigger.title_backup);
	trigger.tooltip = null;

	tooltip.remove();
});
