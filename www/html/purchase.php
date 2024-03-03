<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'purchase.php';

//セッション開始
session_start();

//ログイン中ではなければログインページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//データベースへ接続
$db = get_db_connect();

//ログイン中のユーザーのユーザーidを取得
$user = get_login_user($db);

//purchase_historyからデータを取得 引数は$dbと$user['user_id']

//adminの場合は全部取得。
if(is_admin($user) === true){

  $histories = get_purchase_histories($db);

//通常ユーザーの場合
}else{

  $histories = get_purchase_history($db,$user['user_id']);
  
}


//トークンの生成
$token = get_csrf_token();

//エラーがあった場合でもpurchase_view.phpの画面を表示する
include_once VIEW_PATH . 'purchase_view.php';