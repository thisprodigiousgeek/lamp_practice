<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();
if(is_valid_csrf_token($_POST['csrf_token']) === false) {
  redirect_to(LOGIN_URL);
}
// ログインしていない場合ログインページにリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();//PDO利用でデータベース接続
$user = get_login_user($db);//DBからログインユーザの情報を取得

$cart_id = get_post('cart_id');//ポストされたカートidを取得
//カート情報を削除する関数を利用
if(delete_cart($db, $cart_id)){
  set_message('カートを削除しました。');
// 失敗したらエラーメッセージ
} else {
  set_error('カートの削除に失敗しました。');
}
// カートページにリダイレクト
redirect_to(CART_URL);