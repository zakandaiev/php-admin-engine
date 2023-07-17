// VALIDATION
document.addEventListener('click', event => {
  const form = event.target.closest('form[data-validate]');
  const submit = event.target.closest('[type="submit"]');

  if (!form || !submit) {
    return false;
  }

  form.querySelectorAll(':invalid').forEach(item => {
    item.classList.remove('valid');
    item.classList.add('invalid');
  });
  form.querySelectorAll(':valid').forEach(item => {
    item.classList.remove('invalid');
    item.classList.add('valid');
  });
});

['input', 'change'].forEach(e => {
  document.addEventListener(e, event => {
    const form = event.target.closest('form[data-validate]');
    const item = event.target;

    if (!form) {
      return false;
    }

    if (!item.validity.valid) {
      item.classList.remove('valid');
      item.classList.add('invalid');
    }
    else {
      item.classList.remove('invalid');
      item.classList.add('valid');
    }
  });
});

// INSERT LOADER
document.querySelectorAll('form').forEach(form => {
  const loader = document.createElement('span');
  loader.classList.add('loader');
  form.appendChild(loader);
});

// SUBMIT EVENT
document.addEventListener('submit', event => {
  const form = event.target;

  if (form.hasAttribute('data-native')) {
    return false;
  }

  event.preventDefault();

  alert();
});