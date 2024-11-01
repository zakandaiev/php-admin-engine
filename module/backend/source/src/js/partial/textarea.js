document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('textarea').forEach((t) => {
    const height = t.scrollHeight;

    t.initialHeight = height;

    t.style.height = `${height}px`;
    t.style.overflow = height > 300 ? 'hidden auto' : 'hidden';
  });

  document.addEventListener('input', (event) => {
    const element = event.target;

    if (element.tagName === 'TEXTAREA') {
      if ((element.initialHeight || 0) > element.scrollHeight) {
        return false;
      }

      element.style.height = 0;
      element.style.height = `${element.scrollHeight}px`;
      element.style.overflow = element.scrollHeight > 300 ? 'hidden auto' : 'hidden';
    }
  });
});
