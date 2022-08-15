<?php

require_once(__DIR__ . '/../app/config.php');
require_once(__DIR__ . '/../app/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  validateToken();
  $action = filter_input(INPUT_GET, 'action');

  switch ($action) {  
    case 'addmeal':
      addMeal($pdo);
      break;
    case 'deletemeal':
      deleteMeal($pdo);
      break;
    case 'addlist':
      addFoodlist($pdo);
      break;
    case 'deletelist':
      deleteFoodlist($pdo);
      break;
    case 'addmealandlist';
      addFoodlist($pdo);
      addMeal($pdo);
      break;
    default:
      exit;
  }

  header('Location: ' . SITE_URL . '/public/meal.php');
  exit;
}

$getgenres = getGenres($pdo);
$getmeals = getMeals($pdo);
$getrecentmeals = getrecentMeals($pdo);
$getfoodlist = getFoodlist($pdo);
$dateresults = searchbyDatemeal($pdo);
$searchbydate = filter_input(INPUT_GET, 'searchbydate');

?>

<body>
<?php require_once(__DIR__ . '/pages/_header.php'); ?>

<article id="addmealarticle">
<!-- 食品リストから登録する機能 -->
<section id="addmealfromlist">
  <h1>食品リストから登録する</h1>
  <form action="?action=addmeal" method="post">
    <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
    <ul id="form">
    <li>
      <label for="date">食事した日時</label>
      <input type="datetime-local" name="date" id="date" required>
    </li>
    <li>
      <label for="genre">ジャンル</label>
      <select type="text" name="genre" id="genre" required>
        <option value="">選択してください</option>
      <?php foreach ($getgenres as $getgenre): ?>
        <option value="<?= h($getgenre->genre); ?>"><?= h($getgenre->genre); ?></option>
      <?php endforeach; ?>
      </select>
    </li>
    <li>
      <label for="food">食べた物</label>
      <select type="text" name="food" id="food" required>
        <option value="">選択してください</option>
        <?php foreach ($getfoodlist as $getfood): ?>
        <option value="<?= h($getfood->food); ?>"><?= h($getfood->food); ?></option>
        <?php endforeach; ?>
      </select>
    </li>
    <li>
      <label for="weight">量</label>
      <input type="number" name="weight" id="weight" required>g (単位付きは単位表記に従う)
    </li>
    <li>
      <button type="submit">送信</button>
    </li>
    </ul>
  </form>
</section>

<!-- リストへの登録をしながら追加する機能。チェックボックスで登録するかしないかを選択。 -->
<section id="addmealwithoutlist">
  <h1>リスト外から登録する</h1>
  <form action="?action=addmealandlist" method="post">
    <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
    <ul id="form">
    <li>
      <label for="date">食事した日時</label>
      <input type="datetime-local" name="date" id="date" required>
    </li>
    <li>
      <label for="genre">ジャンル</label>
      <input type="text" name="genre" id="genre" required>
    </li>
    <li>
      <label for="food">食べた物</label>
      <input type="text" name="food" id="food" required>
    </li>
    <li>
      <label for="weight">量</label>
      <input type="number" name="weight" id="weight" required>g / 個</li>
    <li>
    <li>
      <label for="cal">カロリー</label>
      <input type="number" name="cal" id="cal" required>kcal
    </li>
    <li>
      <label for="pro">たんぱく質量</label>
      <input type="number" name="pro" id="pro" required>g
    </li>
    <li>
      <label for="fat">脂質量</label>
      <input type="number" name="fat" id="fat" required>g
    </li>
    <li>
      <label for="car">炭水化物量</label>
      <input type="number" name="car" id="car" required>g
    </li>
      食品リストに追加する
      <input type="hidden" name="check" id="check" value="0">
      <input type="checkbox" name="check" id="check" value="1">
    </li>
    <li>
      <button type="submit">送信</button>
    </li>
    </ul>
  </form>
</section>
</article>

<a href="/public/allmeal.php">全ての記録</a>

<section id="recentmeallist">
  <h1>最近の記録</h1>
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
      <?php foreach ($getrecentmeals as $getrecentmeal): ?>
      <tr>
      <td><?= h($getrecentmeal->date); ?></td>
      <td><?= h($getrecentmeal->food); ?></td>
      <td><?=abs(h($getrecentmeal->weight)); ?></td>
      <td><?=abs(h($getrecentmeal->cal) * h($getrecentmeal->weight)); ?></td>
      <td><?=abs(h($getrecentmeal->pro) * h($getrecentmeal->weight)); ?></td>
      <td><?=abs(h($getrecentmeal->fat) * h($getrecentmeal->weight)); ?></td>
      <td><?=abs(h($getrecentmeal->car) * h($getrecentmeal->weight)); ?></td>
      <td>
        <form action="?action=deletemeal" method="post">
          <span class="delete">x</span>
          <input type="hidden" name="id" value="<?= h($getrecentmeal->id); ?>">
          <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
        </form>
      </td>
      </tr>
      <?php endforeach; ?>
    </table>
    </li>
  </ul>
