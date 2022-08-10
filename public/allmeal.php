<?php

require_once(__DIR__ . '/../app/config.php');
require_once(__DIR__ . '/../app/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  validateToken();
  $action = filter_input(INPUT_GET, 'action');

  switch ($action) {
    case 'delete':
      deleteMeal($pdo);
      break;
    default:
      exit;
  }

  header('Location: ' . SITE_URL . '/public/allmeal.php');
  exit;
}

$getmeals = getMeals($pdo);

?>

<body>
<?php require_once(__DIR__ . '/pages/_header.php'); ?>
<section id="meallist">
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
      <?php foreach ($getmeals as $getmeal): ?>
      <tr>
      <td><?= h($getmeal->date); ?></td>
      <td><?= h($getmeal->food); ?></td>
      <td><?= h($getmeal->weight); ?></td>
      <td><?= h($getmeal->cal) * h($getmeal->weight); ?></td>
      <td><?= h($getmeal->pro) * h($getmeal->weight); ?></td>
      <td><?= h($getmeal->fat) * h($getmeal->weight); ?></td>
      <td><?= h($getmeal->car) * h($getmeal->weight); ?></td>
      <td>
        <form action="?action=delete" method="post">
          <span class="delete">x</span>
          <input type="hidden" name="id" value="<?= h($getmeal->id); ?>">
          <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
        </form>
      </td>
      </tr>
      <?php endforeach; ?>
    </table>
    </li>
  </ul>
</section>

<a href="/public/meal.php">戻る</a>

<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
<script src="/public/js/meal.js"></script>
</body>
</html>