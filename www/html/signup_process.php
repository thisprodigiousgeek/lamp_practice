<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

//セッション開始
session_start();

//ログイン中であればホームページへリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}

//入力されたユーザーネームを$nameに代入
$name = get_post('name');

//入力されたパスワードを$passwordに代入
$password = get_post('password');

//確認用に入力されたパスワードを$password_confirmationに代入
$password_confirmation = get_post('password_confirmation');

//データベース接続
$db = get_db_connect();

try{

  //signup_view.phpで入力されたデータを#resultに代入
  $result = regist_user($db, $name, $password, $password_confirmation);
  if( $result=== false){

    //入力されたデータが無効の場合
    set_error('ユーザー登録に失敗しました。');

    //新規登録画面へリダイレクト
    redirect_to(SIGNUP_URL);
  }

  //ユーザー登録処理を実行できなかった場合
}catch(PDOException $e){

  //ユーザー登録処理を実行できなかった場合
  set_error('ユーザー登録に失敗しました。');

  //新規登録画面へリダイレクト
  redirect_to(SIGNUP_URL);
}

//新規登録ができた場合
set_message('ユーザー登録が完了しました。');

//セッション変数のセット
login_as($db, $name, $password);

//ホームページへリダイレクト
redirect_to(HOME_URL);