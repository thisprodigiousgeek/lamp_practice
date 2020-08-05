<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'order.php';
require_once MODEL_PATH . 'user.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();

$user = get_login_user($db);
$order_id = get_get('order_id');

var_dump($order_id);
$orders = get_user_order_totals($db, $order_id);
$order_details = get_user_order_details($db, $order_id);

include_once VIEW_PATH . '/order_detail_view.php';