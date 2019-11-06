<?php
require_once '../conf/const.php';
require_once '../model/functions.php';
require_once '../model/user.php';
require_once '../model/item.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);
//model/item.phpに関数$items(配列)にopenの商品を格納する
$items = get_open_items($db);

include_once '../view/index_view.php';