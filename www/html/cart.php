<?php
// [[カートページの表示]]


// 定数ファイル読み込み
require_once '../conf/const.php';

// 各モデルファイル(関数)読み込み
require_once MODEL_PATH . 'functions.php';
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

// ログインユーザーのカートに入っている商品情報を取得(select)
$carts = get_user_carts($db, $user['user_id']);

// カート内商品の合計金額
$total_price = sum_carts($carts);

// ファイルの読み込み
include_once '../view/cart_view.php';