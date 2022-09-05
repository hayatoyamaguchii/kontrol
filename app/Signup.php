<?php
Token::create();

function signupSendmail($pdo)
{
  if (empty($_POST['mail']) && !preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $mail)) {
    // メールアドレスが正しくない場合
    } else {
        $mail = $_POST['mail'];

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
              $stmt = $pdo->prepare("INSERT INTO pre_user (urltoken, mail, created, status) VALUES (:urltoken, :mail, now(), '0');");
              $stmt->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
              $stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
              $stmt->execute();
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

function signup($pdo) {
  $mail = Utils::h($_SESSION['mail']);
  if ($mail === '') {
    return;
  }
  $password = password_hash(trim(filter_input(INPUT_POST, 'password')), PASSWORD_DEFAULT);
  if ($password === '') {
    return;
  }
  // userに登録
  $stmt = $pdo->prepare("INSERT INTO user (mail, password, created, updated) VALUES (:mail, :password, now(), now());");
  $stmt->bindValue('mail', $mail, PDO::PARAM_STR);
  $stmt->bindValue('password', $password, PDO::PARAM_STR);
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

function login($pdo) {

  $mail = trim($_POST['mail']);
  $password = '';
  if (isset($_POST['password'])) {
  $password = $_POST['password'];
  }

  $stmt = $pdo->prepare("SELECT * FROM user WHERE mail = :mail;");
  $stmt->bindValue(':mail', $mail);
  $stmt->execute();
  $db = $stmt->fetch(PDO::FETCH_ASSOC);
  // dbからパスワードがもらえれば$dbpassに入れる。もらえなければエラーを吐いてリターン。
  if (isset($db['password'])) {$dbpass = $db['password'];
  } else {
    $_GET['state'] = 'error';
    return;
  }
  // パスワードが一致していたらセッションにメールを渡してログイン完了。不一致であればエラーを吐く。
  if (password_verify($password, $dbpass)) {
      //メールアドレスをセッションに保存し、ログイン状態に。
      $_SESSION['mail'] = $mail;
      $_GET['state'] = 'loggedin';
  } else {
      $_GET['state'] = 'error';
  }
}