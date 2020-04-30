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

$items = get_all_items($db);
// 配列データをエンティティに変換(2次元配列)
// entity_array()をするとstatusの方がstring型になってしまうのが原因。どこまでキャストするか確認するため、一旦h()を直接入れた
//$items = entity_array($items);
// dd($items);
include_once VIEW_PATH . '/admin_view.php';
