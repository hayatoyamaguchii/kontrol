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

<section>
<h2>目標カロリー量</h2>
<li class="nowtarget unit-kcal"><?php 
if(isset($targetpro, $targetfat, $targetcar)) {
$targetcal = $targetpro * 4 + $targetfat * 9 + $targetcar * 4;
echo($targetcal);
} else {
  echo('登録されていません');
}
?></li>

<form action="<?php if(isset($targetpro, $targetfat, $targetcar)) {
echo("?action=update"); 
} else {
echo("?action=add"); 
}?>" method="post">
  <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
  <input type="hidden" name="user" value="<?= Utils::h($_SESSION['user']); ?>">

  <h2>目標たんぱく質量</h2>
  <div class="targetwrapper">
  <li class="nowtarget unit-g">
    <?php 
    if(!empty($targetpro)){
      echo(floatval($targetpro));
    } else {
      echo('登録されていません');
    }
    ?>
    </li>
    <li>
      <label for="targetpro">目標を変更する</label>
      <input type="text" name="targetpro" id="targetpro" required>
    </li>
  </div>

  <h2>目標脂質量</h2>
  <div class="targetwrapper">
  <li class="nowtarget unit-g">
    <?php 
    if(!empty($targetfat)){
      echo(floatval($targetfat));
    } else {
      echo('登録されていません');
    }
    ?></li>
    <li>
      <div>
        <label for="targetfat">目標を変更する</label>
      <input type="text" name="targetfat" id="targetfat" required>
    </li>
  </div>

  <h2>目標炭水化物量</h2>
  <div class="targetwrapper">
    <li class="nowtarget unit-g">
    <?php
    if(!empty($targetcar)){
      echo(floatval($targetcar));
    } else {
      echo('登録されていません');
    }
    ?></li>
    <li>
      <label for="targetcar">目標を変更する</label>
      <input type="text" name="targetcar" id="targetcar" required>
    </li>
  </div>
  <li>
    <button type="submit">送信</button>
  </li>
</form>
</section>

<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
</body>
</html>