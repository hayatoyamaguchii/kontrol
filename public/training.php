<?php

require_once(__DIR__ . '/app/config.php');
require_once(__DIR__ . '/app/functions.php');

if (!isset($_SESSION['mail'])) {
  header('Location: ' . SITE_URL . '/login.php');
}

$training = new Training($pdo);
$training->processPost();
$getrecenttrainings = $training->getRecent();

$searchbydatetraining = searchbyDatetraining($pdo);
$searchbydate = filter_input(INPUT_GET, 'searchbydate');
$searchbytypetraining = searchbyTypetraining($pdo);
$searchbytype = filter_input(INPUT_GET, 'searchbytype');

?>

<body>
<?php require_once(__DIR__ . '/pages/_header.php'); ?>

<form action="?action=add" method="post">
  <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
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
    <label for="sets">セット数</label>
    <input type="number" name="sets" id="sets">sets
  </li>
  <li>
    <label for="weight">重量(1セット目)</label>
    <input type="number" step="0.25" name="weight0" id="weight" required>kg
  </li>
  <li>
    <label for="reps">レップ数(1セット目)</label>
    <input type="number" name="reps0" id="reps" required>reps
  </li>
  <li>
    <ul id="addparent">
    </ul>
  </li>
  <li>
    <button type="button" id="addsets">セットを追加</button>
    <button type="button" id="deletesets">セットを削除</button>
  </ul>
  <li>
    <button type="submit">送信</button>
  </li>
</form>

<a href="/alltraining.php">全ての記録</a>

<section id="recenttrainings">
  <h1>最近の記録</h1>
  <ul>
    <li>
    <table border="1">
      <tr>
        <th>実施日</th>
        <th>部位</th>
        <th>種目</th>
        <th>セット数</th>
        <th>重量</th>
        <th>レップ数</th>
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
        <form action="?action=delete" method="post">
          <span class="delete">x</span>
          <input type="hidden" name="id" value="<?= Utils::h($getrecenttraining->id); ?>">
          <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
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

<form action="?action=searchbytype" method="get">
  <ul>
  <li>
    <label for="">種目で検索</label>
    <input type="text" name="searchbytype" id="searchbytype"required>
  </li>
  <li>
    <button type="submit">検索</button>
  </li>
  </ul>
</form>

<?php 
if (!empty($searchbydatetraining)) {
  echo '<ul>
  <li>
  <table border="1">
    <tr>
    <th>部位</th>
    <th>種目</th>
    <th>セット数</th>
    <th>重量</th>
    <th>レップ数</th>
    </tr>';
  }  
?>

<?php foreach ($searchbydatetraining as $dateresult): ?>
    <tr>
    <td><?= Utils::h($dateresult->date); ?></td>
    <td><?= Utils::h($dateresult->part); ?></td>
    <td><?= floatval( Utils::h($dateresult->type)); ?></td>
    <td><?= floatval( Utils::h($dateresult->sets)); ?></td>
    <td><?= floatval( Utils::h($dateresult->weight)); ?></td>
    <td><?= floatval( Utils::h($dateresult->reps)); ?></td>
    <td>
      <form action="?action=deletemeal" method="post">
        <span class="delete">x</span>
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

<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
<script src="/js/training.js"></script>
</body>
</html>