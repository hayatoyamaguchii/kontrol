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

<form action="?action=sendmail" method="post">
  <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
  <label for="mail">メールアドレス</label>
  <input type="email" name="mail" pattern="^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$" size="30" required>
  <button type="submit" name="submit">登録</button>
</form>


<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
</body>
</html>