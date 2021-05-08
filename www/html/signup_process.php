<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

session_start();
if(is_valid_csrf_token($_POST['csrf_token']) === false) {
  redirect_to(LOGIN_URL);
}
//ログインしていたら商品一覧ページにリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}

$name = get_post('name');//ポストされたname情報を取得
$password = get_post('password');//ポストされたpassword情報を取得
$password_confirmation = get_post('password_confirmation');//ポストされた確認用password情報の取得

$db = get_db_connect();//PDOを利用してDBに接続

try{
  //ユーザー情報をDBに登録する関数を利用
  $result = regist_user($db, $name, $password, $password_confirmation);
  //失敗したらエラーメッセージ、ユーザー登録ページにリダイレクト
  if( $result=== false){
    set_error('ユーザー登録に失敗しました。');
    redirect_to(SIGNUP_URL);
  }
//DB接続やテーブルへのinsertに失敗した場合、エラーメッセージ、ユーザー登録ページにリダイレクト
}catch(PDOException $e){
  set_error('ユーザー登録に失敗しました。');
  redirect_to(SIGNUP_URL);
}
//登録完了メッセージ
set_message('ユーザー登録が完了しました。');
// ユーザーidをセッションに保存する
login_as($db, $name, $password);
// 商品一覧ページへリダイレクト
redirect_to(HOME_URL);