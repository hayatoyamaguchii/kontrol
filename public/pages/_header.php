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
  <!-- <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round|Material+Icons+Sharp|Material+Icons+Two+Tone" rel="stylesheet"> -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
  <link rel="stylesheet" href="css/style.css">
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
  <nav>
    <ul class="header-ul">
      <li class="header-item"><a href="/home.php" class="headerhref"><span class="material-symbols-outlined header-icon">home</span>ホーム</a></li>
      <li class="header-item"><a href="/calendar.php" class="headerhref"><span class="material-symbols-outlined header-icon">calendar_month</span>カレンダー</a></li>
      <li class="header-item"><a href="/meal.php" class="headerhref"><span class="material-symbols-outlined header-icon">restaurant</span>食事記録</a></li>
      <li class="header-item"><a href="/training.php" class="headerhref"><span class="material-symbols-outlined header-icon">fitness_center</span>トレーニング記録</a></li>
      <li class="header-item"><a href="/body.php" class="headerhref"><span class="material-symbols-outlined header-icon">scale</span>体組成記録</a></li>
      <li class="header-item"><a href="/target.php" class="headerhref"><span class="material-symbols-outlined header-icon">flag</span>目標設定</a></li>

      <!-- <li class="mainmenu mainmenu1 header-item"><span class="material-symbols-outlined">account_circle</span>
        <ul class="submenu submenu1 hidden">
          <li class="submenu-item"><a href="/meal.php" class="subitemhref"></a></li>
          <li class="submenu-item"><a href="/training.php" class="subitemhref">トレーニング記録</a></li>
          <li class="submenu-item"><a href="/body.php" class="subitemhref">体組成記録</a></li>
        </ul>
      </li> -->
      <!-- <li class="mainmenu mainmenu2 header-item"><span class="material-symbols-outlined header-icon">settings</span>アカウント
        <ul class="submenu submenu2 hidden">
          <li class="submenu-item"><a href="/account.php" class="subitemhref">アカウント設定</a></li>
          <li class="submenu-item logout"><a href="/logout.php" class="subitemhref">ログアウト</a></li>
        </ul>
      </li> -->
    </ul>
  </nav>
</header>
<main>