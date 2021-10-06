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

//データベースへ接続
$db = get_db_connect();

//ログイン中のユーザーのユーザーidを取得
$user = get_login_user($db);

//上記のユーザーidと一致するカートデータをcartsから取得
$carts = get_user_carts($db, $user['user_id']);

//カートの中に入っている商品の合計金額を$total_priceに代入
$total_price = sum_carts($carts);

//エラーがあった場合でもcart_view.phpの画面を表示する
include_once VIEW_PATH . 'cart_view.php';