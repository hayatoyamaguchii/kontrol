<?php

require_once(__DIR__ . '/app/config.php');

if (!isset($_SESSION['user'])) {
  header('Location: ' . SITE_URL . '/login.php');
}

$target = new Target($pdo);
$target->processPost();
$gettarget = $target->get();

if (isset($gettarget)) {
foreach ($gettarget as $target) {
  $targetpro = $target->targetpro;
  $targetfat = $target->targetfat;
  $targetcar = $target->targetcar;
}}
?>

<body>
<?php require_once(__DIR__ . '/pages/_header.php'); ?>

<h2>目標カロリー量</h2>
<p><?php 
if(isset($targetpro, $targetfat, $targetcar)) {
$targetcal = $targetpro * 4 + $targetfat * 9 + $targetcar * 4;
echo($targetcal);
} else {
  echo('登録されていません');
}
?></p>

<form action="<?php if(isset($targetpro, $targetfat, $targetcar)) {
echo("?action=update"); 
} else {
echo("?action=add"); 
}?>" method="post">
  <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
  <input type="hidden" name="user" value="<?= Utils::h($_SESSION['user']); ?>">

  <h2>目標たんぱく質量</h2>
  <li>
    <label for="targetpro">目標を変更する</label>
    <input type="text" name="targetpro" id="targetpro" require>
  </li>
  <p>
  <?php 
  if(!empty($targetpro)){
    echo($targetpro);
  } else {
    echo('登録されていません');
  }
  ?><p>

  <h2>目標脂質量</h2>
  <li>
    <label for="targetfat">目標を変更する</label>
    <input type="text" name="targetfat" id="targetfat" require>
  </li>
  <p>
  <?php 
  if(!empty($targetfat)){
    echo($targetfat);
  } else {
    echo('登録されていません');
  }
  ?><p>

  <h2>目標炭水化物量</h2>
  <li>
    <label for="targetcar">目標を変更する</label>
    <input type="text" name="targetcar" id="targetcar" require>
  </li>
  <p>
  <?php 
  if(!empty($targetcar)){
    echo($targetcar);
  } else {
    echo('登録されていません');
  }
  ?><p>
    <li>
      <button type="submit">送信</button>
    </li>
</form>

<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
</body>
</html>