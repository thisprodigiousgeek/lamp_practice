<?php
//各モデルから関数データを取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'history.php';
require_once MODEL_PATH . 'details.php';
header('X-Frame-Options: DENY');
session_start();

//トークンチェック
$token=get_post("token");
is_valid_csrf_token($token);
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
//データベースに接続
$db = get_db_connect();
//ユーザー情報を取得(ユーザーIDなど)
$user = get_login_user($db);
//カートに入っている商品から、POSTで飛んできた商品IDに基づいて情報を取得
$carts = get_user_carts($db, $user['user_id']);
//カート商品を購入。購入された商品はカートからなくなる。ここで在庫が1つ減る
if(purchase_carts($db, $carts) === false){
  set_error('商品が購入できませんでした。');
  redirect_to(CART_URL);
} 
//ユーザーIDに基づいた合計金額を表示。ユーザーIDは上の関数で取得
$total_price = sum_carts($carts);

//ユーザーIDのみを取得
$user_id = get_session('user_id');

// //ヒストリーテーブルに追加
order_history($db,$user_id);


$order_id = get_session['order_id'];
//$cartの中の変数を再定義
foreach($carts as $cart){
$item_id = $cart['item_id'];
$item_price = $cart['price'];
$item_amount = $cart['amount'];
}
// //ディティールテーブルに追加
order_details($db,$order_id,$item_id,$item_price,$item_amount);

include_once '../view/finish_view.php';