<?php

require_once(__DIR__ . '/app/config.php');

if (!isset($_SESSION['mail'])) {
  header('Location: ' . SITE_URL . '/login.php');
}

$meal = new Meal($pdo);
$meal->processPost();
$getrecenttrainings = $meal->getRecent();
$getgenres = $meal->getGenres();
$getrecentmeals = $meal->getRecent();
$getfoodlist = $meal->getFoodlist();
$dateresults = $meal->searchbyDate();
$date = filter_input(INPUT_GET, 'searchbydate');

?>

<body>
<?php require_once(__DIR__ . '/pages/_header.php'); ?>

<section id="addmealarticle">
<div class="open open1">食品リストから登録する</div>
<div class="mask hidden"></div>
<div class="open open2">リスト外から登録する</div>
<div class="mask hidden"></div>

</section>

<!-- 食品リストから登録する機能 -->
<div class="modal modal1 hidden">
<section>
  <h2>食品リストから登録する</h2>
  <form action="?action=addmeal" method="post">
    <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
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
        <option value="<?= Utils::h($getgenre->genre); ?>"><?= Utils::h($getgenre->genre); ?></option>
      <?php endforeach; ?>
      </select>
    </li>
    <li>
      <label for="food">食べた物</label>
      <select type="text" name="food" id="food" required>
        <option value="">選択してください</option>
        <?php foreach ($getfoodlist as $getfood): ?>
        <option value="<?= Utils::h($getfood->food); ?>"><?= Utils::h($getfood->food); ?></option>
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
  <div class="close close1">閉じる</div>
</div>
</section>
<!-- リストへの登録をしながら追加する機能。チェックボックスで登録するかしないかを選択。 -->
<div class="modal modal2 hidden">
<section>
  <h2>リスト外から登録する</h2>
  <form action="?action=addmealandlist" method="post">
    <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
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
  <div class="close close2">閉じる</div>
</section>
</div>

<section id="recentmeallist">
  <h2>最近の記録</h2>
  <ul>
    <li>
    <table class="recentmeallist">
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
      <td><?= Utils::h($getrecentmeal->date); ?></td>
      <td><?= Utils::h($getrecentmeal->food); ?></td>
      <td><?=floatval( Utils::h($getrecentmeal->weight)); ?></td>
      <td><?=floatval( Utils::h($getrecentmeal->cal) * Utils::h($getrecentmeal->weight)); ?></td>
      <td><?=floatval( Utils::h($getrecentmeal->pro) * Utils::h($getrecentmeal->weight)); ?></td>
      <td><?=floatval( Utils::h($getrecentmeal->fat) * Utils::h($getrecentmeal->weight)); ?></td>
      <td><?=floatval( Utils::h($getrecentmeal->car) * Utils::h($getrecentmeal->weight)); ?></td>
      <td>
        <form class="deleteform" action="?action=deletemeal" method="post">
          <span class="delete">x</span>
          <input type="hidden" name="id" value="<?= Utils::h($getrecentmeal->id); ?>">
          <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
        </form>
      </td>
      </tr>
      <?php endforeach; ?>
    </table>
    </li>
  </ul>
  <a href="/allmeal.php" class="orange">全ての記録</a>
</section>

<section id="recent7days">
  <h2>直近7日間の平均</h2>
  <table class="recent7days">
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
    <td><?= Utils::h($dateresult->date); ?></td>
    <td><?= Utils::h($dateresult->food); ?></td>
    <td><?= floatval( Utils::h($dateresult->weight)); ?></td>
    <td><?= floatval( Utils::h($dateresult->cal) * Utils::h($dateresult->weight)); ?></td>
    <td><?= floatval( Utils::h($dateresult->pro) * Utils::h($dateresult->weight)); ?></td>
    <td><?= floatval( Utils::h($dateresult->fat) * Utils::h($dateresult->weight)); ?></td>
    <td><?= floatval( Utils::h($dateresult->car) * Utils::h($dateresult->weight)); ?></td>
    <td>
      <form class="deleteform" action="?action=deletemeal" method="post">
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
if (empty($date)) {
  }
elseif (empty($dateresults)) {
  echo '<p>'. $date . 'に該当するデータがありません。</p>';
  }
?>
</section>

<section id="foodlist">
  <h2>マイ食品リスト</h2>
    <label for="genre">ジャンル</label>
    <select type="text" name="genre" id="genre" required>
      <option value="">選択してください</option>
    <?php foreach ($getgenres as $getgenre): ?>
      <option value="<?= Utils::h($getgenre->genre); ?>"><?= Utils::h($getgenre->genre); ?></option>
    <?php endforeach; ?>
    </select>
  <table class="foodlist">
    <tr>
      <th>ジャンル</th>
      <th>食品名</th>
      <th>カロリー</th>
      <th>たんぱく質</th>
      <th>脂質</th>
      <th>炭水化物</th>
      <th></th>
    </tr>
    <?php foreach ($getfoodlist as $getfoodlist): ?>
      <tr>
      <td><?= Utils::h($getfoodlist->genre); ?></td>
      <td><?= Utils::h($getfoodlist->food); ?></td>
      <td><span class="unit-kcal"><?= floatval( Utils::h($getfoodlist->cal)); ?></span></td>
      <td><span class="unit-g"><?= floatval(($getfoodlist->pro)); ?></span></td>
      <td><span class="unit-g"><?= floatval(($getfoodlist->fat)); ?></span></td>
      <td><span class="unit-g"><?= floatval(($getfoodlist->car)); ?></span></td>
      <td class="deletetd">
        <form class="deleteform" action="?action=deletelist" method="post">
          <span class="delete">削除</span>
          <input type="hidden" name="id" value="<?= Utils::h($getfoodlist->id); ?>">
          <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
        </form>
      </td>
      </tr>
      <?php endforeach; ?>
  </table>

<div class="open open3">食品を追加</div>
<div class="mask hidden"></div>
</seciton>

<div class="modal modal3 hidden">
<section>
  <h2>食品を追加</h2>
  <form action="?action=addlist" method="post">
    <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
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
  <div class="close close3">閉じる</div>
</section>
</div>

<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
<script src="/js/meal.js"></script>
</body>
</html>