<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';

// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';

// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';

//セッションをスタートする
session_start();

// ログインチェック用関数を利用
if(is_logined() === true){

// ログインしていない場合はログインページにリダイレクト
  redirect_to(HOME_URL);
}

//nameデータを表示
$name = get_post('name');

//passwordデータを表示
$password = get_post('password');

// PDOを取得
$db = get_db_connect();


// PDOを利用してログインユーザーのデータを取得
$user = login_as($db, $name, $password);

//ログインチェック用関数を利用
if( $user === false){

//ログインに失敗した場合下記のメッセージを表示
  h(set_error('ログインに失敗しました。'));

//ログインページに誘導
  redirect_to(LOGIN_URL);
}
//ログインに成功した場合下記のメッセージを表示
h(set_message('ログインしました。'));

//ログインしたユーザーが管理者だった場合の処理
if ($user['type'] === USER_TYPE_ADMIN){

//管理者ページに移動
  redirect_to(ADMIN_URL);
}
is_valid_csrf_token($token);

// ビューの読み込み。 
redirect_to(HOME_URL);