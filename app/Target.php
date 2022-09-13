<?php

class Target
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
        case 'update':
          $this->update($pdo);
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
    $targetpro = trim(filter_input(INPUT_POST, 'targetpro'));
    if ($targetpro === '') {
      return;
    }
    $targetfat = trim(filter_input(INPUT_POST, 'targetfat'));
    if ($targetfat === '') {
      return;
    }
    $targetcar = trim(filter_input(INPUT_POST, 'targetcar'));
    if ($targetcar === '') {
      return;
    }
    $user = trim(filter_input(INPUT_POST, 'user'));
    if ($user === '') {
      return;
    }
  
    $stmt = $this->pdo->prepare("INSERT INTO target (user, targetpro, targetfat, targetcar) VALUES (:user, :targetpro, :targetfat, :targetcar);");
    $stmt->bindValue('user', $user, PDO::PARAM_STR);
    $stmt->bindValue('targetpro', $targetpro, PDO::PARAM_STR);
    $stmt->bindValue('targetfat', $targetfat, PDO::PARAM_STR);
    $stmt->bindValue('targetcar', $targetcar, PDO::PARAM_STR);
    $stmt->execute();
  }

private function update()
{
  $targetpro = trim(filter_input(INPUT_POST, 'targetpro'));
  if ($targetpro === '') {
    return;
  }
  $targetfat = trim(filter_input(INPUT_POST, 'targetfat'));
  if ($targetfat === '') {
    return;
  }
  $targetcar = trim(filter_input(INPUT_POST, 'targetcar'));
  if ($targetcar === '') {
    return;
  }
  $user = trim(filter_input(INPUT_POST, 'user'));
  if ($user === '') {
    return;
  }

  $stmt = $this->pdo->prepare
  ("UPDATE target SET 
  targetpro = :targetpro,
  targetfat = :targetfat,
  targetcar = :targetcar
  WHERE user = :user;");

  $stmt->bindValue('targetpro', $targetpro, PDO::PARAM_STR);
  $stmt->bindValue('targetfat', $targetfat, PDO::PARAM_STR);
  $stmt->bindValue('targetcar', $targetcar, PDO::PARAM_STR);
  $stmt->bindValue('user', $user, PDO::PARAM_STR);
  $stmt->execute();
}

  public function get()
{
  $user = $_SESSION['user'];

  $stmt = $this->pdo->query("SELECT * FROM target WHERE user = '" . $user . "'");

  $result = $stmt->fetchAll();
  return $result;

}

}