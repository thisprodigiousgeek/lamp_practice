<?php
require_once '../conf/const.php';
require_once '../model/functions.php';
require_once '../model/user.php';
require_once '../model/item.php';
require_once '../model/index.php';


$err = array();
$token = '';
session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET'){
  $sort = get_get('sort');
} else {
  $sort = 'new';
}

$db = get_db_connect();
$user = get_login_user($db);

$items = sort_items($db, $sort);

$token = get_csrf_token();

include_once VIEW_PATH . 'index_view.php';