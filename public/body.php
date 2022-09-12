<?php

require_once(__DIR__ . '/app/config.php');

if (!isset($_SESSION['mail'])) {
  header('Location: ' . SITE_URL . '/login.php');
}

$body = new Body($pdo);
$body->processPost();
$getrecentbody = $body->getRecent();
$dateresults = $body->searchbyDate();
$searchbydate = filter_input(INPUT_GET, 'searchbydate');

?>

<body>
<?php require_once(__DIR__ . '/pages/_header.php'); ?>

<section>
<div class="open open1">体組成を登録する</div>
<div class="mask hidden"></div>
</section>

<div class="modal modal1 hidden">
<section>
  <h2>体組成を登録する</h2>
  <form action="?action=add" method="post">
    <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
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
  <div class="close close1">閉じる</div>
</section>
</div>

<section id="recentbodylist">
  <h2>最近の記録</h2>
  <ul>
    <li>
    <table>
      <tr>
        <th>計測日</th>
        <th>体重</th>
        <th>体脂肪率</th>
      </tr>
      <?php foreach ($getrecentbody as $getrecentbody): ?>
      <tr>
      <td><?= floatval( Utils::h($getrecentbody->date)); ?></td>
      <td><?= floatval( Utils::h($getrecentbody->weight)); ?></td>
      <td><?= floatval( Utils::h($getrecentbody->bodyfat)); ?></td>
      <td>
        <form class="deleteform" action="?action=delete" method="post">
          <span class="delete">削除</span>
          <input type="hidden" name="id" value="<?= Utils::h($getrecentbody->id); ?>">
          <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
        </form>
      </td>
      </tr>
      <?php endforeach; ?>
    </table>
    </li>
  </ul>
  <a href="/allbody.php" class="orange">全ての記録</a>
</section>

<section>
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
  <table>
    <tr>
    <th>計測日</th>
    <th>体重</th>
    <th>体脂肪率</th>
    </tr>';
  }  
?>

<?php foreach ($dateresults as $dateresult): ?>
    <tr>
    <td><?= Utils::h($getrecentbody->date); ?></td>
    <td><?= Utils::h($getrecentbody->weight); ?></td>
    <td><?= Utils::h($getrecentbody->bodyfat); ?></td>
    <td>
      <form class="deleteform" action="?action=delete" method="post">
        <span class="delete">削除</span>
        <input type="hidden" name="id" value="<?= Utils::h($dateresult->id); ?>">
        <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
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
</section>

<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
</body>
</html>