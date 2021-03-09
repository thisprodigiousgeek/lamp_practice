<?php
//定義ファイルの読み込み
require_once '../conf/const.php';
//関数ファイルの読み込み
require_once MODEL_PATH . 'functions.php';
//ユーザーファイルの読み込み
require_once MODEL_PATH . 'user.php';
//商品情報の読み込み
require_once MODEL_PATH . 'item.php';
//カート情報の取得
require_once MODEL_PATH . 'cart.php';

//セッションスタート
session_start();

//ログインされなかったらログインページへ
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//DB接続
$db = get_db_connect();
//ログインされたユーザー情報の取得
$user = get_login_user($db);

//カートIDの取得
$cart_id = get_post('cart_id');
//カート追加情報を取得
$amount = get_post('amount');

//カートにある購入数の更新
if(update_cart_amount($db, $cart_id, $amount)){
  set_message('購入数を更新しました。');
  //更新失敗
} else {
  set_error('購入数の更新に失敗しました。');
}

//カートページへ
redirect_to(CART_URL);