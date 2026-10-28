'use strict';
function brContentFields() {
  const select = document.querySelector('#br-kind');
  if (!select) return;
  const update = () => document.querySelectorAll('[data-br-fields]').forEach(group => {
    group.hidden = group.dataset.brFields !== select.value;
    group.querySelectorAll('input').forEach(input => { input.disabled = group.hidden; });
  });
  select.addEventListener('change', update);
  update();
}
if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', brContentFields);
else brContentFields();
