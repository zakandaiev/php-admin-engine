import { Fancybox } from '@fancyapps/ui';

document.addEventListener('DOMContentLoaded', () => {
  let options = {};

  if (typeof Engine !== 'undefined' && Engine.translation && Engine.translation.fancybox) {
    options = {
      ...options,
      l10n: Engine.translation.fancybox,
    };
  }

  Fancybox.bind('[data-fancybox]', options);
});
