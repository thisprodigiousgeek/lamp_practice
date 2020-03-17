<?php
require_once '../conf/const.php';
require_once '../model/functions.php';
require_once '../model/user.php';
require_once '../model/item.php';

$err = array();
$err[] = 'a';
$token = 'b';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}


$db = get_db_connect();
$user = get_login_user($db);

$items = get_open_items($db);

$token = get_csrf_token();

include_once VIEW_PATH . 'index_view.php';