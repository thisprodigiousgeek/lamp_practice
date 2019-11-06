<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
//セッション情報を確認
session_start();
//セッション情報がセットされているかのチェック　されてなければログインページへ
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();

$user = get_login_user($db);
//user.php userがadminIDかチェックでなければログインページへ
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
//function.php  item_idを格納
$item_id = get_post('item_id');
//function.php openかcloseを格納
$changes_to = get_post('changes_to');

//item.php open,closeのsql文を送る失敗したらエラーを返す
if($changes_to === 'open'){
  update_item_status($db, $item_id, ITEM_STATUS_OPEN);
  set_message('ステータスを変更しました。');
}else if($changes_to === 'close'){
  update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
  set_message('ステータスを変更しました。');
}else {
  set_error('不正なリクエストです。');
}

//function.php 管理者ページへ移動
redirect_to(ADMIN_URL);