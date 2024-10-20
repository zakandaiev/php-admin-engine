import Form from '@/js/util/form';

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('form').forEach((formNode) => {
    const form = new Form(formNode, window.Engine);

    formNode.form = form;
  });
});
