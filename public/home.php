<?php

require_once(__DIR__ . '/app/config.php');

if (!isset($_SESSION['user'])) {
  header('Location: ' . SITE_URL . '/login.php');
}

$meal = new Meal($pdo);
$gettodaymeals = $meal->getToday();
$getgenres = $meal->getGenres();
$getfoodlist = $meal->getFoodlist();

$training = new Training($pdo);
$gettodaytrainings = $training->getToday();

$body = new Body($pdo);

$target = new Target($pdo);
$gettarget = $target->get();

$targetpro = 0;
$targetfat = 0;
$targetcar = 0;

if (isset($gettarget)) {
  foreach ($gettarget as $target) {
    $targetpro = floatval($target->targetpro);
    $targetfat = floatval($target->targetfat);
    $targetcar = floatval($target->targetcar);
  }} 

if(isset($targetpro, $targetfat, $targetcar)) {
  $targetcal = $targetpro * 4 + $targetfat * 9 + $targetcar * 4;}
  // たんぱく質、炭水化物（4kcal / 1g）、脂質（9kcal / 1g）の計算

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  Token::validate();
  $class = filter_input(INPUT_GET, 'class');

  switch ($class) {  
    case 'meal':
      $meal->processPost();
      break;
    case 'training':
      $training->processPost();
      break;
    case 'body':
      $body->processPost();
      break;
    default:
      exit;
  }
}
?>

<body>
<?php require_once(__DIR__ . '/pages/_header.php'); ?>

<section id="openwrapper">
  <div class="addmealbutton-wrapper">
    <div class="open open1 home-mealopen"><h3 class="button-text">食事記録を登録する</h3><span class="button-subtext">食品リストから</span></div>
    <div class="mask hidden"></div>
    <div class="open open2 home-mealopen"><h3 class="button-text">食事記録を登録する</h3><span class="button-subtext">リスト外から</span></div>
    <div class="mask hidden"></div>
  </div>
  <div class="open open3"><h3 class="button-text">トレーニング記録を登録する</h3></div>
  <div class="mask hidden"></div>
  <div class="open open4"><h3 class="button-text">体組成を登録する</h3></div>
  <div class="mask hidden"></div>
</section>

<div class="modal modal1 hidden">
<section>
  <h2>食品リストから登録する</h2>
  <form action="?action=addmeal&class=meal" method="post">
    <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
    <input type="hidden" name="user" value="<?= Utils::h($_SESSION['user']); ?>">
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
</section>
</div>

<div class="modal modal2 hidden">
<section>
  <h2>リスト外から登録する</h2>
  <form action="?action=addmealandlist&class=meal" method="post">
    <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
    <input type="hidden" name="user" value="<?= Utils::h($_SESSION['user']); ?>">
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
      <input type="checkbox" name="check" id="check" value="0">
    </li>
    <li>
      <button type="submit">送信</button>
    </li>
    </ul>
  </form>
  <div class="close close2">閉じる</div>
</section>
</div>

<div class="modal modal3 hidden">
<section>
<section>
  <h2>トレーニング記録を登録する</h2>
  <form action="?action=add&class=training" method="post">
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
  <div class="close3 close">閉じる</div>
</section>
</div>

<div class="modal modal4 hidden">
<section>
  <h2>体組成を登録する</h2>
  <form action="?action=add&class=body" method="post">
    <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
    <input type="hidden" name="user" value="<?= Utils::h($_SESSION['user']); ?>">
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
  <div class="close close4">閉じる</div>
</section>
</div>

<section class="home-flexwrapper">
<section id="todaymealtotal">
  <h2>本日のマクロ栄養素合計量</h2>
    <table class="todaymealtotal">
      <tr>
        <th></th>
        <th>カロリー(kcal)</th>
        <th>たんぱく質(g)</th>
        <th>脂質(g)</th>
        <th>炭水化物(g)</th>
      </tr>

