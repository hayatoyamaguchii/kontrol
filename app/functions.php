<?php

createToken();

try {
  $pdo = new PDO(
    DSN,
    DB_USER,
    DB_PASS,
    [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    ]
  );
} catch (PDOException $e) {
  echo $e->getMessage();
  exit;
}

function h($str)
{
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function createToken()
{
  if(!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
  }
}

function validateToken()
{
  if (
    empty($_SESSION['token']) ||
    $_SESSION['token'] !== filter_input(INPUT_POST, 'token')
  ) {
    exit('Post requestが不正です。');
  }
}

// ここからtraining

function addTrainings($pdo)
{
  // 条件式（$i < 10)を、（次のデータがある場合）に書き換えたい。
  // 現状だと5回繰り返して送信することになっている。
  for($i = 0; $i < 5; $i++) {

  $trainingdate = trim(filter_input(INPUT_POST, 'date'));
  if ($trainingdate === '') {
    return;
  }
  $trainingpart = trim(filter_input(INPUT_POST, 'part'));
  if ($trainingpart === '') {
    return;
  }
  $trainingtype = trim(filter_input(INPUT_POST, 'type'));
  if ($trainingtype === '') {
    return;
  }
  $trainingsets = trim(filter_input(INPUT_POST, 'sets'));
  if ($trainingsets === '') {
    return;
  }
  $trainingweight = trim(filter_input(INPUT_POST, 'weight' . $i));
  if ($trainingweight === '') {
    return;
  }
  $trainingreps = trim(filter_input(INPUT_POST, 'reps' . $i));
  if ($trainingreps === '') {
    return;
  }

  $stmt = $pdo->prepare("INSERT INTO trainings (date, part, type, sets, weight, reps) VALUES (:date, :part, :type, :sets, :weight, :reps)");
  $stmt->bindValue('date', $trainingdate, PDO::PARAM_STR);
  $stmt->bindValue('part', $trainingpart, PDO::PARAM_STR);
  $stmt->bindValue('type', $trainingtype, PDO::PARAM_STR);
  $stmt->bindValue('sets', $trainingsets, PDO::PARAM_STR);
  $stmt->bindValue('weight', $trainingweight, PDO::PARAM_STR);
  $stmt->bindValue('reps', $trainingreps, PDO::PARAM_STR);
  $stmt->execute();
  }
}

function getTrainings($pdo)
{
  $stmt = $pdo->query("SELECT * FROM trainings ORDER BY date DESC");
  $trainings = $stmt->fetchAll();
  return $trainings;
}

function getrecentTrainings($pdo)
{
  $stmt = $pdo->query("SELECT * FROM trainings ORDER BY date DESC LIMIT 5");
  $recenttrainings = $stmt->fetchAll();
  return $recenttrainings;
}

function deleteTrainings($pdo)
{
  $id = filter_input(INPUT_POST, 'id');
  if (empty($id)) {
    return;
  }

  $stmt = $pdo->prepare("DELETE FROM trainings WHERE id = :id");
  $stmt->bindValue('id', $id, PDO::PARAM_INT);
  $stmt->execute();
}

function searchbyDatetraining($pdo)
{
  $searchbydate = filter_input(INPUT_GET, 'searchbydate');

  $stmt = $pdo->query("SELECT * FROM trainings WHERE DATE_FORMAT(date, '%Y-%m-%d') = DATE_FORMAT('" . $searchbydate . "', '%Y-%m-%d');");

  $dateresults = $stmt->fetchAll();
  return $dateresults;
}

function searchbyTypetraining($pdo)
{
  $searchbydate = filter_input(INPUT_GET, 'searchbytype');

  $stmt = $pdo->query("SELECT * FROM trainings WHERE type = '" . $searchbydate . "';");

  $dateresults = $stmt->fetchAll();
  return $dateresults;
}

  //ここまでtraining
  // ここからmeal

  function getGenres($pdo)
{
  $stmt = $pdo->query("SELECT DISTINCT genre FROM foodlist WHERE hidden = 1;");
  $genres = $stmt->fetchAll();
  return $genres;
}

function getMeals($pdo)
{
  $stmt = $pdo->query("SELECT meal.id, meal.user, meal.date, meal.food, meal.weight, foodlist.genre, foodlist.cal, foodlist.pro, foodlist.fat, foodlist.car FROM meal INNER JOIN foodlist ON meal.food = foodlist.food;");
  $getmeals = $stmt->fetchAll();
  return $getmeals;
}

function getrecentMeals($pdo)
{
  $stmt = $pdo->query("SELECT meal.id, meal.user, meal.date, meal.food, meal.weight, foodlist.genre, foodlist.cal, foodlist.pro, foodlist.fat, foodlist.car FROM meal INNER JOIN foodlist ON meal.food = foodlist.food ORDER BY meal.date DESC LIMIT 5;");
  $getrecentmeals = $stmt->fetchAll();
  return $getrecentmeals;
}

function getFoodlist($pdo)
{
  $stmt = $pdo->query("SELECT * FROM foodlist WHERE hidden = 1;");
  $getfoodlist = $stmt->fetchAll();
  return $getfoodlist;
}

function addMeal($pdo)
{
    $date = trim(filter_input(INPUT_POST, 'date'));
    if ($date === '') {
      return;
    }
    $food = trim(filter_input(INPUT_POST, 'food'));
    if ($food === '') {
      return;
    }
    $weight = trim(filter_input(INPUT_POST, 'weight'));
    if ($weight === '') {
      return;
    }

    $stmt = $pdo->prepare("INSERT INTO meal (date, food, weight) VALUES (:date, :food, :weight);");
    $stmt->bindValue('date', $date, PDO::PARAM_STR);
    $stmt->bindValue('food', $food, PDO::PARAM_STR);
    $stmt->bindValue('weight', $weight, PDO::PARAM_STR);
    $stmt->execute();
}

function deleteMeal($pdo)
{
  $id = filter_input(INPUT_POST, 'id');
  if (empty($id)) {
    return;
  }

  $stmt = $pdo->prepare("DELETE FROM meal WHERE id = :id");
  $stmt->bindValue('id', $id, PDO::PARAM_INT);
  $stmt->execute();
}

function searchbyDatemeal($pdo)
{
  $searchbydate = filter_input(INPUT_GET, 'searchbydate');

  $stmt = $pdo->query("SELECT meal.id, meal.user, meal.date, meal.food, meal.weight, foodlist.genre, foodlist.cal, foodlist.pro, foodlist.fat, foodlist.car FROM meal INNER JOIN foodlist ON meal.food = foodlist.food WHERE DATE_FORMAT(date, '%Y-%m-%d') = DATE_FORMAT('" . $searchbydate . "', '%Y-%m-%d');");

  $dateresults = $stmt->fetchAll();
  return $dateresults;
}

function addFoodlist($pdo)
{
  $genre = trim(filter_input(INPUT_POST, 'genre'));
  if ($genre === '') {
    return;
  }
  $food = trim(filter_input(INPUT_POST, 'food'));
  if ($food === '') {
    return;
  }
  $cal = trim(filter_input(INPUT_POST, 'cal')) / filter_input(INPUT_POST, 'weight');
  if ($cal === '') {
    return;
  }
  $pro = trim(filter_input(INPUT_POST, 'pro')) / filter_input(INPUT_POST, 'weight');
  if ($pro === '') {
    return;
  }
  $fat = trim(filter_input(INPUT_POST, 'fat')) / filter_input(INPUT_POST, 'weight');
  if ($fat === '') {
    return;
  }
  $car = trim(filter_input(INPUT_POST, 'car')) / filter_input(INPUT_POST, 'weight');
  if ($car === '') {
    return;
  }

  $stmt = $pdo->prepare("INSERT INTO foodlist (genre, food, cal, pro, fat, car) VALUES 
  (:genre, :food, :cal, :pro, :fat, :car);");
  $stmt->bindValue('genre', $genre, PDO::PARAM_STR);
  $stmt->bindValue('food', $food, PDO::PARAM_STR);
  $stmt->bindValue('cal', $cal, PDO::PARAM_STR);
  $stmt->bindValue('pro', $pro, PDO::PARAM_STR);
  $stmt->bindValue('fat', $fat, PDO::PARAM_STR);
  $stmt->bindValue('car', $car, PDO::PARAM_STR);
  $stmt->execute();
}

function deleteFoodlist($pdo) {
  $id = filter_input(INPUT_POST, 'id');
  if (empty($id)) {
    return;
  }

  $stmt = $pdo->prepare("DELETE FROM foodlist WHERE id = :id");
  $stmt->bindValue('id', $id, PDO::PARAM_INT);
  $stmt->execute();
}

// ここまでmeal
// ここからbody

function getBodycom($pdo)
{
  $stmt = $pdo->query("SELECT * FROM bodycom ORDER BY date DESC;");
  $recentbody = $stmt->fetchAll();
  return $recentbody;
}

function getrecentBodycom($pdo)
{
  $stmt = $pdo->query("SELECT * FROM bodycom ORDER BY date DESC LIMIT 5;");
  $recentbody = $stmt->fetchAll();
  return $recentbody;
}

function addBodycom($pdo)
{
  $date = trim(filter_input(INPUT_POST, 'date'));
  if ($date === '') {
    return;
  }
  $weight = trim(filter_input(INPUT_POST, 'weight'));
  if ($weight === '') {
    return;
  }
  $bodyfat = trim(filter_input(INPUT_POST, 'bodyfat'));
  if ($bodyfat === '') {
    return;
  }

  $stmt = $pdo->prepare("INSERT INTO bodycom (date, weight, bodyfat) VALUES (:date, :weight, :bodyfat);");
  $stmt->bindValue('date', $date, PDO::PARAM_STR);
  $stmt->bindValue('weight', $weight, PDO::PARAM_STR);
  $stmt->bindValue('bodyfat', $bodyfat, PDO::PARAM_STR);
  $stmt->execute();
}

function deleteBodycom($pdo)
{
  $id = filter_input(INPUT_POST, 'id');
  if (empty($id)) {
    return;
  }

  $stmt = $pdo->prepare("DELETE FROM bodycom WHERE id = :id");
  $stmt->bindValue('id', $id, PDO::PARAM_INT);
  $stmt->execute();
}

function searchbyDatebodycom($pdo)
{
  $searchbydate = filter_input(INPUT_GET, 'searchbydate');

  $stmt = $pdo->query("SELECT * FROM bodycom WHERE DATE_FORMAT(date, '%Y-%m-%d') = DATE_FORMAT('" . $searchbydate . "', '%Y-%m-%d');");

  $dateresults = $stmt->fetchAll();
  return $dateresults;
}