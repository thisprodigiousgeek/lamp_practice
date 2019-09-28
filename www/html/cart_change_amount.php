<?php

// [[ カート内商品の数量変更のPOSTが送信された時 ]]


// 定数ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// 各モデル(関数)ファイル読み込み
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

// ログインされてなければログインページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// DB関連の設定
$db = get_db_connect();
// user_idがセッションにセットされていれば、ユーザー情報を取得して変数に代入
$user = get_login_user($db);

// POSTでcart_idとamountが送られてきたことを確認して、変数に代入
$cart_id = get_post('cart_id');
$amount = get_post('amount');

// 購入数量変更
if(update_cart_amount($db, $cart_id, $amount)){
  set_message('購入数を更新しました。');
} else {
  set_error('購入数の更新に失敗しました。');
}

// カート画面へリダレクト
redirect_to(CART_URL);