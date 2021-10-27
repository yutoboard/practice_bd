&lt;?php
require_once('config.php');
//データベースへ接続、テーブルがない場合は作成
try {
  $pdo = new PDO(DSN, DB_USER, DB_PASS);
  $pdo-&gt;setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo-&gt;exec("create table if not exists userDeta(
      id int not null auto_increment primary key,
      email varchar(255),
      password varchar(255),
      created timestamp not null default current_timestamp
    )");
} catch (Exception $e) {
  echo $e-&gt;getMessage() . PHP_EOL;
}
//メールアドレスのバリデーション
if (!$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  echo '入力された値が不正です。';
  return false;
}
//正規表現でパスワードをバリデーション
if (preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $_POST['password'])) {
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
} else {
  echo 'パスワードは半角英数字をそれぞれ1文字以上含んだ8文字以上で設定してください。';
  return false;
}
//データベース内のメールアドレスを取得
$stmt = $pdo-&gt;prepare("select email from userDeta where email = ?");
$stmt-&gt;execute([$email]);
$row = $stmt-&gt;fetch(PDO::FETCH_ASSOC);
//データベース内のメールアドレスと重複していない場合、登録する。
if (!isset($row['email'])) {
  $stmt = $pdo-&gt;prepare("insert into userDeta(email, password) value(?, ?)");
  $stmt-&gt;execute([$email, $password]);
?&gt;
&lt;link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet"&gt;
&lt;link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css"&gt;
&lt;link rel="stylesheet" href="shinjin.css"&gt;
&lt;body id="log_body"&gt;
  &lt;main class="main_log"&gt;
    &lt;p&gt;登録完了&lt;/p&gt;
  &lt;h1 style="text-align:center;margin-top: 0em;margin-bottom: 1em;" class="h1_log"&gt;ログインしてください&lt;/h1&gt;
  &lt;form action="login.php" method="post" class="form_log"&gt;
    &lt;!--&lt;label for="email" class="label"&gt;メールアドレス&lt;/label&gt;&lt;br&gt;--&gt;
    &lt;input type="email" name="email" class="textbox un" placeholder="メールアドレス"&gt;&lt;br&gt;
    &lt;!--&lt;label for="password" class="label"&gt;パスワード&lt;/label&gt;&lt;br&gt;--&gt;
    &lt;input type="password" name="password" class="textbox pass" placeholder="パスワード"&gt;&lt;br&gt;
    &lt;button type="submit" class="log_button"&gt;ログインする&lt;/button&gt;
  &lt;/form&gt;
  &lt;p align="center"&gt;戻るボタンや更新ボタンを押さないでください&lt;/p&gt;
&lt;/body&gt;
&lt;?php
}else {
?&gt;
&lt;link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet"&gt;
&lt;link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css"&gt;
&lt;link rel="stylesheet" href="style.css"&gt;
&lt;body id="log_body"&gt;
  &lt;main class="main_log"&gt;
  &lt;p&gt;既に登録されたメールアドレスです&lt;/p&gt;
  &lt;h1 style="text-align:center;margin-top: 0em;margin-bottom: 1em;" class="h1_log"&gt;初めての方はこちら&lt;/h1&gt;
  &lt;form action="register.php" method="post" class="form_log"&gt;
    &lt;!--&lt;label for="email" class="label"&gt;メールアドレス&lt;/label&gt;&lt;br&gt;--&gt;
    &lt;input type="email" name="email" class="textbox un" placeholder="メールアドレス"&gt;&lt;br&gt;
    &lt;!--&lt;label for="password" class="label"&gt;パスワード&lt;/label&gt;&lt;br&gt;--&gt;
    &lt;input type="password" name="password" class="textbox pass" placeholder="パスワード"&gt;&lt;br&gt;
    &lt;button type="submit" class="log_button"&gt;新規登録する&lt;/button&gt;
    &lt;p style="text-align:center;margin-top: 1.5em;"&gt;※パスワードは半角英数字をそれぞれ１文字以上含んだ、８文字以上で設定してください。&lt;/p&gt;
  &lt;/form&gt;
&lt;/main&gt;
&lt;/body&gt;
&lt;?php
return false;
}
?&gt;