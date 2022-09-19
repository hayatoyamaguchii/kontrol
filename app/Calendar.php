<?php
Token::create();

function calendarbody($pdo)
{
  $year=isset($_GET['year'])?(int)$_GET['year']:date("Y");
  $date=isset($_GET['date'])?(int)$_GET['date']:date("j");
  $month=isset($_GET['month'])?(int)$_GET['month']:date("n");
  $user = $_SESSION['user'];

  if (!isset($year)){
    $year = date("Y");
  }

  if (strlen($month) === 1) {
    $month = 0 . $month;
  } 

  if (strlen($date) === 1) {
    $date = 0 . $date;
  } 

  $fulldate = $year . $month . $date;

  $stmt = $pdo->query("SELECT * FROM bodycom WHERE user = '" . $user . "' AND DATE_FORMAT(date, '%Y-%m-%d') = DATE_FORMAT('" . $fulldate . "', '%Y-%m-%d');");

  $dateresultsbody = $stmt->fetchAll();
  return $dateresultsbody;
}

function calendarmeal($pdo)
{
  $year=isset($_GET['year'])?(int)$_GET['year']:date("Y");
  $date=isset($_GET['date'])?(int)$_GET['date']:date("j");
  $month=isset($_GET['month'])?(int)$_GET['month']:date("n");
  $user = $_SESSION['user'];

  if (!isset($year)){
    $year = date("Y");
  }

  if (strlen($month) === 1) {
    $month = 0 . $month;
  } 

  if (strlen($date) === 1) {
    $date = 0 . $date;
  } 

  $fulldate = $year . $month . $date;

  $stmt = $pdo->query("SELECT meal.id, meal.user, meal.date, meal.food, meal.weight, foodlist.genre, foodlist.cal, foodlist.pro, foodlist.fat, foodlist.car FROM meal INNER JOIN foodlist ON meal.food = foodlist.food  WHERE meal.user = '" . $user . "' AND DATE_FORMAT(date, '%Y-%m-%d') = DATE_FORMAT('" . $fulldate . "', '%Y-%m-%d');");

  $dateresultsmeal = $stmt->fetchAll();
  return $dateresultsmeal;
}

function calendartraining($pdo)
{
  $year=isset($_GET['year'])?(int)$_GET['year']:date("Y");
  $date=isset($_GET['date'])?(int)$_GET['date']:date("j");
  $month=isset($_GET['month'])?(int)$_GET['month']:date("n");
  $user = $_SESSION['user'];

  if (!isset($year)){
    $year = date("Y");
  }

  if (strlen($month) === 1) {
    $month = 0 . $month;
  } 

  if (strlen($date) === 1) {
    $date = 0 . $date;
  } 

  $fulldate = $year . $month . $date;

  $stmt = $pdo->query("SELECT * FROM trainings  WHERE user = '" . $user . "' AND date = '" . $fulldate . "';");

  $dateresultstraining = $stmt->fetchAll();
  return $dateresultstraining;
}