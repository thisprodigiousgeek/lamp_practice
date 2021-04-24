<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'order.php';


//セッションスタート
session_start();

//ログインされていない状態ならばログイン画面にリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//DB接続
$db = get_db_connect();
//ユーザー情報を取得
$user = get_login_user($db);
//ログイン中のuser_idを取得
$user_id = get_session('user_id');
//postで送られてきたものを取得
$order_id = get_post('order_id');
$order_date = get_post('order_date');
$total = get_post('total');
//詳細情報を取得
$order_details = get_order_detail($db,$order_id);

include_once '../view/order_detail_view.php';