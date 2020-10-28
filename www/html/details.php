<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'cart.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
$token = get_csrf_token();

$details_id = get_get('details_id');

$db = get_db_connect();
$user = get_login_user($db);

$details = get_details_list($db, $details_id);

$purchased_history = get_history_list($db, $details_id);

include_once VIEW_PATH . 'details_view.php';