<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

//セッション開始
session_start();

//ログイン中ではなければログインページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//データベース接続
$db = get_db_connect();

//ログイン中のユーザーのユーザーidを取得
$user = get_login_user($db);

//postのキーcart_idにセットされている値を$cart_idに代入
$cart_id = get_post('cart_id');

//postのキーamountにセットされている値を$amountに代入
$amount = get_post('amount');

//カート追加の可不可
if(update_cart_amount($db, $cart_id, $amount)){

  //カートに追加できた場合
  set_message('購入数を更新しました。');

  //カートに追加できなかった場合
} else {
  set_error('購入数の更新に失敗しました。');
}

//カートページへリダイレクト
redirect_to(CART_URL);