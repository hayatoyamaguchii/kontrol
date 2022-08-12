<?php

require_once(__DIR__ . '/../app/config.php');
require_once(__DIR__ . '/../app/functions.php');

?>

<?php

$year = date('Y');
$month = date('n');

$last_day = date('j', mktime(0, 0, 0, $month + 1, 0, $year));

$calender = array();
$j = 0;

for ($i = 1; $i < $last_day + 1; $i++) {
  $week = date('w', mktime(0, 0, 0, $month, $i, $year));

}

?>

<body>
<?php require_once(__DIR__ . '/pages/_header.php'); ?>

<?= $last_day ?>
<?= $week ?>

<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
<script src="/public/js/training.js"></script>
</body>
</html>