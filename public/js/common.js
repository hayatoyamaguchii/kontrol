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

  // const open = document.getElementById('open');
  // const close = document.getElementById('close');
  // const modal = document.getElementById('modal');
  // const mask = document.getElementById('mask');

  // open.addEventListener('click', () => {
  //   modal.classList.remove('hidden');
  //   mask.classList.remove('hidden');
  // });

  // close.addEventListener('click', () => {
  //   modal.classList.add('hidden');
  //   mask.classList.add('hidden');
  // });

  // mask.addEventListener('click', () => {
  //   close.click();
  // });

  const open = document.querySelectorAll('.open');
  console.log(open);

  const mask = document.querySelector('.mask');
  console.log(mask);

  const modal = document.querySelectorAll('.modal');
  console.log(modal);

  const close = document.querySelectorAll('.close');
  console.log(close);

  for (let i = 0; i < open.length; i++) {
    open[i].addEventListener('click', function () {
      // e.preventDefault();
      mask.classList.remove('hidden');
      modal[i].classList.remove('hidden');
    });
  }

  for (let i = 0; i < close.length; i++) {
    close[i].addEventListener('click', function () {
      mask.classList.add('hidden');
      modal[i].classList.add('hidden');
    });
  }
}