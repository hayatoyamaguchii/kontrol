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

$dateresultsbody = calendarbody($pdo);
$dateresultsmeal = calendarmeal($pdo);
$dateresultstraining = calendartraining($pdo);

// カレンダーの作成
$year=isset($_GET['year'])?(int)$_GET['year']:date("Y");
$month=isset($_GET['month'])?(int)$_GET['month']:date("n");
$date=isset($_GET['date'])?(int)$_GET['date']:date("j");

$before=date("w",mktime(0,0,0,$month,1,$year));
$dayCount=date("t",mktime(0,0,0,$month,1,$year));
$after=6-date("w",mktime(0,0,0,$month,$dayCount,$year));
$total=$before+$dayCount+$after;
$row=$total/7;
$calendar=[];
for($i=0;$i<$row;$i++){
  $temp=[];
  for($j=0;$j<7;$j++){
    if($i===0 && $j<$before || $i===$row-1 && $j>=7-$after){
      $temp[]="";
    }else{
      $maxdate=$i*7+$j+1-$before;
      if(date('Y-m-j')===date('Y-m-j',mktime(0,0,0,$month,$maxdate,$year))){
        $maxdate="*".$maxdate;
      }
      $temp[]=$maxdate;
    }
  }
  $calendar[]=$temp;
}
?>

<body>
<?php require_once(__DIR__ . '/pages/_header.php'); ?>

<section>
  <h2><?=$year?>年<?=$month?>月のカレンダー</h2>
  <p>
    <a class="orange" href="?year=<?=date('Y',mktime(0,0,0,$month-1,$date,$year))?>&month=<?=date('m',mktime(0,0,0,$month-1,$date,$year))?>">前月</a>
    <a class="orange" href="?year=<?=date('Y',mktime(0,0,0,$month+1,$date,$year))?>&month=<?=date('m',mktime(0,0,0,$month+1,$date,$year))?>">翌月</a>
  </p>
  <table class="calendartable">
    <tr>
      <th>日</th>
      <th>月</th>
      <th>火</th>
      <th>水</th>
      <th>木</th>
      <th>金</th>
      <th>土</th>
    </tr>
    <?php foreach($calendar as $tr): ?>
    <tr class="calendardatetr">
      <?php foreach($tr as $td):?>
        <?php if(substr($td,0,1)==="*"): ?>
          <td class="today"><a class="calendardate" href="?year=<?=$year?>&month=<?=$month ?>&date=<?=substr($td,1)?>"><?=substr($td,1)?></a></td>
        <?php else: ?>
          <td><a class="calendardate" href="?year=<?=$year?>&month=<?=$month?>&date=<?=$td?>"><?=$td?></a></td>
        <?php endif;?>
      <?php endforeach;?>
    </tr>
    <?php endforeach;?>
  </table>

<a class="orange" href="?year=<?=date('Y',mktime(0,0,0,$month,$date-1,$year))?>&month=<?=date('m',mktime(0,0,0,$month,$date-1,$year))?>&date=<?=date('j',mktime(0,0,0,$month,$date-1,$year))?>">前の日</a>
<a class="orange" href="?year=<?=date('Y',mktime(0,0,0,$month,$date+1,$year))?>&month=<?=date('m',mktime(0,0,0,$month,$date+1,$year))?>&date=<?=date('j',mktime(0,0,0,$month,$date+1,$year))?>">次の日</a>
</section>

<section>
<h2 class="calendarh2">体組成記録</h2>

<?php 
if (empty($dateresultsbody)) {
  echo '<p>'. $year . '年' . $month . '月' . $date. '日' . 'に該当するデータがありません。</p>';
  }
?>

<?php 
if (!empty($dateresultsbody)): ?>
  <table>
    <tr>
    <th>計測日</th>
    <th>体重</th>
    <th>体脂肪率</th>
    <th>削除</th>
    </tr>

<?php foreach ($dateresultsbody as $dateresult): ?>
    <tr>
    <td><?= Utils::h($dateresult->date); ?></td>
    <td><?= Utils::h($dateresult->weight); ?></td>
    <td><?= Utils::h($dateresult->bodyfat); ?></td>
    <td>
      <form class="deleteform" action="?action=delete&class=body" method="post">
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
<?php endif; ?>

<h2 class="calendarh2">食事記録</h2>

