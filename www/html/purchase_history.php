<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'purchase.php';

session_start();

$token = get_csrf_token();

if(is_logined() === false){
    redirect_to(LOGIN_URL);
  }
  
$db = get_db_connect();
$user = get_login_user($db);

$orders = get_history($db, $user['user_id']);
$order_details = get_history_details($db, $user['user_id']);

$admin_history = admin_history($db);
$admin_history_sum = admin_history_sum($db);

if ($user['type'] === USER_TYPE_ADMIN) {
  include_once VIEW_PATH . 'admin_history_view.php';
} else {
  include_once VIEW_PATH . 'history_view.php';
}