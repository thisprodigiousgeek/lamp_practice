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

//postのキーcart_idの値を$cart_idに代入
$cart_id = get_post('cart_id');

//$cart_idの値と一致する商品の削除の可不可
if(delete_cart($db, $cart_id)){

  //削除できた場合
  set_message('カートを削除しました。');

  //削除できなかった場合
} else {
  set_error('カートの削除に失敗しました。');
}

//カートページへリダイレクト
redirect_to(CART_URL);