<?php 
if (empty($dateresultsmeal)):
  echo '<p>'. $year . '年' . $month . '月' . $date. '日' . 'に該当するデータがありません。</p>';
?>

<?php else: ?>
  <ul>
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
    <th>削除</th>
    </tr>

<?php foreach ($dateresultsmeal as $dateresult): ?>
    <tr>
    <td><?= Utils::h($dateresult->date); ?></td>
    <td><?= Utils::h($dateresult->food); ?></td>
    <td><?= floatval( Utils::h($dateresult->weight)); ?></td>
    <td><?= floatval( Utils::h($dateresult->cal) * Utils::h($dateresult->weight)); ?></td>
    <td><?= floatval( Utils::h($dateresult->pro) * Utils::h($dateresult->weight)); ?></td>
    <td><?= floatval( Utils::h($dateresult->fat) * Utils::h($dateresult->weight)); ?></td>
    <td><?= floatval( Utils::h($dateresult->car) * Utils::h($dateresult->weight)); ?></td>
    <td>
      <form class="deleteform" action="?action=deletemeal&class=meal" method="post">
        <span class="delete">削除</span>
        <input type="hidden" name="id" value="<?= Utils::h($dateresult->id); ?>">
        <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
      </form>
    </td>
    </tr>
    <?php endforeach; ?>
  </table>

  <section id="calendarmealtotal">
  <h2 class="calendarh2">PFCバランス</h2>
    <table class="calendarmealtotal">
      <tr>
        <th></th>
        <th>カロリー(kcal)</th>
        <th>たんぱく質(g)</th>
        <th>脂質(g)</th>
        <th>炭水化物(g)</th>
      </tr>

<?php
  $calendarcal = 0;
  $calendarpro = 0;
  $calendarfat = 0;
  $calendarcar = 0;
  foreach ($dateresultsmeal as $dateresult)
  {
  $calendarcal += floatval( Utils::h($dateresult->cal) * Utils::h($dateresult->weight));
  $calendarpro += floatval( Utils::h($dateresult->pro) * Utils::h($dateresult->weight));
  $calendarfat += floatval( Utils::h($dateresult->fat) * Utils::h($dateresult->weight));
  $calendarcar += floatval( Utils::h($dateresult->car) * Utils::h($dateresult->weight));
  }
?>
  <tr>
    <th>合計</th>
    <td><?= Utils::h($calendarcal); ?></td>
    <td><?= Utils::h($calendarpro); ?></td>
    <td><?= Utils::h($calendarfat); ?></td>
    <td><?= Utils::h($calendarcar); ?></td>
  </tr>
</table>
</section>
<?php endif; ?>

<h2 class="calendarh2">トレーニング記録</h2>

<?php 
if (empty($dateresultstraining)) {
  echo '<p>'. $year . '年' . $month . '月' . $date. '日' . 'に該当するデータがありません。</p>';
  }
?>

<?php if (!empty($dateresultstraining)): ?>
  <table>
    <tr>
    <th>日時</th>
    <th>部位</th>
    <th>種目</th>
    <th>セット数</th>
    <th>重量</th>
    <th>レップ数</th>
    <th>削除</th>
    </tr>

<?php foreach ($dateresultstraining as $dateresult): ?>
    <tr>
    <td><?= Utils::h($dateresult->date); ?></td>
    <td><?= Utils::h($dateresult->part); ?></td>
    <td><?= Utils::h($dateresult->type); ?></td>
    <td><?= Utils::h($dateresult->sets); ?></td>
    <td><?= Utils::h($dateresult->weight); ?></td>
    <td><?= Utils::h($dateresult->reps); ?></td>
    <td>
      <form class="deleteform" action="?action=delete&class=training" method="post">
        <span class="delete">削除</span>
        <input type="hidden" name="id" value="<?= Utils::h($dateresult->id); ?>">
        <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
      </form>
    </td>
    </tr>
    <?php endforeach; ?>
  </table>
  <section id="totalweight">
<?php
  $totalweight = 0;
  foreach ($dateresultstraining as $dateresult)
  {
  $totalweight += floatval(Utils::h($dateresult->weight) * Utils::h($dateresult->reps));
  }
?>

  <h2 class="calendarh2">総負荷量</h2>
  <p><?= $totalweight; ?>kg</p>
  <?php endif; ?>
</section>

<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
<script src="/js/training.js"></script>
</body>
</html>