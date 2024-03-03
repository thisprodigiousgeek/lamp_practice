<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

//セッション開始
session_start();

//postで送信されたtokenの取得
$token = get_post('token');

//セッションに保存されている'csrf_token'とpostから受け取った$tokenの値が一致しているか確認
if(is_valid_csrf_token($token) === false){

  //一致していなければログインページへリダイレクトし、ログインを要求する
  redirect_to(LOGIN_URL);

}

//一致していればトークンの削除
unset($_SESSION['csrf_token']);


//ログイン中ならホームページへリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}

//入力されたユーザー名を$nameに代入
$name = get_post('name');

//入力されたパスワードを$passwordに代入
$password = get_post('password');

//データベースに接続
$db = get_db_connect();

//登録していないユーザーの場合
$user = login_as($db, $name, $password);
if( $user === false){
  set_error('ログインに失敗しました。');
  redirect_to(LOGIN_URL);
}

//ログインユーザーが管理者の場合
set_message('ログインしました。');
if ($user['type'] === USER_TYPE_ADMIN){
  redirect_to(ADMIN_URL);
}

//ホームページへリダイレクト
redirect_to(HOME_URL);