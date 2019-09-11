<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';

// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';

// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';

// itemデータに関する関数ファイルを読み込み。
require_once MODEL_PATH . 'item.php';

// cartデータに関する関数ファイルを読み込み。
require_once MODEL_PATH . 'cart.php';

//セッションをスタートする
session_start();

// ログインチェック用関数を利用
if(is_logined() === false){

 // ログインしていない場合はログインページにリダイレクト
  redirect_to(LOGIN_URL);

}

// PDOを取得
$db = get_db_connect();

// PDOを利用してログインユーザーのデータを取得
$user = get_login_user($db);

//cart_idデータを関数get_postを利用し取得
$cart_id = get_post('cart_id');

//amountデータを関数get_postを利用し取得
$amount = get_post('amount');

//update_cart_amountを使い購入数の変更を行う
if(update_cart_amount($db, $cart_id, $amount)){

//購入数変更成功時下記のメッセージを表示
  set_message('購入数を更新しました。');

} else {

//購入数変更失敗時下記のメッセージを表示
  set_error('購入数の更新に失敗しました。');

}
is_valid_csrf_token($token);

// ビューの読み込み。
redirect_to(CART_URL);