<?php
require_once '../conf/const.php';
require_once '../model/functions.php';
require_once '../model/user.php';
require_once '../model/item.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//トークン生成
$token = get_csrf_token();

$db = get_db_connect();
$user = get_login_user($db);

//エスケープ処理の追加
$data = get_open_items($db);
$items = change_htmlsp_array($data);
include_once VIEW_PATH . 'index_view.php';