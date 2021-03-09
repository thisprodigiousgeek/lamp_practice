<?php
//定義ファイル読み込み
require_once '../conf/const.php';
//関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
//ユーザーファイル読み込み
require_once MODEL_PATH . 'user.php';

//セッションスタート
session_start();

//ログインされたらホームページへ
if(is_logined() === true){
  redirect_to(HOME_URL);
}

//名前、パスワード、確認パスワードのポスト受け取り
$name = get_post('name');
$password = get_post('password');
$password_confirmation = get_post('password_confirmation');

//DB接続
$db = get_db_connect();

try{
  //登録したユーザー接続
  $result = regist_user($db, $name, $password, $password_confirmation);
  //登録失敗したら登録画面へ
  if( $result=== false){
    set_error('ユーザー登録に失敗しました。');
    redirect_to(SIGNUP_URL);
  }
}catch(PDOException $e){
  set_error('ユーザー登録に失敗しました。');
  redirect_to(SIGNUP_URL);
}

set_message('ユーザー登録が完了しました。');
//ログイン処理
login_as($db, $name, $password);
//ホームページへ
redirect_to(HOME_URL);