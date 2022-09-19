<!DOCTYPE html>
<html lang="ja">
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-236003031-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'UA-236003031-1');
  </script>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
  <link rel="stylesheet" href="css/style.css">
  <link rel="icon" href="/../img/favicon.ico">
  <title>Kontrol</title>
</head>
<header class="title-header">
  <h1><a href="/home.php"><span class="orange">K</span>ontrol</a></h1>
  <div class="title-header-item"><span class="material-symbols-outlined title-header-icon">account_circle</span><div class ="title-header-text"><?php if(isset($_SESSION['mail'])) { echo ($_SESSION['mail']); }?></div>
        <ul class="title-header-submenu title-header-submenu1 hidden">
          <li class="title-header-submenu-item"><a href="/account.php" class="subitemhref">アカウント設定</a></li>
          <li class="submenu-item"><a href="/logout.php" class="subitemhref">ログアウト</a></li>
        </ul>
  </div>
</header>
<header>
  <nav id="pc-menu">
    <ul class="header-ul">
      <li class="header-item"><a href="/home.php" class="headerhref"><span class="material-symbols-outlined header-icon">home</span>ホーム</a></li>
      <li class="header-item"><a href="/calendar.php" class="headerhref"><span class="material-symbols-outlined header-icon">calendar_month</span>カレンダー</a></li>
      <li class="header-item"><a href="/meal.php" class="headerhref"><span class="material-symbols-outlined header-icon">restaurant</span>食事記録</a></li>
      <li class="header-item"><a href="/training.php" class="headerhref"><span class="material-symbols-outlined header-icon">fitness_center</span>トレーニング記録</a></li>
      <li class="header-item"><a href="/body.php" class="headerhref"><span class="material-symbols-outlined header-icon">scale</span>体組成記録</a></li>
      <li class="header-item"><a href="/target.php" class="headerhref"><span class="material-symbols-outlined header-icon">flag</span>目標設定</a></li>
    </ul>
  </nav>

  <div id="sp-menu" id="sp-menu-wrapper">
    <span class="material-symbols-outlined sp-menu-icon sp-menu-open" id="sp-menu-open">menu</span>
  </div>
</header>

<nav class="sp-menu-overlay overlay-close">
  <div class="sp-menu" id="sp-menu-wrapper">
  <span class="material-symbols-outlined sp-menu-icon sp-menu-close" id="sp-menu-close">close</span>
  </div>
  <ul class="header-ul">
      <li class="header-item"><a href="/home.php" class="headerhref"><span class="material-symbols-outlined header-icon">home</span>ホーム</a></li>
      <li class="header-item"><a href="/calendar.php" class="headerhref"><span class="material-symbols-outlined header-icon">calendar_month</span>カレンダー</a></li>
      <li class="header-item"><a href="/meal.php" class="headerhref"><span class="material-symbols-outlined header-icon">restaurant</span>食事記録</a></li>
      <li class="header-item"><a href="/training.php" class="headerhref"><span class="material-symbols-outlined header-icon">fitness_center</span>トレーニング<br>記録</a></li>
      <li class="header-item"><a href="/body.php" class="headerhref"><span class="material-symbols-outlined header-icon">scale</span>体組成記録</a></li>
      <li class="header-item"><a href="/target.php" class="headerhref"><span class="material-symbols-outlined header-icon">flag</span>目標設定</a></li>
    </ul>
  </nav>
<main>