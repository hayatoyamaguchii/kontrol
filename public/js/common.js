'use strict';
{
  const deletes = document.querySelectorAll('.delete');
  deletes.forEach(span => {
    span.addEventListener('click', () => {
      if(!confirm('削除しますか？')) {
        return;
      }
      span.parentNode.submit();
    });
  });
}