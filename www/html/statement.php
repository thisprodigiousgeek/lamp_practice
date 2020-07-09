<?php 
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'statement.php';



session_start();

//ログインしていなければ (=ユーザーIDがセッションに保存されていなければ)、ログイン画面へ
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//DBへの接続を行う関数を$dbへ代入
$db = get_db_connect();
//ログインしているユーザーの情報を取得する関数を$userに代入
$user = get_login_user($db);

//送られてきたのorder_idの値(注文番号)を取得する関数を変数に代入
$order_id = get_post('order_id');
//送られてきたのorder_datetimeの値(購入日時)を取得する関数を変数に代入
$order_datetime = get_post('order_datetime');
//送られてきたのtotalの値(合計金額)を取得する関数を変数に代入
$total = get_post('total');

//オーダーIDに応じた購入明細情報の取得(order_idごと)
$statements = get_statements($db,$order_id);

//購入した商品の合計金額
//$statements_total_price = sum_orders($orders);

//購入明細情報の取得(order_idごと)
//$statements = get_statements($db,$user['order_id']);
//var_dump($statement);
//購入した商品の合計金額
//$order_total_price = sum_orders($orders);

include_once VIEW_PATH . '/statement_view.php';
?>