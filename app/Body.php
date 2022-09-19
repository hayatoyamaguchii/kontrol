<?php

class Body
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
    Token::create();
    }

    public function processPost() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      Token::validate();
      $action = filter_input(INPUT_GET, 'action');
    
      switch ($action) {  
        case 'add':
          $this->add($pdo);
          break;
        case 'delete':
          $this->delete($pdo);
          break;
        default:
          exit;
      }
    
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }
  }

  private function add()
{
  $user = trim(filter_input(INPUT_POST, 'user'));
  if ($user === '') {
    return;
  }
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

  $stmt = $this->pdo->prepare("INSERT INTO bodycom (user, date, weight, bodyfat) VALUES (:user, :date, :weight, :bodyfat);");
  $stmt->bindValue('user', $user, PDO::PARAM_STR);
  $stmt->bindValue('date', $date, PDO::PARAM_STR);
  $stmt->bindValue('weight', $weight, PDO::PARAM_STR);
  $stmt->bindValue('bodyfat', $bodyfat, PDO::PARAM_STR);
  $stmt->execute();
}

  private function delete()
{
  $id = filter_input(INPUT_POST, 'id');
  if (empty($id)) {
    return;
  }

  $stmt = $this->pdo->prepare("DELETE FROM bodycom WHERE id = :id");
  $stmt->bindValue('id', $id, PDO::PARAM_INT);
  $stmt->execute();
}

  public function getAll()
{
  $user = $_SESSION['user'];
  
  $stmt = $this->pdo->query("SELECT * FROM bodycom WHERE user = '" . $user . "' ORDER BY date DESC;");
  $recentbody = $stmt->fetchAll();
  return $recentbody;
}

  public function getRecent()
{
  $user = $_SESSION['user'];

  $stmt = $this->pdo->query("SELECT * FROM bodycom  WHERE user = '" . $user . "' ORDER BY date DESC LIMIT 5;");
  $recentbody = $stmt->fetchAll();
  return $recentbody;
}

  public function searchbyDate()
{
  $searchbydate = filter_input(INPUT_GET, 'searchbydate');
  $user = $_SESSION['user'];

  $stmt = $this->pdo->query("SELECT * FROM bodycom  WHERE user = '" . $user . "' AND DATE_FORMAT(date, '%Y-%m-%d') = DATE_FORMAT('" . $searchbydate . "', '%Y-%m-%d');");

  $dateresults = $stmt->fetchAll();
  return $dateresults;
}

}