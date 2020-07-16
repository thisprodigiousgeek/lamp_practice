<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'user.php';

session_start();

if (is_logined() === false){
  redirect_to(LOGIN_URL);
}
$db = get_db_connect();
$user = get_login_user($db);

$token = get_token();

if(is_admin($user) === true){
  $history_data = get_all_history($db);
} else {
  $history_data = get_history($db, $user['user_id']);
}
include_once VIEW_PATH . 'history_view.php';

