<?php

require_once(__DIR__ . '/../app/config.php');
require_once(__DIR__ . '/../app/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  validateToken();
  $action = filter_input(INPUT_GET, 'action');

  switch ($action) {
    case 'delete':
      deleteBodycom($pdo);
      break;
    default:
      exit;
  }

  header('Location: ' . SITE_URL . '/public/allmeal.php');
  exit;
}

$getbody = getBodycom($pdo);

?>

<body>
<?php require_once(__DIR__ . '/pages/_header.php'); ?>

<section id="bodylist">
  <h1>最近の記録</h1>
  <ul>
    <li>
    <table border="1">
      <tr>
        <th>計測日</th>
        <th>体重</th>
        <th>体脂肪率</th>
      </tr>
      <?php foreach ($getbody as $getbody): ?>
      <tr>
      <td><?= h($getbody->date); ?></td>
      <td><?= h($getbody->weight); ?></td>
      <td><?= h($getbody->bodyfat); ?></td>
      <td>
        <form action="?action=delete" method="post">
          <span class="delete">x</span>
          <input type="hidden" name="id" value="<?= h($getbody->id); ?>">
          <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
        </form>
      </td>
      </tr>
      <?php endforeach; ?>
    </table>
    </li>
  </ul>
</section>

<a href="/public/body.php">戻る</a>

<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
</body>
</html>