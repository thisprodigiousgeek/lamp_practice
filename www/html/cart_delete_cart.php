<?php

// 定数ファイルを読み込み
require_once '../conf/const.php';
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';
// itemデータに関する関数ファイルを読み込み。
require_once MODEL_PATH . 'item.php';
//カートデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'cart.php';

session_start();

//ログインしていない場合、ログインページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

$cart_id = get_post('cart_id');

//カートを削除したとき、メッセージを表示する
if(delete_cart($db, $cart_id)){
  set_message('カートを削除しました。');
} else {
  set_error('カートの削除に失敗しました。');
}
//カートページへリダイレクト
redirect_to(CART_URL);