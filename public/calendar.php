<?php

require_once(__DIR__ . '/../app/config.php');
require_once(__DIR__ . '/../app/functions.php');

$dateresultsbody = calendarbody($pdo);
// $dateresultsmeal = 
// $dateresultstraining = 

//クエリパラメータが来ていたらその年月、そうでなければ実行日の年月とする。
//date("Y")は2021など4桁,date("y")は21と二桁
//date("n")は1~12,date("m")は01~12と0埋めされる
$year=isset($_GET['year'])?(int)$_GET['year']:date("Y");
$month=isset($_GET['month'])?(int)$_GET['month']:date("n");
$date=isset($_GET['date'])?(int)$_GET['date']:date("j");

//1日の曜日の戻り値(0:日曜,1:月...6:土)から最初の空白の数を計算(日なら0)
$before=date("w",mktime(0,0,0,$month,1,$year));
//date("t")でその月の日数
$dayCount=date("t",mktime(0,0,0,$month,1,$year));
//最後の日以降の空白の数
$after=6-date("w",mktime(0,0,0,$month,$dayCount,$year));
$total=$before+$dayCount+$after;
//幅が7の表にしたときの行数
$row=$total/7;
//空の配列を作成（最終的に2次元配列になる)
$calendar=[];
for($i=0;$i<$row;$i++){
  //空の配列を作成
  $temp=[];
  for($j=0;$j<7;$j++){
    if($i===0 && $j<$before || $i===$row-1 && $j>=7-$after){
      //空白の部分は空文字を入れる
      $temp[]="";
    }else{
      $maxdate=$i*7+$j+1-$before;
      //年月日が一致すればdate("j")は1~31 date("d")は01~31
      if(date('Y-m-j')===date('Y-m-j',mktime(0,0,0,$month,$maxdate,$year))){
        $maxdate="*".$maxdate;
      }
      //そうでなければ日付を入れる(今日には先頭に*がつく)
      $temp[]=$maxdate;
    }
  }
  //calendar配列に追加
  $calendar[]=$temp;
}
?>

<body>
<?php require_once(__DIR__ . '/pages/_header.php'); ?>

    <h1><?=$year?>年<?=$month?>月のカレンダー</h1>
    <p>
      <a href="?year=<?php if($month===1){echo $year - 1; } else {echo $year;}?>&month=<?php if($month===1){echo $month + 11;} else {echo $month - 1;}?>">前月</a>
      <a href="?year=<?php if($month===12){echo $year + 1; } else {echo $year;}?>&month=<?php if($month===12){echo $month - 11;} else {echo $month + 1;}?>">翌月</a>

    </p>
    <table border="1">
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
      <tr>
        <?php foreach($tr as $td):?>
          <?php if(substr($td,0,1)==="*"): ?>
            <td class="today"><a href="?year=<?=$year?>&month=<?=$month ?>&date=<?=substr($td,1)?>"><?=substr($td,1)?></a></td>
          <?php else: ?>
            <td><a href="?year=<?=$year?>&month=<?=$month?>&date=<?=$td?>"><?=$td?></a></td>
          <?php endif;?>
        <?php endforeach;?>
      </tr>
      <?php endforeach;?>
    </table>

<?php 
if (!empty($dateresultsbody)) {
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

<?php foreach ($dateresultsbody as $dateresult): ?>
    <tr>
    <td><?= h($dateresult->date); ?></td>
    <td><?= h($dateresult->weight); ?></td>
    <td><?= h($dateresult->bodyfat); ?></td>
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
if (empty($dateresultsbody)) {
  echo '<p>'. $year . '年' . $month . '月' . $date. '日' . 'に該当するデータがありません。</p>';
  }
?>

<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
<script src="/public/js/training.js"></script>
</body>
</html>