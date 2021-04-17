<?php
// 定数ファイルの読み込み
require_once '../conf/const.php';
// 汎用関数ファイルの読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';
// itemデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'item.php';
// cartデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'cart.php';
// ログインチェックのために、セッションを開始
session_start();
// ログインチェック用の関数を呼び出し
if(is_logined() === false){
  // ログインしていない場合、ログイン画面にリダイレクト
  redirect_to(LOGIN_URL);
}
// user,item情報取得のために、PDOを取得
$db = get_db_connect();
// ログインuser情報取得用の関数を呼び出し
$user = get_login_user($db);
// cartに追加する商品idの取得、postデータ取得用の関数の呼び出し
$item_id = get_post('item_id');
// cartsテーブルに商品を追加用の関数を呼び出し
if(add_cart($db,$user['user_id'], $item_id)){
  // sessionにメッセージを追加する関数の呼び出し
  set_message('カートに商品を追加しました。');
} else {
  // sessionにメッセージを追加する関数の呼び出し
  set_error('カートの更新に失敗しました。');
}
// index画面にリダイレクト
redirect_to(HOME_URL);