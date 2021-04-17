<?php
// 定数ファイルの読み込み
require_once '../conf/const.php';
// 汎用関数ファイルの読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'user.php';
// ログインチェックのために、セッションを開始
session_start();
// ログインチェック用の関数を呼び出し
if(is_logined() === true){
  // ログインしていない場合、index画面にリダイレクト
  redirect_to(HOME_URL);
}
// ユーザ名の取得、postデータ取得用関数の呼び出し
$name = get_post('name');
// pwの取得、postデータ取得用関数の呼び出し
$password = get_post('password');
// 確認用pwの取得、postデータ取得用関数の呼び出し
$password_confirmation = get_post('password_confirmation');
// PDOの取得
$db = get_db_connect();

try{
  // 新規ユーザー登録用の関数の呼び出し
  $result = regist_user($db, $name, $password, $password_confirmation);
  if( $result=== false){
    // sessionにメッセージを追加する関数の呼び出し
    set_error('ユーザー登録に失敗しました。');
    // サインアップ画面にリダイレクト
    redirect_to(SIGNUP_URL);
  }
  // 例外処理
}catch(PDOException $e){
  // sessionにメッセージを追加する関数の呼び出し
  set_error('ユーザー登録に失敗しました。');
  // サインアップ画面にリダイレクト
  redirect_to(SIGNUP_URL);
}
// sessionにメッセージを追加する関数の呼び出し
set_message('ユーザー登録が完了しました。');
// ログイン処理用の関数の呼び出し
login_as($db, $name, $password);
// index画面にリダイレクト
redirect_to(HOME_URL);