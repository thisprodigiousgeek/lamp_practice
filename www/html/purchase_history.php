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
// 表示項目は「注文番号」「購入日時」「該当の注文の合計金額」とする。


$data = get_purchase_history($db, $user_id);
$items = change_htmlsp_array($data);
$items = change_timestamp($items);

include_once VIEW_PATH . 'purchase_history_view.php';
