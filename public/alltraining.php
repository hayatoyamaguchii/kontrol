<?php

require_once(__DIR__ . '/app/config.php');
require_once(__DIR__ . '/app/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  validateToken();
  $action = filter_input(INPUT_GET, 'action');

  switch ($action) {
    case 'delete':
      deletetraining($pdo);
      break;
    default:
      exit;
  }

  header('Location: ' . SITE_URL . '/alltraining.php');
  exit;
}

$gettrainings = getTrainings($pdo);

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
      <?php foreach ($gettrainings as $gettraining): ?>
      <tr>
      <td><?= h($gettraining->date); ?></td>
      <td><?= h($gettraining->part); ?></td>
      <td><?= h($gettraining->type); ?></td>
      <td><?= h($gettraining->sets); ?></td>
      <td><?= h($gettraining->weight); ?></td>
      <td><?= h($gettraining->reps); ?></td>
      <td>
        <form action="?action=delete" method="post">
          <span class="delete">x</span>
          <input type="hidden" name="id" value="<?= h($gettraining->id); ?>">
          <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
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