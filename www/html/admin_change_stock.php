<?php
//定義ファイルの読み込み
require_once '../conf/const.php';
//関数ファイルの読み込み
require_once MODEL_PATH . 'functions.php';
//ユーザーファイルの読み込み
require_once MODEL_PATH . 'user.php';
//商品ファイルの読み込み
require_once MODEL_PATH . 'item.php';

//セッションスタート
session_start();

//ログインされなかったらログインURLへ
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//DB接続
$db = get_db_connect();

//ユーザーログインページへ接続
$user = get_login_user($db);

//ユーザーが管理者ではなかったらログインページへ
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

//ポストの取得
$item_id = get_post('item_id');
$stock = get_post('stock');

//在庫情報の更新
if(update_item_stock($db, $item_id, $stock)){
  set_message('在庫数を変更しました。');
  //更新されなければ
} else {
  set_error('在庫数の変更に失敗しました。');
}

//管理者ページへ
redirect_to(ADMIN_URL);