// TOOLTIP
document.addEventListener('mouseover', async (event) => {
	const tooltip = event.target.closest('[data-tooltip]');

	if (!tooltip) {
		return false;
	}

	const placement = tooltip.getAttribute('data-tooltip') || 'top';
	const content = tooltip.getAttribute('data-title');

	if(tooltip.parentElement.classList.contains('tooltip-wrapper')) {
		return false;
	}

	const wrapper = document.createElement('div');
	wrapper.classList.add('tooltip-wrapper');
	wrapper.style.display = getComputedStyle(tooltip).getPropertyValue('display');

	const tip = document.createElement('span');
	tip.classList.add('tooltip');
	tip.classList.add(`tooltip_${placement}`);
	tip.textContent = content;

	tooltip.parentNode.insertBefore(wrapper, tooltip);
  wrapper.appendChild(tooltip);
  wrapper.appendChild(tip);
});
