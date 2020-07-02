<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

session_start();

// ログイン済みか確認し、trueならtopページへリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}

// post送信されたnameを変数に格納する
$name = get_post('name');
// post送信されたpasswordを変数に格納する
$password = get_post('password');
// post送信されたpassword(確認用)を変数に格納する
$password_confirmation = get_post('password_confirmation');

// DB接続
$db = get_db_connect();

// 新規ユーザー登録
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
redirect_to(HOME_URL);