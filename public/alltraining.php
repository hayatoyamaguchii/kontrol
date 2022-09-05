<?php

require_once(__DIR__ . '/app/config.php');

if (!isset($_SESSION['mail'])) {
  header('Location: ' . SITE_URL . '/login.php');
}

$training = new Training($pdo);
$training->processPost();
$getalltrainings = $training->getAll();

?>

<body>
<?php require_once(__DIR__ . '/pages/_header.php'); ?>
<section id="traininglist">
  <h1>全ての記録</h1>
  <ul>
    <li>
    <table border="1">
      <tr>
        <th>食事した日</th>
        <th>食べた物</th>
        <th>量(g / 個)</th>
        <th>カロリー(kcal)</th>
        <th>たんぱく質(g)</th>
        <th>脂質(g)</th>
        <th>炭水化物(g)</th>
      </tr>
      <?php foreach ($getalltrainings as $getalltraining): ?>
      <tr>
      <td><?= Utils::h($getalltraining->date); ?></td>
      <td><?= Utils::h($getalltraining->part); ?></td>
      <td><?= Utils::h($getalltraining->type); ?></td>
      <td><?= Utils::h($getalltraining->sets); ?></td>
      <td><?= Utils::h($getalltraining->weight); ?></td>
      <td><?= Utils::h($getalltraining->reps); ?></td>
      <td>
        <form action="?action=delete" method="post">
          <span class="delete">x</span>
          <input type="hidden" name="id" value="<?= Utils::h($getalltraining->id); ?>">
          <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
        </form>
      </td>
      </tr>
      <?php endforeach; ?>
    </table>
    </li>
  </ul>
</section>

<a href="/training.php">戻る</a>

<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
<script src="/js/training.js"></script>
</body>
</html>