<?php

require_once(__DIR__ . '/app/config.php');

if (!isset($_SESSION['user'])) {
  header('Location: ' . SITE_URL . '/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  Token::validate();
  $action = filter_input(INPUT_GET, 'action');

  switch ($action) {
    case 'changepassword':
      changepassword($pdo);
      break;
    case 'deleteaccount':
      deleteaccount($pdo);
      break;
    default:
      exit;
  }

  header('Location: ' . $_SERVER['HTTP_REFERER']);
  exit;
}

?>

<body>
<?php require_once(__DIR__ . '/pages/_header.php'); ?>

<section>
<h2>メールアドレス</h2>
<?= Utils::h($_SESSION['mail']); ?>
</section>
<section>
<h2>パスワード変更</h2>
  <form action="?action=changepassword" method="post">
  <ul class="searchform">
          <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
          <li class="searchli"><label for="password">新しいパスワード</label><input type="password" name="password" require></li>
          <li><button type="submit">変更する</button></li>
        </ul>
  </form>
</section>
<section>
<h2>退会する</h2>
  <form action="?action=deleteaccount" method="post">
        <ul>
          <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
          <li><button type="submit" class="delete">削除する</button></li>
        </ul>
  </form>
</section>

<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
</body>
</html>