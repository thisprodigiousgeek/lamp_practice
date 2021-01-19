<?php
/*
* 商品の在庫数変更ファイル
*/

require_once '../conf/const.php'; //定数関数ファイルの読み込み
require_once MODEL_PATH . 'functions.php'; //共通関数ファイルの読み込み
require_once MODEL_PATH . 'user.php'; //ユーザーデータ用関数ファイルの読み込み
require_once MODEL_PATH . 'item.php'; //商品用関数ファイルの読みこみ

//セッション開始、再開
session_start();
//ログイン可否判断
if(is_logined() === false){
  //ログインしていなかった場合、login.php
  redirect_to(LOGIN_URL);
}

//データベースへ接続（sql実行準備）
$db = get_db_connect();

//ログインユーザーの情報を取得して、変数へ代入
$user = get_login_user($db);

//管理者可否判断
if(is_admin($user) === false){
  //管理者でなかった場合、login.php
  redirect_to(LOGIN_URL);
}
//POST値取得して、変数へ代入
$item_id = get_post('item_id');
$stock = get_post('stock');
//在庫数を変更しての真偽値
if(update_item_stock($db, $item_id, $stock)){
  //メッセージを設定
  set_message('在庫数を変更しました。');
} else {
  //メッセージを設定
  set_error('在庫数の変更に失敗しました。');
}
//管理者ページへ遷移
redirect_to(ADMIN_URL);