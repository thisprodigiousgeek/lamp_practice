<?php

require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'purchase.php';

//セッション開始
session_start();

//postで送信されたトークンの取得
$token = get_post('token');

//セッションに保存されている'csrf_token'とpostから受け取った$tokenの値が一致しているか確認
if(is_valid_csrf_token($token) === false){

    //一致していなければログインページへリダイレクト
    redirect_to(LOGIN_URL);

}

//一致していればトークンを削除する
unset($_SESSION['csrf_token']);

//ログイン中ではなければログインページへリダイレクト
if(is_logined() === false){
    redirect_to(LOGIN_URL);
  }

//データベース接続
$db = get_db_connect();

//ログイン中のユーザーのユーザーidを取得
$user = get_login_user($db);

//postで送信した値の取得
$order_id = get_post('order_id');

$datetime = get_post('datetime');

$total = get_post('total');

//購入明細取得
$details = get_purchase_details($db,$order_id);


include_once VIEW_PATH . 'purchase_detail_view.php';

?>