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

foreach(get_open_items($db) as $key => $value) {
    get_open_items($db)[$key] = htmlspecialchars($value, ENT_QUOTES,'UTF-8');
}

$items = $value;

include_once VIEW_PATH . 'index_view.php';