import Sortable from 'sortablejs';

document.addEventListener('DOMContentLoaded', () => {
  function makeSortable(element) {
    const sortable = new Sortable(element, {
      multiDrag: element.hasAttribute('data-multi') ? true : false,
      group: element.hasAttribute('data-multi') ? element.getAttribute('data-multi') : false,
      handle: element.hasAttribute('data-handle') ? element.getAttribute('data-handle') : false,
      filter: element.hasAttribute('data-disabled') ? element.getAttribute('data-disabled') : '.sortable__disabled',
      ghostClass: element.hasAttribute('data-ghost') ? element.getAttribute('data-ghost') : 'sortable__ghost',
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

    element.instance = sortable;
  }

  document.querySelectorAll('[data-sortable]').forEach((element) => makeSortable(element));
});
