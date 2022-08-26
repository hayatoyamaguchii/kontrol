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

function calendarbody($pdo)
{
  $year=isset($_GET['year'])?(int)$_GET['year']:date("Y");
  $date=isset($_GET['date'])?(int)$_GET['date']:date("j");
  $month=isset($_GET['month'])?(int)$_GET['month']:date("n");

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

  $stmt = $pdo->query("SELECT * FROM bodycom WHERE DATE_FORMAT(date, '%Y-%m-%d') = DATE_FORMAT('" . $fulldate . "', '%Y-%m-%d');");

  $dateresultsbody = $stmt->fetchAll();
  return $dateresultsbody;
}

function calendarmeal($pdo)
{
  $year=isset($_GET['year'])?(int)$_GET['year']:date("Y");
  $date=isset($_GET['date'])?(int)$_GET['date']:date("j");
  $month=isset($_GET['month'])?(int)$_GET['month']:date("n");

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

  $stmt = $pdo->query("SELECT meal.id, meal.user, meal.date, meal.food, meal.weight, foodlist.genre, foodlist.cal, foodlist.pro, foodlist.fat, foodlist.car FROM meal INNER JOIN foodlist ON meal.food = foodlist.food WHERE DATE_FORMAT(date, '%Y-%m-%d') = DATE_FORMAT('" . $fulldate . "', '%Y-%m-%d');");

  $dateresultsmeal = $stmt->fetchAll();
  return $dateresultsmeal;
}

function calendartraining($pdo)
{
  $year=isset($_GET['year'])?(int)$_GET['year']:date("Y");
  $date=isset($_GET['date'])?(int)$_GET['date']:date("j");
  $month=isset($_GET['month'])?(int)$_GET['month']:date("n");

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

  $stmt = $pdo->query("SELECT * FROM trainings WHERE type = '" . $fulldate . "';");

  $dateresultstraining = $stmt->fetchAll();
  return $dateresultstraining;
}

// calendarここまで
// signupここから

function signupSendmail($pdo)
{
  if (empty($_POST['mail'])) {
      //メールアドレス空欄の場合
      $errors['mail'] = 'メールアドレスが未入力です。';
  }else{
      $mail = $_POST['mail'];
    if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $mail)){
      // メールアドレスの形式が正しくない場合
      $errors['mail_check'] = "メールアドレスの形式が正しくありません。";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM user WHERE mail = :mail;");
        $stmt -> bindValue(':mail', $mail, PDO::PARAM_STR);
        $stmt -> execute();
        $dbcheck = $stmt->fetch(PDO::FETCH_ASSOC);
      if (isset($dbcheck["id"])) {
          // 登録済みメール送信
          mb_language("Japanese"); 
          mb_internal_encoding("UTF-8");
          
          $email = "noreply-kontrol@hayato-yamaguchi.com";
          $subject = "【Kontrol】 既にアカウントが存在しています";
          $body = <<< EOM
          "Kontrol運営です。
          登録を試みましたが、ご利用のメールアドレス $mail は既に登録済みです。
          ログインするか、他のメールアドレスをご利用ください。
          EOM;
          $to = $mail;
          $header = "From: $email";
          
          mb_send_mail($to, $subject, $body, $header);
      } else {
        // 登録可能な場合
          $urltoken = hash('sha256',uniqid(rand(),1));
          $url =SITE_URL . "/signup.php?urltoken=".$urltoken;
          $_SESSION['mail'] = $mail;
          try{
              $sql = "INSERT INTO pre_user (urltoken, mail, created, status) VALUES (:urltoken, :mail, now(), '0')";
              $stmt = $pdo->prepare($sql);
              $stmt->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
              $stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
              $stmt->execute();
              $message = "メールをお送りしました。24時間以内にメールに記載されたURLからご登録下さい。";
          }catch (PDOException $e){
              print('Error:'.$e->getMessage());
              exit();
          }
          // 登録可能メール送信
          mb_language("Japanese"); 
          mb_internal_encoding("UTF-8");
          
          $email = "noreply-kontrol@hayato-yamaguchi.com";
          $subject = "【Kontrol】 会員登録手続き";
          $body = <<< EOM
          Kontrol運営です。
          仮登録が完了いたしました。
          24時間以内に下記URLから本登録へお進みください。
          心当たりのない場合は削除してください。
          {$url}
          EOM;
          $to = $mail;
          $header = "From: $email";
          
          mb_send_mail($to, $subject, $body, $header);
      }
    }
  }
}

function signup($pdo) {
  $mail = h($_SESSION['mail']);
  if ($mail === '') {
    return;
  }
  $password = password_hash(trim(filter_input(INPUT_POST, 'password')), PASSWORD_DEFAULT);
  if ($password === '') {
    return;
  }
  $name = trim(filter_input(INPUT_POST, 'name'));
  if ($name === '') {
    return;
  }
  // userに登録
  $stmt = $pdo->prepare("INSERT INTO user (mail, password, name, created, updated) VALUES (:mail, :password, :name, now(), now());");
  $stmt->bindValue('mail', $mail, PDO::PARAM_STR);
  $stmt->bindValue('password', $password, PDO::PARAM_STR);
  $stmt->bindValue('name', $name, PDO::PARAM_STR);
  $stmt->execute();
  // pre_userのステータスを登録済みに更新
  $stmt = $pdo->prepare("UPDATE pre_user SET status = 1 WHERE mail = :mail;");
  $stmt->bindValue('mail', $mail, PDO::PARAM_STR);
  $stmt->execute();

  // 登録完了メール送信
  mb_language("Japanese"); 
  mb_internal_encoding("UTF-8");
  
  $email = "noreply-kontrol@hayato-yamaguchi.com";
  $subject = "【Kontrol】 会員登録完了のお知らせ";
  $body = <<< EOM
  Kontrol運営です。
  会員登録が完了いたしました。
  EOM;
  $to = $mail;
  $header = "From: $email";
  
  mb_send_mail($to, $subject, $body, $header);
}