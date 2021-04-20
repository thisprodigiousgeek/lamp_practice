<?php
// 定数ファイルの読み込み
require_once '../conf/const.php';
// 汎用関数ファイルの読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'user.php';
// itemデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'item.php';
// cartデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'cart.php';
// ログインチェックのために、セッションを開始
session_start();
// ログインチェック用の関数を呼び出し
if(is_logined() === false){
  // ログインしていない場合、ログイン画面にリダイレクト
  redirect_to(LOGIN_URL);
}

// トークンをpostから受け取る
$token = get_post('csrf_token');
// sessionに格納しているトークンと照合
if (is_valid_csrf_token($token) === false) {
  // トークンの削除
  set_session('csrf_token','');
  // 処理の中断
  exit('不正なアクセスです');

} else {
  // トークンの削除
  set_session('csrf_token','');
  // トークンの生成
  $token = get_csrf_token();
}

// PDOの取得
$db = get_db_connect();
// ログインuser情報取得用の関数を呼び出し
$user = get_login_user($db);
// cartidの取得、postデータ取得用の関数の呼び出し
$cart_id = get_post('cart_id');
// cartidで指定したカート情報の削除
if(delete_cart($db, $cart_id)){
  // sessionにメッセージを追加する関数の呼び出し
  set_message('カートを削除しました。');
} else {
  // sessionにメッセージを追加する関数の呼び出し
  set_error('カートの削除に失敗しました。');
}
// cart画面にリダイレクト
redirect_to(CART_URL);