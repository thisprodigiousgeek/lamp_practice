<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();

$user = get_login_user($db);

if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

$item_id = get_post('item_id');

//stockが数字かどうか、整数かどうかの確認とエスケープ
if(is_numeric(get_post('stock')) === TRUE && is_float(get_post('stock')) === FALSE && get_post('stock') > 0){
  $stock = htmlspecialchars(get_post('stock'),ENT_QUOTES,'UTF-8');
}else{
  set_message('在庫数の変更に失敗しました');
}

if(update_item_stock($db, $item_id, $stock)){
  set_message('在庫数を変更しました。');
} else {
  set_error('在庫数の変更に失敗しました。');
}

redirect_to(ADMIN_URL);