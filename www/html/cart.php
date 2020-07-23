<?php
// 設定ファイル読込
require_once '../conf/const.php';
// 関数ファイル読込
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
// セッション開始
session_start();
// ログインしていなければログイン画面へリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
// DB接続
$db = get_db_connect();
// ユーザ情報取得
$user = get_login_user($db);
// ユーザのカート情報取得
$carts = get_user_carts($db, $user['user_id']);
// カート内の合計金額を取得する
$total_price = sum_carts($carts);
// HTMLエンティティ化
$user = entity_array($user);
$carts = entity_arrays($carts);
// カートページのviewファイル出力
include_once VIEW_PATH . 'cart_view.php';