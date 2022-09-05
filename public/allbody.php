<?php

require_once(__DIR__ . '/app/config.php');

if (!isset($_SESSION['mail'])) {
  header('Location: ' . SITE_URL . '/login.php');
}

$body = new Body($pdo);
$body->processPost();
$getbody = $body->getAll();

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
      <td><?= Utils::h($getbody->date); ?></td>
      <td><?= Utils::h($getbody->weight); ?></td>
      <td><?= Utils::h($getbody->bodyfat); ?></td>
      <td>
        <form action="?action=delete" method="post">
          <span class="delete">x</span>
          <input type="hidden" name="id" value="<?= Utils::h($getbody->id); ?>">
          <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
        </form>
      </td>
      </tr>
      <?php endforeach; ?>
    </table>
    </li>
  </ul>
</section>

<a href="/body.php">戻る</a>

<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
</body>
</html>