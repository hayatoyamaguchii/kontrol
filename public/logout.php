<?php

require_once(__DIR__ . '/app/config.php');

session_start();
$_SESSION = array();
session_destroy();
header('Location: ' . SITE_URL . '/index.php');
?>