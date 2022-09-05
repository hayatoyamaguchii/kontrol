<?php

require_once(__DIR__ . '/app/config.php');

if (!isset($_SESSION['mail'])) {
  header('Location: ' . SITE_URL . '/login.php');
}

?>

<body>
<?php require_once(__DIR__ . '/pages/_header.php'); ?>

<?php if(isset($_SESSION['mail'])) {
	echo ($_SESSION['mail']);
}
?>

<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
</body>
</html>