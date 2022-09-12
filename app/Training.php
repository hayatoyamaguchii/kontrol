<?php

class Training
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
    Token::create();
  }

  public function processPost()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      Token::validate();
      $action = filter_input(INPUT_GET, 'action');
    
      switch ($action) {
        case 'add':
          $this->add();
          break;
        case 'delete':
          $this->delete();
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

  $stmt = $this->pdo->prepare("INSERT INTO trainings (date, part, type, sets, weight, reps) VALUES (:date, :part, :type, :sets, :weight, :reps)");
  $stmt->bindValue('date', $trainingdate, PDO::PARAM_STR);
  $stmt->bindValue('part', $trainingpart, PDO::PARAM_STR);
  $stmt->bindValue('type', $trainingtype, PDO::PARAM_STR);
  $stmt->bindValue('sets', $trainingsets, PDO::PARAM_STR);
  $stmt->bindValue('weight', $trainingweight, PDO::PARAM_STR);
  $stmt->bindValue('reps', $trainingreps, PDO::PARAM_STR);
  $stmt->execute();
  }
}

  private function delete()
{
  $id = filter_input(INPUT_POST, 'id');
  if (empty($id)) {
    return;
  }

  $stmt = $this->pdo->prepare("DELETE FROM trainings WHERE id = :id");
  $stmt->bindValue('id', $id, PDO::PARAM_INT);
  $stmt->execute();
}

  public function getAll()
  {
    $stmt = $this->pdo->query("SELECT * FROM trainings ORDER BY date DESC");
    $getall = $stmt->fetchAll();
    return $getall;
  }

  public function getRecent()
  {
    $stmt = $this->pdo->query("SELECT * FROM trainings ORDER BY date DESC LIMIT 5");
    $getrecent = $stmt->fetchAll();
    return $getrecent;
  }

  public function getToday()
  {
    $stmt = $this->pdo->query("SELECT * FROM trainings WHERE date > DATE_SUB(NOW(), INTERVAL 1 DAY)");
    $gettoday = $stmt->fetchAll();
    return $gettoday;
  }

  public function getByDate()
  {
    $date = filter_input(INPUT_GET, 'searchbydate');

    $stmt = $this->pdo->query("SELECT * FROM trainings WHERE DATE_FORMAT(date, '%Y-%m-%d') = DATE_FORMAT('" . $date . "', '%Y-%m-%d');");
  
    $searchbydate = $stmt->fetchAll();
    return $searchbydate;
  }

  public function getByType()
  {
    $type = filter_input(INPUT_GET, 'searchbytype');

    $stmt = $this->pdo->query("SELECT * FROM trainings WHERE type = '" . $type . "';");
  
    $searchbytype = $stmt->fetchAll();
    return $searchbytype;
  }

}