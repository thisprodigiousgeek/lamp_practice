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
$stock = get_post('stock');

if(is_valid_csrf_token($token)) {  //6.21 CSRF
  if(update_item_stock($db, $item_id, $stock)){
    set_message('在庫数を変更しました。');
  } else {
    set_error('在庫数の変更に失敗しました。');
  }
} else {
  set_error('不正な処理です');
}

redirect_to(ADMIN_URL);