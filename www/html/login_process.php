<?php
//定数ファイルを読み込み
require_once '../conf/const.php';
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';

// ログインチェックのために、sessionを開始
session_start();
// ログインチェック関数を呼び出し
if(is_logined() === true){
  // ログインしていない場合は、index画面にリダイレクト
  redirect_to(HOME_URL);
}
// ユーザ名の取得、postデータ取得用関数の呼び出し
$name = get_post('name');
// pwの取得、postデータ取得用関数の呼び出し
$password = get_post('password');
// ユーザ認証用に、PDOの取得
$db = get_db_connect();

// user登録認証用関数の呼び出し、user情報取得とsessionのセットを実施
$user = login_as($db, $name, $password);
// user認証の結果を判定
if( $user === false){
  set_error('ログインに失敗しました。');
  // ログイン画面にリダイレクト
  redirect_to(LOGIN_URL);
}
// sessionにメッセージを格納する関数の呼び出し
set_message('ログインしました。');
// ユーザtypeがadminかどうか判定
if ($user['type'] === USER_TYPE_ADMIN){
  // adminの場合、管理画面にリダイレクト
  redirect_to(ADMIN_URL);
}
// index画面にリダイレクト
redirect_to(HOME_URL);