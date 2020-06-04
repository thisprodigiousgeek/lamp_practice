<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
header('X-Frame-Options: DENY');
session_start();

$token = get_post("token");
is_valid_csrf_token($token);
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
//データベースに接続
$db = get_db_connect();
//ユーザー情報の取得
$user = get_login_user($db);
//ユーザーの認証。間違っていたらログイン画面へ
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
//admin_viewからの変更を受け取る
$item_id = get_post('item_id');
$stock = get_post('stock');
//updateでデータベースの情報を書き換えている。item.phpで定義
if(update_item_stock($db, $item_id, $stock)){
  set_message('在庫数を変更しました。');
} else {
  set_error('在庫数の変更に失敗しました。');
}

redirect_to(ADMIN_URL);