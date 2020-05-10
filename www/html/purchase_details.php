<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'purchase.php';

session_start();

$db = get_db_connect();
$user = get_login_user($db);
$user_id = $_SESSION['user_id'];
$order_id = $_POST['order_id'];

$data = get_purchase_details($db, $order_id);

$items = check_user_id($user_id, $data[0]['user_id'], $data);
$sum_purchase = sum_purchase($items);

$items = change_timestamp($items);
include_once VIEW_PATH . 'purchase_details_view.php';