<?php
  $todaycal = 0;
  $todaypro = 0;
  $todayfat = 0;
  $todaycar = 0;
  foreach ($gettodaymeals as $gettodaymeal)
  {
  $todaycal += floatval( Utils::h($gettodaymeal->cal) * Utils::h($gettodaymeal->weight));
  $todaypro += floatval( Utils::h($gettodaymeal->pro) * Utils::h($gettodaymeal->weight));
  $todayfat += floatval( Utils::h($gettodaymeal->fat) * Utils::h($gettodaymeal->weight));
  $todaycar += floatval( Utils::h($gettodaymeal->car) * Utils::h($gettodaymeal->weight));
  }
?>
  <tr>
    <th>現在摂取量</th>
    <td><?= Utils::h($todaycal); ?></td>
    <td><?= Utils::h($todaypro); ?></td>
    <td><?= Utils::h($todayfat); ?></td>
    <td><?= Utils::h($todaycar); ?></td>
  </tr>
  <tr>
    <th>目標摂取量
    </th>
    <td><?= Utils::h($targetcal); ?></td>
    <td><?= Utils::h($targetpro); ?></td>
    <td><?= Utils::h($targetfat); ?></td>
    <td><?= Utils::h($targetcar); ?></td>
  </tr>
  <tr>
    <th>差分</th>
    <td><?= Utils::h($todaycal) - Utils::h($targetcal); ?></td>
    <td><?= Utils::h($todaypro) - Utils::h($targetpro); ?></td>
    <td><?= Utils::h($todayfat) - Utils::h($targetfat); ?></td>
    <td><?= Utils::h($todaycar) - Utils::h($targetcar); ?></td>
  </tr>
</table>
</section>

<section id="todaytotalweight">
<?php
  $todaytotalweight = 0;
  foreach ($gettodaytrainings as $gettodaytraining)
  {
  $todaytotalweight += floatval(Utils::h($gettodaytraining->weight) * Utils::h($gettodaytraining->reps));
  }
?>

<h2>本日の総負荷量</h2>
<p class="todaytotalweight"><?= $todaytotalweight; ?>kg</p>
</section>
</section>

<section id="todaymeallist">
  <h2>食事</h2>
  <ul>
    <li>
    <table class="todaymeallist">
      <tr>
        <th>食事した日</th>
        <th>食べた物</th>
        <th>量(g / 個)</th>
        <th>カロリー(kcal)</th>
        <th>たんぱく質(g)</th>
        <th>脂質(g)</th>
        <th>炭水化物(g)</th>
        <th></th>
      </tr>
      <?php foreach ($gettodaymeals as $gettodaymeal): ?>
      <tr>
      <td><?= Utils::h($gettodaymeal->date); ?></td>
      <td><?= Utils::h($gettodaymeal->food); ?></td>
      <td><?=floatval( Utils::h($gettodaymeal->weight)); ?></td>
      <td><?=floatval( Utils::h($gettodaymeal->cal) * Utils::h($gettodaymeal->weight)); ?></td>
      <td><?=floatval( Utils::h($gettodaymeal->pro) * Utils::h($gettodaymeal->weight)); ?></td>
      <td><?=floatval( Utils::h($gettodaymeal->fat) * Utils::h($gettodaymeal->weight)); ?></td>
      <td><?=floatval( Utils::h($gettodaymeal->car) * Utils::h($gettodaymeal->weight)); ?></td>
      <td>
        <form class="deleteform" action="?action=deletemeal&class=meal" method="post">
          <span class="delete">削除</span>
          <input type="hidden" name="id" value="<?= Utils::h($gettodaymeal->id); ?>">
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

<section id="todaytrainings">
  <h2>トレーニング</h2>
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
        <th></th>
      </tr>
      <?php foreach ($gettodaytrainings as $gettodaytraining): ?>
      <tr>
      <td><?= Utils::h($gettodaytraining->date); ?></td>
      <td><?= Utils::h($gettodaytraining->part); ?></td>
      <td><?= Utils::h($gettodaytraining->type); ?></td>
      <td><?= Utils::h($gettodaytraining->sets); ?></td>
      <td><?= Utils::h($gettodaytraining->weight); ?></td>
      <td><?= Utils::h($gettodaytraining->reps); ?></td>
      <td>
        <form class="deleteform" action="?action=delete&class=training" method="post">
          <span class="delete">削除</span>
          <input type="hidden" name="id" value="<?= Utils::h($gettodaytraining->id); ?>">
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

<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
</body>
</html>