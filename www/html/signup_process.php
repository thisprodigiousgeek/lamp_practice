<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

//セッションスタート
session_start();

//ログインされている状態ならばホーム画面にリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}

//post送信されたものを取得
$name = get_post('name');
$password = get_post('password');
$password_confirmation = get_post('password_confirmation');

//DB接続
$db = get_db_connect();
//ユーザー登録
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
//nameとpasswordがあっていればセッションに登録
login_as($db, $name, $password);
redirect_to(HOME_URL);