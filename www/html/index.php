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

$sort = (int) get_post('sort');

if($sort === 0){
  $items = get_open_items($db);
}else if($sort === 1){
  $items = get_open_sort_asc_items($db);
}else if($sort === 2){
  $items = get_open_sort_desc_items($db);
}else{
  $items = get_open_items($db);
}

$token = get_csrf_token();

include_once VIEW_PATH . 'index_view.php';