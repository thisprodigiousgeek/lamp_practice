<?php
//ユーザー新規登録ページ

//定数ファイルを読み込み
require_once '../conf/const.php';
//汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
//userファイルを読み込み
require_once MODEL_PATH . 'user.php';

session_start();

//ログインユーザであれば、商品一覧ページへリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}


$name = get_post('name');
$password = get_post('password');
$password_confirmation = get_post('password_confirmation');

$db = get_db_connect();

//DB登録済みのユーザかチェック。未登録であれば、エラーメッセージを表示し、登録画面へリダイレクト
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

//登録ユーザーであれば、商品一覧へリダイレクト
set_message('ユーザー登録が完了しました。');
login_as($db, $name, $password);
redirect_to(HOME_URL);