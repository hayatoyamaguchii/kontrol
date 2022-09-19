<?php

class Meal
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
    Token::create();
  }

  public function processpost()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      Token::validate();
      $action = filter_input(INPUT_GET, 'action');
    
      switch ($action) {  
        case 'addmeal':
          $this->add($pdo);
          break;
        case 'deletemeal':
          $this->delete($pdo);
          break;
        case 'addlist':
          $this->addFoodlist($pdo);
          break;
        case 'changelist':
          $this->changeFoodlist($pdo);
          break;
        case 'deletelist':
          $this->deleteFoodlist($pdo);
          break;
        case 'addmealandlist';
          $this->addFoodlist($pdo);
          $this->add($pdo);
          break;
        default:
          exit;
      }
    
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }
  }

  private function add ()
  {
    $user = trim(filter_input(INPUT_POST, 'user'));
    if ($user === '') {
      return;
    }
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

    $stmt = $this->pdo->prepare("INSERT INTO meal (user, date, food, weight) VALUES (:user, :date, :food, :weight);");
    $stmt->bindValue('user', $user, PDO::PARAM_STR);
    $stmt->bindValue('date', $date, PDO::PARAM_STR);
    $stmt->bindValue('food', $food, PDO::PARAM_STR);
    $stmt->bindValue('weight', $weight, PDO::PARAM_STR);
    $stmt->execute();
  }

  private function delete()
    {
      $id = filter_input(INPUT_POST, 'id');
      if (empty($id)) {
        return;
      }
    
      $stmt = $this->pdo->prepare("DELETE FROM meal WHERE id = :id");
      $stmt->bindValue('id', $id, PDO::PARAM_INT);
      $stmt->execute();
    }

  public function getGenres()
    {
      $user = $_SESSION['user'];

      $stmt = $this->pdo->query("SELECT DISTINCT genre FROM foodlist WHERE user = '" . $user . "' AND hidden = 0");
      $genres = $stmt->fetchAll();
      return $genres;
    }
    
  public function getAll()
    {
      $user = $_SESSION['user'];
      
      $stmt = $this->pdo->query("SELECT meal.id, meal.user, meal.date, meal.food, meal.weight, foodlist.genre, foodlist.cal, foodlist.pro, foodlist.fat, foodlist.car FROM meal INNER JOIN foodlist ON meal.food = foodlist.food WHERE meal.user = '" . $user . "'");
      $getmeals = $stmt->fetchAll();
      return $getmeals;
    }
    
  public function getRecent()
    {
      $user = $_SESSION['user'];

      $stmt = $this->pdo->query("SELECT meal.id, meal.user, meal.date, meal.food, meal.weight, foodlist.genre, foodlist.cal, foodlist.pro, foodlist.fat, foodlist.car FROM meal INNER JOIN foodlist ON meal.food = foodlist.food WHERE meal.user = '" . $user . "' ORDER BY meal.date DESC LIMIT 5");
      $getrecentmeals = $stmt->fetchAll();
      return $getrecentmeals;
    }

    public function getToday()
    {
      $user = $_SESSION['user'];

      $stmt = $this->pdo->query("SELECT meal.id, meal.user, meal.date, meal.food, meal.weight, foodlist.genre, foodlist.cal, foodlist.pro, foodlist.fat, foodlist.car FROM meal INNER JOIN foodlist ON meal.food = foodlist.food WHERE meal.user = '" . $user . "' AND date > DATE_SUB(NOW(), INTERVAL 1 DAY)");
      $gettoday = $stmt->fetchAll();
      return $gettoday;
    }
    
  public function getFoodlist()
    {
      $user = $_SESSION['user'];

      $stmt = $this->pdo->query("SELECT * FROM foodlist WHERE user = '" . $user . "' AND hidden = 0");
      $getfoodlist = $stmt->fetchAll();
      return $getfoodlist;
    }

  public function searchbyDate()
  {
    $searchbydate = filter_input(INPUT_GET, 'searchbydate');
    $user = $_SESSION['user'];

    $stmt = $this->pdo->query("SELECT meal.id, meal.user, meal.date, meal.food, meal.weight, foodlist.genre, foodlist.cal, foodlist.pro, foodlist.fat, foodlist.car FROM meal INNER JOIN foodlist ON meal.food = foodlist.food WHERE  meal.user = '" . $user . "' AND DATE_FORMAT(date, '%Y-%m-%d') = DATE_FORMAT('" . $searchbydate . "', '%Y-%m-%d');");

    $dateresults = $stmt->fetchAll();
    return $dateresults;
  }

  private function addFoodlist($pdo)
{
  $user = trim(filter_input(INPUT_POST, 'user'));
  if ($user === '') {
    return;
  }
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
  $hidden = trim(filter_input(INPUT_POST, 'check'));
  if ($hidden === '') {
    $hidden = 1;
  }

  $stmt = $this->pdo->prepare("INSERT INTO foodlist (user, genre, food, cal, pro, fat, car, hidden) VALUES 
  (:user, :genre, :food, :cal, :pro, :fat, :car, :hidden);");
  $stmt->bindValue('user', $user, PDO::PARAM_STR);
  $stmt->bindValue('genre', $genre, PDO::PARAM_STR);
  $stmt->bindValue('food', $food, PDO::PARAM_STR);
  $stmt->bindValue('cal', $cal, PDO::PARAM_STR);
  $stmt->bindValue('pro', $pro, PDO::PARAM_STR);
  $stmt->bindValue('fat', $fat, PDO::PARAM_STR);
  $stmt->bindValue('car', $car, PDO::PARAM_STR);
  $stmt->bindValue('hidden', $hidden, PDO::PARAM_STR);
  $stmt->execute();
}

private function changeFoodlist($pdo)
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
  $id = filter_input(INPUT_POST, 'id');
  if (empty($id)) {
    return;
  }

  $stmt = $this->pdo->prepare("UPDATE foodlist SET genre = :genre, food = :food, cal = :cal, pro = :pro, fat = :fat, car = :car WHERE id = :id");
  $stmt->bindValue('genre', $genre, PDO::PARAM_STR);
  $stmt->bindValue('food', $food, PDO::PARAM_STR);
  $stmt->bindValue('cal', $cal, PDO::PARAM_STR);
  $stmt->bindValue('pro', $pro, PDO::PARAM_STR);
  $stmt->bindValue('fat', $fat, PDO::PARAM_STR);
  $stmt->bindValue('car', $car, PDO::PARAM_STR);
  $stmt->bindValue('id', $id, PDO::PARAM_INT);
  $stmt->execute();
}

private function deleteFoodlist($pdo) {
  $id = filter_input(INPUT_POST, 'id');
  if (empty($id)) {
    return;
  }

  $stmt = $this->pdo->prepare("UPDATE foodlist SET hidden = 1 WHERE id = :id");
  $stmt->bindValue('id', $id, PDO::PARAM_INT);
  $stmt->execute();
}


}