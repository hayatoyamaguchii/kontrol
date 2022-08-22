<?php

require_once(__DIR__ . '/app/config.php');
require_once(__DIR__ . '/app/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  validateToken();
  $action = filter_input(INPUT_GET, 'action');

  switch ($action) {  
    case 'sendmail':
      signupSendmail($pdo);
      break;
    default:
      exit;
  }

  header('Location: ' . SITE_URL . '/signup.php');
  exit;
}

?>

<body>
<?php require_once(__DIR__ . '/pages/_header.php'); ?>

<h1>会員登録</h1>

<?php if(!isset($_GET['token'])): ?>

<form action="?action=sendmail" method="post">
  <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
  <label for="mail">メールアドレス</label>
  <input type="email" name="mail" pattern="^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$" size="30" required>
  <button type="submit" name="submit">登録</button>
</form>

<?php else: ?>
<?php 
  //GETデータを変数に入れる
  $token = $_GET["token"];
      //statusが0の本登録がまだ AND 仮登録から24時間以内
      $stmt = $pdo->prepare("SELECT mail FROM pre_user WHERE token=(:token) AND status = 0 AND created > now() - interval 24 hour;");
      $stmt->bindValue(':token', $token, PDO::PARAM_STR);
      $stmt->execute();
      $mail = $stmt->fetch(PDO::FETCH_ASSOC);
      $mailrow = $stmt->rowCount();
?>

<?php if($mailrow === 1): ?>
  <form action="?urltoken=<?= $urltoken; ?>" method="post">
        <p>メールアドレス：<?=implode($mail)?></p>
        <p>パスワード：<input type="password" name="password"></p>
        <p>氏名：<input type="text" name="name" value="<?php if( !empty($_SESSION['name']) ){ echo $_SESSION['name']; } ?>"></p>
        <input type="hidden" name="token" value="<?=$token?>">
        <input type="submit" name="btn_confirm" value="確認する">
  </form>

<?php else: ?>
<p>トークンが異なるか、仮登録から24時間以上経過しています。登録を最初からやり直してください。</p>
<p><a href="<?=SITE_URL . "/signup.php"?>">新規登録はこちら</a></p>
<?php endif; ?>
<?php endif; ?>


<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
</body>
</html>