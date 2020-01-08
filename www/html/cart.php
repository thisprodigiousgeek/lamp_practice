<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
//セッション開始をコール
session_start();
//$_SESSION['user_id']が存在しなければログイン画面へリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
//データベース接続、ログインしているユーザーの情報を$userに格納
$db = get_db_connect();
$user = get_login_user($db);
//ログインしているユーザーのカートの中に入っている商品全ての情報を$cartsに格納
$carts = get_user_carts($db, $user['user_id']);
//カートの中に入っている商品の価格を合計
$total_price = sum_carts($carts);

include_once '../view/cart_view.php';