</section>

<section id="recent7days">
  <h1>直近7日間の平均</h1>
  <table border="1">
    <tr>
      <th></th>
      <th>目標</th>
      <th>直近7日間の平均</th>
      <th>目標との差分</th>
    </tr>
    <tr>
      <th>カロリー</th>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <th>たんぱく質</th>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <th>脂質</th>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <th>炭水化物</th>
      <td></td>
      <td></td>
      <td></td>
    </tr>
  </table>
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
    <th>食事した日</th>
    <th>食べた物</th>
    <th>量(g / 個)</th>
    <th>カロリー(kcal)</th>
    <th>たんぱく質(g)</th>
    <th>脂質(g)</th>
    <th>炭水化物(g)</th>
    </tr>';
  }  
?>

<?php foreach ($dateresults as $dateresult): ?>
    <tr>
    <td><?= h($dateresult->date); ?></td>
    <td><?= h($dateresult->food); ?></td>
    <td><?= abs(h($dateresult->weight)); ?></td>
    <td><?= abs(h($dateresult->cal) * h($dateresult->weight)); ?></td>
    <td><?= abs(h($dateresult->pro) * h($dateresult->weight)); ?></td>
    <td><?= abs(h($dateresult->fat) * h($dateresult->weight)); ?></td>
    <td><?= abs(h($dateresult->car) * h($dateresult->weight)); ?></td>
    <td>
      <form action="?action=deletemeal" method="post">
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

<section id="addfoodlist">
  <h1>食品リストに追加</h1>
  <form action="?action=addlist" method="post">
    <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
    <ul id="form">
    <li>
      <label for="genre">ジャンル</label>
      <input type="text" name="genre" id="genre" required>
    </li>
    <li>
      <label for="food">食品名</label>
      <input type="text" name="food" id="food" required>
    </li>
    <li>
      <label for="cal">カロリー</label>
      <input type="number" name="cal" id="cal" required>kcal</li>
    <li>
    <li>
      <label for="pro">たんぱく質量</label>
      <input type="number" name="pro" id="pro" required>g
    </li>
    <li>
      <label for="fat">脂質量</label>
      <input type="number" name="fat" id="fat" required>g
    </li>
    <li>
      <label for="car">炭水化物量</label>
      <input type="number" name="car" id="car" required>g
    <li>
    <li>
      <label for="weight">量</label>
      <input type="number" name="weight" id="weight" required>(g / 個)
    </li>
      <button type="submit">リストに登録</button>
    </li>
    </ul>
  </form>
</section>

<section id="foodlist">
  <h1>マイ食品リスト</h1>
    <label for="genre">ジャンル</label>
    <select type="text" name="genre" id="genre" required>
      <option value="">選択してください</option>
    <?php foreach ($getgenres as $getgenre): ?>
      <option value="<?= h($getgenre->genre); ?>"><?= h($getgenre->genre); ?></option>
    <?php endforeach; ?>
    </select>
  <table border="1">
    <tr>
      <th>ジャンル</th>
      <th>食品名</th>
      <th>カロリー</th>
      <th>たんぱく質</th>
      <th>脂質</th>
      <th>炭水化物</th>
    </tr>
    <?php foreach ($getfoodlist as $getfoodlist): ?>
      <tr>
      <td><?= h($getfoodlist->genre); ?></td>
      <td><?= h($getfoodlist->food); ?></td>
      <td><?= abs(h($getfoodlist->cal)); ?></td>
      <td><?= abs(($getfoodlist->pro)); ?></td>
      <td><?= abs(($getfoodlist->fat)); ?></td>
      <td><?= abs(($getfoodlist->car)); ?></td>
      <td>
        <form action="?action=deletelist" method="post">
          <span class="delete">x</span>
          <input type="hidden" name="id" value="<?= h($getfoodlist->id); ?>">
          <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
        </form>
      </td>
      </tr>
      <?php endforeach; ?>
  </table>
</section>

<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
<script src="/public/js/meal.js"></script>
</body>
</html>