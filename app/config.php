<?php

session_start();

define('DSN', 'mysql:host=localhost;dbname=yamaguchihayato_kontrol;charset=utf8mb4');
define('DB_USER', 'yamaguchihayato_kontrol');
define('DB_PASS', 'dbpass');
define('SITE_URL', 'https://kontrol.hayato-yamaguchi.com');

require_once(__DIR__ . '/Utils.php');
require_once(__DIR__ . '/Token.php');
require_once(__DIR__ . '/Database.php');
require_once(__DIR__ . '/Training.php');

$pdo = Database::getInstance();