<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

session_start();
if(is_valid_csrf_token($_POST['csrf_token']) === false) {
   redirect_to(LOGIN_URL);
}
//ログインしていたら商品一覧ページにリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}

$name = get_post('name');//ポストされたname情報を取得
$password = get_post('password');//ポストされたパスワード情報を取得

$db = get_db_connect();//PDOを利用してDBに接続


$user = login_as($db, $name, $password);//ログイン情報のチェックを行う関数を利用してユーザ情報を取得
//関数login_asの返り値がfalseであればエラーメッセージ、ログインページにリダイレクト
if( $user === false){
  set_error('ログインに失敗しました。');
  redirect_to(LOGIN_URL);
}
//ログイン成功メッセージ
set_message('ログインしました。');
//ログインしたアカウントが管理者アカウントであれば管理者ページにリダイレクト
if ($user['type'] === USER_TYPE_ADMIN){
  redirect_to(ADMIN_URL);
}
//商品一覧ページにリダイレクト
redirect_to(HOME_URL);