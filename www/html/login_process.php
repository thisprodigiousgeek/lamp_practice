<?php
//定義ファイル読み込み
require_once '../conf/const.php';
//汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
//userデータに関するファイル読み込み
require_once MODEL_PATH . 'user.php';

//ログインチェックするため、セッション開始
session_start();

//ログインチェック用関数を利用
if(is_logined() === true){
  //ログインしている場合はホームページにリダイレクト
  redirect_to(HOME_URL);
}

//postデータの取得
$name = get_post('name');
$password = get_post('password');
$token = get_post('token');

//データベース接続
$db = get_db_connect();

//ユーザー登録されてるか確認
$user = login_as($db, $name, $password);

//トークンチェック
is_valid_csrf_token($token);

//$userが無かった場合
if($user === false){
  //セッション変数にエラー表示
  set_error('ログインに失敗しました。');
  //ログインページにリダイレクト
  redirect_to(LOGIN_URL);
}
//セッション変数にメッセージ表示
set_message('ログインしました。');
//$userのtypeがUSER_TYPE_ADMINだった場合
if ($user['type'] === USER_TYPE_ADMIN){
  //管理者ページにリダイレクト
  redirect_to(ADMIN_URL);
}

//ホームページへ遷移
redirect_to(HOME_URL);