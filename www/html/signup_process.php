<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関するファイルを読み込み
require_once MODEL_PATH . 'user.php';

// ログインチェックを行うため、セッションを開始する
session_start();

// ログインチェック用関数を利用
if(is_logined() === true){
  // ログインされていれば、index.phpにリダイレクト
  redirect_to(HOME_URL);
}
// ユーザー名取得関数
$name = get_post('name');
// パスワード取得関数
$password = get_post('password');
// パスワード確認用取得
$password_confirmation = get_post('password_confirmation');

// PDOを取得
$db = get_db_connect();
// 入力した情報をチェックし、データベースに登録
try{
  // ユーザー情報登録関数
  $result = regist_user($db, $name, $password, $password_confirmation);
  if( $result=== false){
    set_error('ユーザー登録に失敗しました。');
    // sighnup.phpにリダイレクト
    redirect_to(SIGNUP_URL);
  }
}catch(PDOException $e){
  set_error('ユーザー登録に失敗しました。');
  redirect_to(SIGNUP_URL);
}
// セッション変数に代入
set_message('ユーザー登録が完了しました。');
// 登録情報をログイン情報に入れ、セッション変数に入れる
login_as($db, $name, $password);
// index.phpにリダイレクト
redirect_to(HOME_URL);