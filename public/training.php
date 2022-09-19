<?php

require_once(__DIR__ . '/app/config.php');

if (!isset($_SESSION['user'])) {
  header('Location: ' . SITE_URL . '/login.php');
}

$training = new Training($pdo);
$training->processPost();
$getrecenttrainings = $training->getRecent();
$searchbydate = $training->getByDate();

$date = filter_input(INPUT_GET, 'searchbydate');
$type = filter_input(INPUT_GET, 'searchbytype');

?>

<body>
<?php require_once(__DIR__ . '/pages/_header.php'); ?>

<section>
<div class="open open1">トレーニング記録を登録する</div>
<div class="mask hidden"></div>
</section>

<div class="modal modal1 hidden">
<section>
  <h2>トレーニング記録を登録する</h2>
  <form action="?action=add" method="post">
    <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
    <input type="hidden" name="user" value="<?= Utils::h($_SESSION['user']); ?>">
    <ul id="form">
    <li>
      <label for="">実施した日</label>
      
      <input type="date" name="date" id="date" required>
    </li>
    <li>
      <label for="part">部位</label>
      <select type="text" name="part" id="part" required>
        <option value="chest">胸</option>
        <option value="back">背中</option>
        <option value="shoulder">肩</option>
        <option value="arm">腕</option>
        <option value="legs">脚</option>
        <option value="abs">腹</option>
        <option value="cardio">有酸素</option>
        <option value="other">その他</option>
      </select>
    </li>
    <li>
      <label for="type">種目</label>
      <input type="text" name="type" id="type" required>
    </li>
    <li>
      <label for="sets">何セット目？</label>
      <input type="number" name="sets" id="sets">sets
    </li>
    <li>
      <label for="weight">重量</label>
      <input type="number" step="0.25" name="weight" id="weight" required>kg
    </li>
    <li>
      <label for="reps">レップ数</label>
      <input type="number" name="reps" id="reps" required>reps
    </li>
    <li>
      <ul id="addparent">
      </ul>
    </li>
    <li>
      <button type="submit">送信</button>
    </li>
  </form>
  <div class="close1 close">閉じる</div>
</section>
</div>

<section id="recenttrainings">
  <h2>最近の記録</h2>
  <ul>
    <li>
    <table>
      <tr>
        <th>実施日</th>
        <th>部位</th>
        <th>種目</th>
        <th>セット数</th>
        <th>重量</th>
        <th>レップ数</th>
        <th>削除</th>
      </tr>
      <?php foreach ($getrecenttrainings as $getrecenttraining): ?>
      <tr>
      <td><?= Utils::h($getrecenttraining->date); ?></td>
      <td><?= Utils::h($getrecenttraining->part); ?></td>
      <td><?= Utils::h($getrecenttraining->type); ?></td>
      <td><?= Utils::h($getrecenttraining->sets); ?></td>
      <td><?= Utils::h($getrecenttraining->weight); ?></td>
      <td><?= Utils::h($getrecenttraining->reps); ?></td>
      <td>
        <form class="deleteform" action="?action=delete" method="post">
          <span class="delete">削除</span>
          <input type="hidden" name="id" value="<?= Utils::h($getrecenttraining->id); ?>">
          <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
        </form>
      </td>
      </tr>
      <?php endforeach; ?>
    </table>
    </li>
  </ul>
  <a href="/alltraining.php" class="orange">全ての記録</a>
</section>

<section>
<form action="?action=searchbydate" method="get">
  <ul class="searchform">
  <li class="searchli">
    <label for="">日付検索</label>
    <input type="date" name="searchbydate" id="searchbydate"required>
  </li>
  <li>
    <button type="submit">検索</button>
  </li>
  </ul>
</form>

<?php if (!empty($searchbydate)):?>
  <ul>
  <li>
  <table>
    <tr>
    <th>実施日</th>
    <th>部位</th>
    <th>種目</th>
    <th>セット数</th>
    <th>重量</th>
    <th>レップ数</th>
    </tr>
<?php endif; 
  foreach ($searchbydate as $dateresult): ?>
    <tr>
    <td><?= Utils::h($dateresult->date); ?></td>
    <td><?= Utils::h($dateresult->part); ?></td>
    <td><?= Utils::h($dateresult->type); ?></td>
    <td><?= Utils::h($dateresult->sets); ?></td>
    <td><?= Utils::h($dateresult->weight); ?></td>
    <td><?= Utils::h($dateresult->reps); ?></td>
    <td>
      <form class="deleteform" action="?action=deletemeal" method="post">
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
if (empty($dateresult) && isset($_GET['searchbydate'])) {
  echo '<p>'. $date . 'に該当するデータがありません。</p>';
  }
?>
</section>

<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
<script src="/js/training.js"></script>
</body>
</html>