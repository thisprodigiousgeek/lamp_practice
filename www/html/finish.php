<?php
//定義ファイル読み込み
require_once '../conf/const.php';
//汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
//userデータに関するファイル読み込み
require_once MODEL_PATH . 'user.php';
//itemデータに関するファイル読み込み
require_once MODEL_PATH . 'item.php';
//cartデータに関するファイル読み込み
require_once MODEL_PATH . 'cart.php';

//ログインチェックするため、セッション開始
session_start();

//ログインチェック用関数を利用
if(is_logined() === false){
  //ログインしていない場合はログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

//データベース接続
$db = get_db_connect();
//ログインユーザーのデータを取得
$user = get_login_user($db);

//カートに入っているアイテム表示
$carts = get_user_carts($db, $user['user_id']);

//postで送られてきたデータ
$token = get_post('token');

//トークンチェック
if(is_valid_csrf_token($token) === false){
  //ログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

//カート購入失敗した場合
if(purchase_carts($db, $carts, $user['user_id']) === false){
  //セッション変数にエラー表示
  set_error('商品が購入できませんでした。');
  //カートページにリダイレクト
  redirect_to(CART_URL);
} 

//カートの合計金額計算
$total_price = sum_carts($carts);

//ビューの読み込み
include_once '../view/finish_view.php';