<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

//セッション開始
session_start();

//postで送られたトークンの取得
$token = get_post('token');

//セッションに保存されている'csrf_token'とpostから受け取った$tokenの値が一致しているか確認
if(is_valid_csrf_token($token) === false){

  //一致していなければログインページへリダイレクトし、ログインを要求する
  redirect_to(LOGIN_URL);

}

//一致していればtokenを削除する
unset($SESSION['csrf_token']);

//ログイン中ではなければログインページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//データベース接続
$db = get_db_connect();

//ログイン中のユーザーのユーザーidを取得
$user = get_login_user($db);

//上記のユーザーidと一致するカートデータをcartsから取得
$carts = get_user_carts($db, $user['user_id']);

//商品購入の可不可
if(purchase_carts($db, $carts) === false){

  //商品が購入できなかった場合
  set_error('商品が購入できませんでした。');

  //カートページへリダイレクト
  redirect_to(CART_URL);
} 

//cartsから取得した商品の合計金額を$total_priceに代入
$total_price = sum_carts($carts);

//トークンの生成
$token = get_csrf_token();

//エラーが起きでもfinish_view.phpは表示する
include_once '../view/finish_view.php';