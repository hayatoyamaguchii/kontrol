<?php

require_once(__DIR__ . '/app/config.php');
require_once(__DIR__ . '/app/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  validateToken();
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

  header('Location: ' . SITE_URL . '/option.php');
  exit;
}

?>

<body>
<?php require_once(__DIR__ . '/pages/_header.php'); ?>

<h2>メールアドレス</h2>
<h2>パスワード</h2>
<h2>生年月日</h2>
<h2>身長</h2>
<h2>退会する</h2>

<input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">

<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
</body>
</html>