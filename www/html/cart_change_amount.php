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

//postデータの取得
$cart_id = get_post('cart_id');
$amount = get_post('amount');

//カートの購入数変更成功した場合
if(update_cart_amount($db, $cart_id, $amount)){
  //セッション変数にメッセージ表示
  set_message('購入数を更新しました。');
//購入数変更失敗した場合
} else {
  //セッション変数にエラー表示
  set_error('購入数の更新に失敗しました。');
}

//カートページへ遷移
redirect_to(CART_URL);