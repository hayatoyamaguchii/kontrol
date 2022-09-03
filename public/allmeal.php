<?php

require_once(__DIR__ . '/app/config.php');
require_once(__DIR__ . '/app/functions.php');

if (!isset($_SESSION['mail'])) {
  header('Location: ' . SITE_URL . '/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  Token::validate();
  $action = filter_input(INPUT_GET, 'action');

  switch ($action) {
    case 'delete':
      deleteMeal($pdo);
      break;
    default:
      exit;
  }

  header('Location: ' . SITE_URL . '/allmeal.php');
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
      <td><?= Utils::h($getmeal->date); ?></td>
      <td><?= Utils::h($getmeal->food); ?></td>
      <td><?= Utils::h($getmeal->weight); ?></td>
      <td><?= Utils::h($getmeal->cal) * Utils::h($getmeal->weight); ?></td>
      <td><?= Utils::h($getmeal->pro) * Utils::h($getmeal->weight); ?></td>
      <td><?= Utils::h($getmeal->fat) * Utils::h($getmeal->weight); ?></td>
      <td><?= Utils::h($getmeal->car) * Utils::h($getmeal->weight); ?></td>
      <td>
        <form action="?action=delete" method="post">
          <span class="delete">x</span>
          <input type="hidden" name="id" value="<?= Utils::h($getmeal->id); ?>">
          <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
        </form>
      </td>
      </tr>
      <?php endforeach; ?>
    </table>
    </li>
  </ul>
</section>

<a href="/meal.php">戻る</a>

<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
<script src="/js/meal.js"></script>
</body>
</html>