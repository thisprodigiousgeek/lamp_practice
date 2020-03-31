<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'history.php';
require_once MODEL_PATH . 'detail.php';

session_start();

if (is_logined() === false) {
    redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $buy_id = $_POST['buy_id'];
    $total_price = $_POST['total'];
} else {
    print "不正なアクセスです\n5秒後にMarketに戻ります";
    sleep(5);
    redirect_to(HOME_URL);
}

//購入履歴・購入明細テーブルの更新
// $purchase_data = get_purchase_data($db, $user['name'], $user['user_id']);
$purchase_detail = get_detail_data($db, $user['name'], $user['user_id'], $buy_id);

$subtotal = calculation_subtotal($purchase_detail);


include_once '../view/detail_view.php';
