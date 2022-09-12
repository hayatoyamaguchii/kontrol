<?php

require_once(__DIR__ . '/app/config.php');

if (!isset($_SESSION['mail'])) {
  header('Location: ' . SITE_URL . '/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  Token::validate();
  $action = filter_input(INPUT_GET, 'action');

  switch ($action) {
    case 'add':
      addTrainings($pdo);
      break;
    case 'delete':
      deleteTrainings($pdo);
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

<h2>メールアドレス</h2>
<h2>パスワード</h2>
<h2>退会する</h2>

<input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">

<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
</body>
</html>