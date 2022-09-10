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
  // サイドヘッダーホバー（使っていないが、追加した時用
  // const mainmenu = document.querySelectorAll('.mainmenu');
  // const submenu = document.querySelectorAll('.submenu');

  // for (let i = 0; i < mainmenu.length; i++) {
  //   mainmenu[i].addEventListener('mouseover', function () {
  //     submenu[i].classList.add('showsubmenu');
  //   });
  //   mainmenu[i].addEventListener('mouseout', function () {
  //     submenu[i].classList.remove('showsubmenu');
  //   });
  // }

  // ヘッダー現在のページ
  let mainitem = document.getElementsByClassName('headerhref');
    for (let i = 0; i < mainitem.length; i++) {
      if(mainitem[i].href === location.href) {
          mainitem[i].parentNode.classList.add('current');
        }
    }

  let subitem = document.getElementsByClassName('subitemhref');
  for (let i = 0; i < subitem.length; i++) {
    if(subitem[i].href === location.href) {
        subitem[i].closest(".mainmenu").classList.add('current');
        subitem[i].parentNode.classList.add('current');
      }
  }

}