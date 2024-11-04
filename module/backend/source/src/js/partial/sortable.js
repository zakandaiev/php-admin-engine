import Sortable from 'sortablejs';

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-sortable]').forEach((element) => {
    const sortable = new Sortable(element, {
      multiDrag: element.hasAttribute('data-multi') ? true : false,
      group: element.getAttribute('data-multi') || false,
      handle: element.getAttribute('data-sortable') || false,
      filter: element.getAttribute('data-disabled') || '.sortable__disabled',
      ghostClass: element.getAttribute('data-ghost') || 'sortable__ghost',
      fallbackOnBody: false,
      swapThreshold: 0.5,
      animation: 150,
      onEnd: (event) => {
        if (element.onEnd && element.onEnd instanceof Function) {
          element.onEnd();
        }

        if (element.hasAttribute('data-callback') && window[element.getAttribute('data-callback')]) {
          window[element.getAttribute('data-callback')](event);
        }
      },
    });

    element.sortable = sortable;
  });
});
