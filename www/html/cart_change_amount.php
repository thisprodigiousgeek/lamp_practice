<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();
//ログインしていなかったらログインページにリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();//PDOを利用してDB接続
$user = get_login_user($db);//DBからログインしたユーザ情報を取得

$cart_id = get_post('cart_id');//ポストされたカートid取得
$amount = get_post('amount');//ポストされた数量を取得
//カートの数量を更新する関数を利用
if(update_cart_amount($db, $cart_id, $amount)){
  set_message('購入数を更新しました。');
  //更新失敗したらエラーメッセージ
} else {
  set_error('購入数の更新に失敗しました。');
}
// カートページにリダイレクト
redirect_to(CART_URL);