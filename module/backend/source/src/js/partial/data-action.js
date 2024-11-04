import DataAction from '@/js/util/data-action';

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-action]').forEach((actionNode) => {
    const dataAction = new DataAction(actionNode, window.Engine);

    actionNode.dataAction = dataAction;
  });
});
