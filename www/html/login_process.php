<?php
//登録済みのユーザーであるかチェックする

// 定数ファイルを読み込み
require_once '../conf/const.php';
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';

session_start();

//ログインユーザーである場合、商品一覧ページへリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}

$name = get_post('name');
$password = get_post('password');

$db = get_db_connect();


$user = login_as($db, $name, $password);
//登録済みのユーザーでない場合、エラーメッセージを表示し、ログインページへリダイレクト
if( $user === false){
  set_error('ログインに失敗しました。');
  redirect_to(LOGIN_URL);
}
//登録済みのユーザーであり、管理者である場合は管理ページへ、そうでない場合、商品一覧ページへリダイレクト
set_message('ログインしました。');
if ($user['type'] === USER_TYPE_ADMIN){
  redirect_to(ADMIN_URL);
}
//商品一覧ページへリダイレクト
redirect_to(HOME_URL);