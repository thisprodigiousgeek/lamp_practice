<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'order.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

$token = get_post('token');
$order_id = (int)get_post('order_id');

$order = get_orders($db, $order_id);
$details = get_order_details($db, $order_id);

if(is_valid_csrf_token($token) === false) {
  set_error('不正な操作です。');
}

include_once VIEW_PATH . 'details_view.php';