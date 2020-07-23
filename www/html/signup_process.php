<?php
// 設定ファイル読込
require_once '../conf/const.php';
// 関数ファイル読込
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
// セッション開始
session_start();
// ログインしていない場合ログイン画面にリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}
// POST値取得
$name = get_post('name');
$password = get_post('password');
$password_confirmation = get_post('password_confirmation');
// DB接続
$db = get_db_connect();
try{
  // ユーザ登録
  $result = regist_user($db, $name, $password, $password_confirmation);
  if( $result=== false){
    // 異常メッセージ
    set_error('ユーザー登録に失敗しました。');
    // サインアップ画面にリダイレクト
    redirect_to(SIGNUP_URL);
  }
}catch(PDOException $e){
  // 異常メッセージ
  set_error('ユーザー登録に失敗しました。');
  // サインアップ画面にリダイレクト
  redirect_to(SIGNUP_URL);
}
// 正常メッセージ
set_message('ユーザー登録が完了しました。');
// ログイン処理
login_as($db, $name, $password);
// ホームページにリダイレクト
redirect_to(HOME_URL);