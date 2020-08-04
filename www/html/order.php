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

var_dump($user);

if(is_admin($user) === true){
  $orders = get_order_items($db);
}else if(is_admin($user) === false){

}

include_once VIEW_PATH . '/order_view.php';