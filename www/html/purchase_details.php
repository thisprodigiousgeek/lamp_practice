<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'purchase.php';

session_start();

if(is_valid_csrf_token($_POST['token']) === false){
  redirect_to(LOGIN_URL);
}
  
$db = get_db_connect();
$user = get_login_user($db);
$order_id = get_post('order_id');

$details = get_details($db, $order_id);
$purchase_date = get_purchase_date($db, $order_id);
$total_price = sum_purchase($details);

include_once VIEW_PATH . 'details_view.php';