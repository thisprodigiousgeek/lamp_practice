<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

$data = get_user_carts($db, $user['user_id']);

//トークン生成
$token = get_csrf_token();
//エスケープ処理を追加
$carts = change_htmlsp_array($data);

$total_price = sum_carts($carts);

include_once VIEW_PATH . 'cart_view.php';