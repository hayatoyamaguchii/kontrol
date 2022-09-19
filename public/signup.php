<?php

require_once(__DIR__ . '/app/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  Token::validate();
  $action = filter_input(INPUT_GET, 'action');

  switch ($action) {  
    case 'sendmail':
      signupSendmail($pdo);
      header('Location: ' . SITE_URL . '/signup.php?state=sended');
      break;
    case 'signup':
      signup($pdo);
      header('Location: ' . SITE_URL . '/signup.php?state=done');
      break;
    default:
      exit;
  }
}

if (isset($_SESSION['mail'])) {
  header('Location: ' . SITE_URL . '/home.php');
}

?>

<body>
<?php require_once(__DIR__ . '/pages/_header.php'); ?>

<section>
<h1>会員登録</h1>
<?php if(!isset($_GET['urltoken']) && !isset($_GET['state'])): ?>
<form action="?action=sendmail" method="post">
  <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
  <label for="mail" require>メールアドレス</label>
  <input type="email" name="mail" pattern="^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$" required>
  <button type="submit" name="submit">登録</button>
</form>
<p><a href="<?=SITE_URL . "/login.php"?>" class="orange">ログインはこちらから</a></p>

<?php elseif (isset($_GET['urltoken'])):
  $urltoken = $_GET["urltoken"];

      $stmt = $pdo->prepare("SELECT mail FROM pre_user WHERE urltoken=(:urltoken) AND status = 0 AND created > now() - interval 24 hour;");
      $stmt->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
      $stmt->execute();
      $mail = $stmt->fetch(PDO::FETCH_ASSOC);
      $mailrow = $stmt->rowCount();
?>

  <?php if($mailrow > 0): $_SESSION['sendmail'] = implode($mail) ?>
    <form action="?urltoken=<?= $urltoken; ?>&action=signup" method="post">
        <ul>
          <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
          <input type="hidden" name="urltoken" value="<?=$urltoken?>">
          <li><input type="hidden" name="mail" value="<?= Utils::h($_SESSION['sendmail'])?>" require>メールアドレス<?= Utils::h($_SESSION['sendmail'])?></li>
          <li><label for="password">パスワード</label><input type="password" name="password" require></li>
          <a href="<?=SITE_URL . '/signup.php'?>"><button type="submit" name="signup">登録</button></a>
        </ul>
    </form>
  <?php else: ?>
  <p>トークンが異なるか、仮登録から24時間以上経過しています。最初からやり直してください。</p>
  <p class="orange"><a href="<?=SITE_URL . "/signup.php"?>">新規登録はこちら</a></p>
  <?php endif; ?>

<?php elseif($_GET['state'] === 'sended'): ?>
<p>メールを送信しました。24時間以内にご確認ください。</p>

<?php elseif($_GET['state'] === 'done'): ?>
<p>登録が完了しました。</p>
<p class="orange"><a href="<?=SITE_URL . "/home.php"?>">ホームへ進む</a></p>
<?php endif; ?>
</section>

<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
</body>
</html>