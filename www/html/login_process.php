<?php
require_once '../conf/const.php';//定義ファイル読み込み
require_once MODEL_PATH . 'functions.php';//関数ファイル読み込み
require_once MODEL_PATH . 'user.php';//関数ファイル読み込み
//セッション開始
session_start();
//$_SESSION['user_id']が存在しなければログイン画面へリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}
//フォームから送られてきた情報を格納
$name = get_post('name');
$password = get_post('password');
//データベース接続
$db = get_db_connect();

//ユーザーが入力したユーザ名から、ユーザー情報を取得。ユーザー情報のパスワードと入力されたパスワードが違っていないかを確認。正しければユーザー情報を$userに、ユーザーIDを $_SESSION['user_id']へ格納
$user = login_as($db, $name, $password);
if( $user === false){
  set_error('ログインに失敗しました。');
  redirect_to(LOGIN_URL);
}
//$_SESSION['__message']に文字列格納。もし、ユーザータイプが管理側であれば管理画面へリダイレクト
set_message('ログインしました。');
if ($user['type'] === USER_TYPE_ADMIN){
  redirect_to(ADMIN_URL);
}
redirect_to(HOME_URL); //ホーム画面へリダイレクト