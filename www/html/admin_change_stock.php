<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();
//sessionにuser_idがあるかのチェック
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
//dbからuser_idチェック
$user = get_login_user($db);
//user_idがadminかのチェックでなければログインページへ
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

//function.php 各変数に入力された情報を格納
$item_id = get_post('item_id');
$stock = get_post('stock');

//item.php 在庫数を変更するsql文を作成
if(update_item_stock($db, $item_id, $stock)){
  set_message('在庫数を変更しました。');
} else {
  set_error('在庫数の変更に失敗しました。');
}
//functions.php 管理者ページへ
redirect_to(ADMIN_URL);