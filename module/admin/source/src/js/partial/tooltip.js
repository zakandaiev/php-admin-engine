// TOOLTIP
document.addEventListener('mouseover', event => {
	const tooltip = event.target.closest('[data-tooltip]');

	if (!tooltip) {
		return false;
	}

	event.preventDefault();

	const placement = tooltip.getAttribute('data-tooltip') || 'top';
	const content = tooltip.getAttribute('title');

	if (tooltip.parentElement.classList.contains('tooltip-wrapper')) {
		return false;
	}

	const wrapper = document.createElement('div');
	wrapper.classList.add('tooltip-wrapper');
	wrapper.style.display = getComputedStyle(tooltip).getPropertyValue('display');

	const tip = document.createElement('span');
	tip.classList.add('tooltip', `tooltip_${placement}`);

	const tip_content = document.createElement('span');
	tip_content.classList.add('tooltip__content');
	tip_content.textContent = content;

  tip.appendChild(tip_content);

	tooltip.parentNode.insertBefore(wrapper, tooltip);
  wrapper.appendChild(tooltip);
  wrapper.appendChild(tip);
});
