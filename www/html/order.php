<?php 
//主にデータベースへ接続して情報を得る処理を書く。

session_start();

//ログインしていなければ (=ユーザーIDがセッションに保存されていなければ)、ログイン画面へ
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//DBへの接続を行う関数を$dbへ代入
$db = get_db_connect();
//ログインしているユーザーのIDを取得する関数を$userに代入
$user = get_login_user($db);
?>