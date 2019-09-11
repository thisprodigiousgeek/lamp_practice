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
$password_confirmation = get_post('password_confirmation');

// PDOを取得
$db = get_db_connect();

try{
  $result = regist_user($db, $name, $password, $password_confirmation);
  if( $result=== false){
    set_error('ユーザー登録に失敗しました。');
    redirect_to(SIGNUP_URL);
  }
}catch(PDOException $e){
  set_error('ユーザー登録に失敗しました。');
  redirect_to(SIGNUP_URL);
}

set_message('ユーザー登録が完了しました。');
login_as($db, $name, $password);
is_valid_csrf_token($token);
// ビューの読み込み。
redirect_to(HOME_URL);