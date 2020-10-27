<?php
//必要ファイルの読み込み
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'order.php';
//セッションスタート
session_start();
//ログインされていなければログインページへリダイレクト
if(is_logined() === false){
    redirect_to(LOGIN_URL);
}
//DB接続
$db = get_db_connect();
//ログイン情報の取得
$user = get_login_user($db);
//order_historyテーブルの情報を取得
if(is_admin($user) === false){
    $order = get_user_order_history($db, $user['user_id']);
}else{
    $order = get_alluser_order_history($db);
}
//viewファイルの読み込み
include_once VIEW_PATH . 'order_history_view.php';