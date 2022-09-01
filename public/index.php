<?php

require_once(__DIR__ . '/app/config.php');
require_once(__DIR__ . '/app/functions.php');

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/css/style.css">
  <title>Kontrol</title>
</head>
<header>
  <div class="header-wrapper">
    <ul class="header-ul">
      <li><h1 class="header-item">
      <a href=""><img src="/img/logo.png" alt="logo" class="header-logo"></a></h1></li>
      <li><a href="" class="header-item">サービス紹介</a></li>
      <li><a href="" class="header-item">使い方</a></li>
      <li><a href="/signup.php" class="header-item">新規会員登録</a></li>
      <li><a href="/login.php" class="header-item">ログイン</a></li>
    </ul>
  </div> 
</header>
<body>
  <div class="hero">
    <strong>KaradaControl</strong>
    <div class="hero-vid">
    <video loop autoplay muted preload playsinline>
      <!-- <source src="/img/hero.mp4" type="video/mp4"> -->
    </video>
    </div>
  </div>
  <div class="hero-cover"></div>
  <section class="section service" id="service">
      <h2 class="section-title">Service</h2>
      <div class="service-wrapper">
        <div class="service-item service-itemA">「目指す自分」</div>
        <div class="service-item service-itemB">「からだの情報」</div>
        <div class="service-item service-itemC">「よくたべるもの」</div>
      </div>
        <p>以上3点から、体づくりをする人の食事サポートをするサービスです。</p>
        <!-- キャッチコピー案
        「徹底的にやろう」 -->
      <h3>食事サポートとは</h3>
        <p>主に三大栄養素のバランス・量を管理することで、筋肉トレーニングやダイエットの効果を最大限に引き出します。</p>
      <h4>三大栄養素の説明入れる（図）</h4>
  </section>
  <section class="section howtouse" id="howtouse">
    <h2 class="section-title">How to use</h2>
      <ol class="howtouse-list">
        <li>
          <img src="/img/howtouse.png" alt="#">
          <h3 class="section-subtitle">目指す自分の設定</h3>
          <ol class="item-list">
            <li>
              <h5>目標体重</h5>
              <p>なりたい体重を設定します。</p>
            </li>
            <li>
              <h5>目標期間</h5>
              <p>達成まで期間を設定します。</p>
            </li>
          </ol>
        </li>
        <li>
          <img src="public/img/howtouse.png" alt="#">
          <h3 class="section-subtitle">からだの情報の設定</h3>
              <ol class="item-list">
                <li>
                  <h5>現在の状態</h5>
                  <p>現在の年齢・身長・体重・性別を設定します。</p>
                </li>
                <li>
                  <h5>運動量</h5>
                  <p>運動量を設定します。</p>
                </li>
              </ol>
        </li>
        <li>
          <img src="public/img/howtouse.png" alt="#">
          <h3 class="section-subtitle">よくたべるものの設定</h3>
              <ol class="item-list">
                <li>
                  <h5>よく食べる物の登録</h5>
                  <p>カロリー、PFCバランスを登録します。</p>
                </li>
                <li>
                  <h5>プリセットも用意されています。</h5>
                  <p>白米やお肉、調味料などの情報が用意されています。</p>
                </li>
              </ol>
        </li>
        <li>
          <img src="public/img/howtouse.png" alt="#">
          <h3 class="section-subtitle">1日の食事内容を記録・確認</h3>
              <ol class="item-list">
                <li>
                  <h5>記録方法</h5>
                  <p>食事内容、体重を登録します。</p>
                </li>
                <li>
                  <h5>1日で摂るべき量の確認</h5>
                  <p>あとどのくらい摂取すべきかが表示されます。</p>
                </li>
              </ol>
        </li>
        <li>
          <img src="public/img/howtouse.png" alt="#">
          <h3 class="section-subtitle">実績の振り返り</h3>
              <ol class="item-list">
                <li>
                  <h5>食事の記録</h5>
                  <p>日々登録した食事の記録が表示されます。</p>
                </li>
                <li>
                  <h5>体重推移</h5>
                  <p>体重の推移が表示されます。</p>
                </li>
              </ol>
        </li>
      </ol>
  </section>
  <section class="section signup" id="signup">
    <h2 class="section-title">Sign up</h2>
    <form action="" method="post">
      <ul>
      <li>
        <label for="mail">メールアドレス</label>
        <input type="text" name="mail" id="mail">
      </li>
      <li>
        <label for="password">パスワード</label>
        <input type="text" name="password" id="password">
      </li>
      <li>
        <button type="submit">登録 / ログイン</button>
      </li>
    </form>
  </section>
<?php require_once(__DIR__ . '/pages/_footer.php'); ?>
</body>
</html>