<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'user.php';

session_start();

if(is_logined() === false){
  redirect_to(HOME_URL);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  set_error('不正なリクエストです。');
  redirect_to(HISTORY_URL);
}

$db = get_db_connect();

$user = get_login_user($db);

$history_id = get_post('history_id');
$purchase_date = get_post('purchase_date');
$total_price = get_post('total_price');
$token = get_post('token');

$user = get_login_user($db);
if(is_token($token) === true){
  if (is_admin($user) === true){
    $history_detail_data = get_all_history_detail($db, $history_id);
  } else {
    $history_detail_data = get_history_detail($db, $user['user_id'], $history_id);
  }
} else {
  set_error('不正なリクエストです。');
  redirect_to(HISTORY_URL);
}
include_once VIEW_PATH . 'history_detail_view.php';
?>