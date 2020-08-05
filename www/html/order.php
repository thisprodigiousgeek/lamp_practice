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

if(is_admin($user) === true){
  $orders = get_orders($db);
}else if(is_admin($user) === false){
  $normal_user = $user['user_id'];
  $orders = get_user_orders($db, $normal_user);
}

include_once VIEW_PATH . '/order_view.php';