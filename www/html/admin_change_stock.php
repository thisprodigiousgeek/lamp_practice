<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();
//ログインしてない場合ログインページに飛ぶ
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();

$user = get_login_user($db);
//ログインアカウントが管理者アカウントではなかったらログインページにリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

$item_id = get_post('item_id');
$stock = get_post('stock');
//在庫数変更の関数を利用
if(update_item_stock($db, $item_id, $stock)){
  set_message('在庫数を変更しました。');
//在庫変更できなかったらエラーメッセージを出す
} else {
  set_error('在庫数の変更に失敗しました。');
}
//管理ページにリダイレクト
redirect_to(ADMIN_URL);