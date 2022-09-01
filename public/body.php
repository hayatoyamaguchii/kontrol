<?php

require_once(__DIR__ . '/app/config.php');
require_once(__DIR__ . '/app/functions.php');

if (!isset($_SESSION['mail'])) {
  header('Location: ' . SITE_URL . '/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  validateToken();
  $action = filter_input(INPUT_GET, 'action');

  switch ($action) {  
    case 'add':
      addBodycom($pdo);
      break;
    case 'delete':
      deleteBodycom($pdo);
      break;
    default:
      exit;
  }

  header('Location: ' . SITE_URL . '/body.php');
  exit;
}

$getmeals = getBodycom($pdo);
$getrecentbody = getrecentBodycom($pdo);
$dateresults = searchbyDatebodycom($pdo);
$searchbydate = filter_input(INPUT_GET, 'searchbydate');

?>

<body>
<?php require_once(__DIR__ . '/pages/_header.php'); ?>

<section id="addbodycomposition">
  <h1>体組成を登録する</h1>
  <form action="?action=add" method="post">
    <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
    <ul id="form">
    <li>
      <label for="date">計測日</label>
      <input type="datetime-local" name="date" id="date" required>
    </li>
    <li>
      <label for="weight">体重</label>
      <input type="number" name="weight" id="weight" min="1" step="0.01" required>kg
    </li>
    <li>
      <label for="bodyfat">体脂肪</label>
      <input type="number" name="bodyfat" id="bodyfat" min="1" max="100" step="0.01" equired>%
    </li>
    <li>
      <button type="submit">送信</button>
    </li>
    </ul>
  </form>
</section>

<a href="/allbody.php">全ての記録</a>

<section id="recentbodylist">
  <h1>最近の記録</h1>
  <ul>
    <li>
    <table border="1">
      <tr>
        <th>計測日</th>
        <th>体重</th>
        <th>体脂肪率</th>
      </tr>
      <?php foreach ($getrecentbody as $getrecentbody): ?>
      <tr>
      <td><?= floatval(h($getrecentbody->date)); ?></td>
      <td><?= floatval(h($getrecentbody->weight)); ?></td>
      <td><?= floatval(h($getrecentbody->bodyfat)); ?></td>
      <td>
        <form action="?action=delete" method="post">
          <span class="delete">x</span>
          <input type="hidden" name="id" value="<?= h($getrecentbody->id); ?>">
          <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
        </form>
      </td>
      </tr>
      <?php endforeach; ?>
    </table>
    </li>
  </ul>
</section>

<form action="?action=searchbydate" method="get">
  <ul>
  <li>
    <label for="">日付検索</label>
    <input type="date" name="searchbydate" id="searchbydate"required>
  </li>
  <li>
    <button type="submit">検索</button>
  </li>
  </ul>
</form>

<?php 
if (!empty($dateresults)) {
  echo '<ul>
  <li>
  <table border="1">
    <tr>
    <th>計測日</th>
    <th>体重</th>
    <th>体脂肪率</th>
    </tr>';
  }  
?>

<?php foreach ($dateresults as $dateresult): ?>
    <tr>
    <td><?= h($getrecentbody->date); ?></td>
    <td><?= h($getrecentbody->weight); ?></td>
    <td><?= h($getrecentbody->bodyfat); ?></td>
    <td>
      <form action="?action=delete" method="post">
        <span class="delete">x</span>
        <input type="hidden" name="id" value="<?= h($dateresult->id); ?>">
        <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
      </form>
    </td>
    </tr>
    <?php endforeach; ?>
  </table>
  </li>
</ul>
<?php 
if (empty($searchbydate)) {
  }
elseif (empty($dateresults)) {
  echo '<p>'. $searchbydate . 'に該当するデータがありません。</p>';
  }
?>

<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
</body>
</html>