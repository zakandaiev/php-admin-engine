document.addEventListener('DOMContentLoaded', () => {
  document.addEventListener('click', (event) => {
    const trigger = event.target.closest('[data-popover]');

    document.querySelectorAll('.popover').forEach((popover) => popover.remove());

    if (!trigger) {
      return false;
    }

    const triggerRect = trigger.getBoundingClientRect();
    const offset = 15;

    const placement = trigger.getAttribute('data-popover') || 'top';
    const title = trigger.getAttribute('data-title');
    const content = trigger.getAttribute('data-content');

    const popover = document.createElement('div');
    popover.classList.add('popover');
    popover.classList.add(`popover_${placement}`);

    const popoverHeader = document.createElement('div');
    popoverHeader.classList.add('popover__header');
    popoverHeader.textContent = title;

    const popoverBody = document.createElement('div');
    popoverBody.classList.add('popover__body');
    popoverBody.textContent = content;

    popover.appendChild(popoverHeader);
    popover.appendChild(popoverBody);

    document.body.appendChild(popover);

    const popoverRect = popover.getBoundingClientRect();

    setPopoverPosition();

    function setPopoverPosition() {
      let top = triggerRect.top - popoverRect.height - offset;
      let left = triggerRect.left + (triggerRect.width / 2) - (popoverRect.width / 2);

      popover.style.top = `${top}px`;
      popover.style.left = `${left}px`;

      if (placement === 'bottom') {
        top = triggerRect.bottom + offset;
        left = triggerRect.left + (triggerRect.width / 2) - (popoverRect.width / 2);

        popover.style.top = `${top}px`;
        popover.style.left = `${left}px`;
      } else if (placement === 'left') {
        top = triggerRect.top + (triggerRect.height / 2) - (popoverRect.height / 2);
        left = triggerRect.left - popoverRect.width - offset;

        popover.style.top = `${top}px`;
        popover.style.left = `${left}px`;
      } else if (placement === 'right') {
        top = triggerRect.top + (triggerRect.height / 2) - (popoverRect.height / 2);
        left = triggerRect.right + offset;

        popover.style.top = `${top}px`;
        popover.style.left = `${left}px`;
      }
    }
  });
});
