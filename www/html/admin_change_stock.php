<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

//セッション開始
session_start();

//ログイン状態では無い場合ログインページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//データベース接続
$db = get_db_connect();

//ログインしたユーザーのユーザーidの取得
$user = get_login_user($db);

//ログインページへリダイレクト 条件式の部分?
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

//在庫数変更の対象商品のitem_idを$item_idに代入
$item_id = get_post('item_id');

//在庫数変更の対象商品のstockを$stockに代入
$stock = get_post('stock');

//在庫数を変更する
if(update_item_stock($db, $item_id, $stock)){
  set_message('在庫数を変更しました。');

//変更に失敗した場合
} else {
  set_error('在庫数の変更に失敗しました。');
}

//?
redirect_to(ADMIN_URL);