import { Fancybox } from '@fancyapps/ui';

document.addEventListener('DOMContentLoaded', () => {
  let options = {};

  if (window?.Engine?.translation?.fancybox) {
    options = {
      ...options,
      l10n: window.Engine.translation.fancybox,
    };
  }

  Fancybox.bind('[data-fancybox]', options);
});
