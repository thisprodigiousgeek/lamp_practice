<?php
//定義ファイル読み込み
require_once '../conf/const.php';
//汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
//userデータに関するファイル読み込み
require_once MODEL_PATH . 'user.php';

//ログインチェックするため、セッション開始
session_start();

//ログインチェック用関数を利用
if(is_logined() === true){
  //ログインしている場合はホームページにリダイレクト
  redirect_to(HOME_URL);
}

//postデータ取得
$name = get_post('name');
$password = get_post('password');
$password_confirmation = get_post('password_confirmation');
$token = get_post('token');

//トークンチェック
if(is_valid_csrf_token($token) === false){
  //ログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

//DB接続
$db = get_db_connect();

try{
  //ユーザー登録
  $result = regist_user($db, $name, $password, $password_confirmation);
  //ユーザー登録に失敗した場合
  if( $result=== false){
    //セッション変数にエラー表示
    set_error('ユーザー登録に失敗しました。');
    //サインアップページにリダイレクト
    redirect_to(SIGNUP_URL);
  }
}catch(PDOException $e){
  //セッション変数にエラー表示
  set_error('ユーザー登録に失敗しました。');
  //サインアップページにリダイレクト
  redirect_to(SIGNUP_URL);
}

//セッション変数のメッセージ表示
set_message('ユーザー登録が完了しました。');
//ユーザー登録されてるか確認
login_as($db, $name, $password);
//ホームページへ遷移
redirect_to(HOME_URL);