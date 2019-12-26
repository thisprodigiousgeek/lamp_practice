<?php
require_once '../conf/const.php'; //定義ファイル読み込み
require_once MODEL_PATH . 'functions.php'; 
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
//セッション開始をコール
session_start();
//$_SESSION['user_id']が格納されていなければリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
//データベース接続
$db = get_db_connect();
//ログインしているユーザーのユーザー情報を$userに格納
$user = get_login_user($db);
//adminユーザーか確認
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
//フォームから送られてきた情報を格納（＄_POST[])
$item_id = get_post('item_id');
$stock = get_post('stock');
//在庫数の変更
if(update_item_stock($db, $item_id, $stock)){
  set_message('在庫数を変更しました。');
} else {
  set_error('在庫数の変更に失敗しました。');
}
//リダイレクト
redirect_to(ADMIN_URL);