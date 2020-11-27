<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();

$user = get_login_user($db);

$order_id = get_post('order_id');

if(is_admin($user)){
  $items = get_details($db, $order_id);

} else {
  $items = get_details($db, $order_id, $user['user_id']);
}

include_once VIEW_PATH . '/details_view.php';
