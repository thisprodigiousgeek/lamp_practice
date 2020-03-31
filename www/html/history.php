<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'history.php';

session_start();

if (is_logined() === false) {
    redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

//購入履歴データの取得
$purchase_data = get_purchase_data($db, $user['name'], $user['user_id']);
//合計金額の取得
// $total_price = calculation_total_price($db, $purchase_data, $buy_id);

include_once '../view/history_view.php';
