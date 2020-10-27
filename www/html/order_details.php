<?php
//必要ファイルの読み込み
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'order.php';
//sessionstart
session_start();
//ログインされていなければログインページへリダイレクト
if(is_logined() === false){
    redirect_to(LOGIN_URL);
}
//DB接続
$db = get_db_connect();
//ログイン情報の取得
$user = get_login_user($db);
//getとして購入履歴のidを取得
$order_history_id = get_get('order_history_id');
//dd($order_history_id);

//order_detailsテーブルの情報を取得
//管理者(admin)との識別
if(is_admin($user) === false){
    $order = get_user_order_details($db, $order_history_id);
}else{
    $order = get_alluser_order_details($db);
}

//管理者ではない場合画面上部に表示させるカート情報を取得
if(is_admin($user) === false){
    $order_history = get_id_order_history($db, $order_history_id);
}

//購入金額の合計
//$total_price = sum_carts($order);

//viewファイルの読み込み
include_once VIEW_PATH . 'order_details_view.php';