'use strict';
{
  // 各種記録の削除ボタン
  const deletes = document.querySelectorAll('.delete');
  deletes.forEach(span => {
    span.addEventListener('click', () => {
      if(!confirm('削除しますか？')) {
        return;
      }
      span.parentNode.submit();
    });
  });

// モーダルウィンドウ
  const open = document.querySelectorAll('.open');
  const mask = document.querySelector('.mask');
  const modal = document.querySelectorAll('.modal');
  const close = document.querySelectorAll('.close');

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

  // タイトルヘッダーホバーメニュー
  const titleheaderitem = document.querySelectorAll('.title-header-item');
  const titleheadersubmenu = document.querySelectorAll('.title-header-submenu');

  for (let i = 0; i < titleheaderitem.length; i++) {
    titleheaderitem[i].addEventListener('mouseover', function () {
      titleheadersubmenu[i].classList.add('showtitleheadersubmenu');
      titleheadersubmenu[i].classList.remove('hidden');
    });
    titleheaderitem[i].addEventListener('mouseout', function () {
      titleheadersubmenu[i].classList.remove('showtitleheadersubmenu');
      titleheadersubmenu[i].classList.add('hidden');
    });
  }

  // ヘッダー現在のページ
  let headericon = document.getElementsByClassName('headerhref');
    for (let i = 0; i < headericon.length; i++) {
      if(location.href.includes(headericon[i].href)) {
          headericon[i].parentNode.classList.add('current');
        }
    }

  // ハンバーガーメニュー

  const overlayopen = document.querySelector('.sp-menu-open')
  const overlayclose = document.querySelector('.sp-menu-close')
  const overlay = document.querySelector('.sp-menu-overlay')

  overlayopen.addEventListener('click', () => {
    overlay.classList.remove('overlay-close');
    overlay.classList.add('ovelay-open');
    overlayopen.classList.add('overlay-close');
    overlayclose.classList.remove('overlay-close');
  });

  overlayclose.addEventListener('click', () => {
    overlay.classList.add('overlay-close');
    overlay.classList.remove('ovelay-open');
    overlayclose.classList.add('overlay-close');
    overlayopen.classList.remove('overlay-close');
  });

}