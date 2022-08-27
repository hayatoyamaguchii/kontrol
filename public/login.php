<?php

require_once(__DIR__ . '/app/config.php');
require_once(__DIR__ . '/app/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  validateToken();
  $action = filter_input(INPUT_GET, 'action');

  switch ($action) {  
    case 'login':
      login($pdo);
      header('Location: ' . SITE_URL . '/login.php?state=done');
      break;
    default:
      exit;
  }
}

?>

<body>
<?php require_once(__DIR__ . '/pages/_header.php'); ?>

<h1>ログイン</h1>
<?php if(!isset($_GET['state']) && !isset($_SESSION['loggedin'])): ?>
<form action="?action=login" method="post">
  <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
  <ul>
    <li><label for="mail" require>メールアドレス</label><input type="email" name="mail" pattern="^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$" size="30" require></li>
    <li><label for="password">パスワード</label><input type="password" name="password" require></li>
    <li><button type="submit" name="submit">ログイン</button></li>
  </ul>
</form>

<?php 
    $mail = $_POST['mail'];
    $stmt = $dbh->prepare("SELECT * FROM users WHERE mail = :mail;");
    $stmt->bindValue(':mail', $mail);
    $stmt->execute();
    $db = $stmt->fetch();

    if (password_verify($_POST['password'], $db['password'])) {
      //DBのユーザー情報をセッションに保存
      $_SESSION['id'] = $member['id'];
      $_SESSION['name'] = $member['name'];
      $msg = 'ログインが完了しました。早速使ってみましょう！';
      $link = '<a href="home.php">ホームへ進む</a>';
  } else {
      $msg = 'メールアドレスもしくはパスワードが間違っています。';
      $link = '  <p><a href="<?=SITE_URL . "/login.php"?>">戻る</a></p>
      <p><a href="<?=SITE_URL . "/signup.php"?>">新規登録はこちら</a></p>';
  }

      // $_SESSION['mail'] = implode($mail)
?>

<p><?php echo $msg; ?></p>
<?php echo $link; ?>

<?php elseif ($_SESSION['loggedin']): ?>
<?php header('Location: ' . SITE_URL . '/home.php'); ?>
<?php endif; ?>

<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
</body>
</html>