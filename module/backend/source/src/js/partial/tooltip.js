document.addEventListener('DOMContentLoaded', () => {
  document.addEventListener('mouseover', (event) => {
    const trigger = event.target.closest('[data-tooltip]');

    if (!trigger) {
      return false;
    }

    const triggerRect = trigger.getBoundingClientRect();
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

    const tooltipRect = tooltip.getBoundingClientRect();

    setTooltipPosition();

    function setTooltipPosition() {
      let top = triggerRect.top - tooltipRect.height - offset;
      let left = triggerRect.left + (triggerRect.width / 2) - (tooltipRect.width / 2);

      tooltip.style.top = `${top}px`;
      tooltip.style.left = `${left}px`;

      if (placement === 'bottom') {
        top = triggerRect.bottom + offset;
        left = triggerRect.left + (triggerRect.width / 2) - (tooltipRect.width / 2);

        tooltip.style.top = `${top}px`;
        tooltip.style.left = `${left}px`;
      } else if (placement === 'left') {
        top = triggerRect.top + (triggerRect.height / 2) - (tooltipRect.height / 2);
        left = triggerRect.left - tooltipRect.width - offset;

        tooltip.style.top = `${top}px`;
        tooltip.style.left = `${left}px`;
      } else if (placement === 'right') {
        top = triggerRect.top + (triggerRect.height / 2) - (tooltipRect.height / 2);
        left = triggerRect.right + offset;

        tooltip.style.top = `${top}px`;
        tooltip.style.left = `${left}px`;
      }
    }
  });

  document.addEventListener('mouseout', (event) => {
    const trigger = event.target.closest('[data-tooltip]');

    if (!trigger) {
      return false;
    }

    const { tooltip } = trigger;

    if (!tooltip) {
      return false;
    }

    trigger.setAttribute('title', trigger.title_backup);
    trigger.tooltip = null;

    tooltip.remove();
  });
});
