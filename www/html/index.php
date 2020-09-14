<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

$token = get_csrf_token();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

$page = get_page($_GET['page'], array());

$start_item = ($page - 1) * ITEM_LIMIT;

$items = get_open_items($db, $start_item);
$item = get_open_item($db);

$item_count = count($item);
$total_page = ceil($item_count / ITEM_LIMIT);

$now = get_page($_GET['page'], $total_page);

include_once VIEW_PATH . 'index_view.php';