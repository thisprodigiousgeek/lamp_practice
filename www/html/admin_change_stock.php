<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

// ログイン済みか確認してfalseだったらlogiｎページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// DBに接続する
$db = get_db_connect();

// login済みのuser_idをセッションから取得して変数に格納
$user = get_login_user($db);

// 管理者かどうかチェックして、falseならloginページへリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

// post送信されたitem_idを変数に格納
$item_id = get_post('item_id');
// post送信された在庫数を変数に格納
$stock = get_post('stock');

// 在庫数を更新する
if(update_item_stock($db, $item_id, $stock)){
  set_message('在庫数を変更しました。');
} else {
  set_error('在庫数の変更に失敗しました。');
}

redirect_to(ADMIN_URL);