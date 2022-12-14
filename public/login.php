<?php
require_once(__DIR__ . '/app/config.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  Token::validate();
  $action = filter_input(INPUT_GET, 'action');

  switch ($action) {  
    case 'login':
      login($pdo);
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
<h1>ログイン</h1>
<?php if(!isset($_GET['state'])): ?>
<form action="?action=login" method="post">
  <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
  <ul>
    <li><label for="mail" require>メールアドレス</label><input type="email" name="mail" pattern="^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$" require></li>
    <li><label for="password">パスワード</label><input type="password" name="password" require></li>
    <li><button type="submit" name="submit">ログイン</button></li>
  </ul>
</form>
<p><a href="<?=SITE_URL . "/signup.php"?>" class="orange">新規登録はこちら</a></p>
<p>メールアドレス：test@test.test</p>
<p>パスワード：test</p>
<p>上記でログインしテストユーザーとして全ての機能を利用可能です。</p>

<?php elseif ($_GET['state'] === 'error'): ?>
  <p>メールアドレスもしくはパスワードが間違っています。</p>
  <p><a href="<?=SITE_URL . "/login.php"?>" class="orange">戻る</a></p>
  <p><a href="<?=SITE_URL . "/signup.php"?>" class="orange">新規登録はこちら</a></p>


<?php elseif ($_GET['state'] === 'loggedin'): ?>
  <p>ログインが完了しました。早速使ってみましょう！</p>
  <a href="home.php">ホームへ進む</a>
<?php endif; ?>
</seciton>

<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
</body>
</html>