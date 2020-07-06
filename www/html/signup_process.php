<?php
// それぞれのページから情報を取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

// セッションを開始
session_start();

// ログインされていればホームページに移動
if(is_logined() === true){
  redirect_to(HOME_URL);
}

// POSTからそれぞれ情報を取得
$name = get_post('name');
$password = get_post('password');
$password_confirmation = get_post('password_confirmation');

// データベースに接続
$db = get_db_connect();

// ユーザーの登録
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

// メッセージを取得
set_message('ユーザー登録が完了しました。');

// ログインを実行
login_as($db, $name, $password);

// ホームページに移動
redirect_to(HOME_URL);