<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

session_start();

//ログイン成功時、トップページへ
if(is_logined() === true){
  redirect_to(HOME_URL);
}

//送られてきたnameの値を取得する関数を変数に代入
$name = get_post('name');
//送られてきたpasswordの値を取得する関数を変数に代入
$password = get_post('password');
//送られてきたpassword_confirmationの値を取得する関数を変数に代入
$password_confirmation = get_post('password_confirmation');

//DBに接続する関数を変数に代入
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
redirect_to(HOME_URL);