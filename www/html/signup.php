<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';

session_start();

get_csrf_token();
$token = $_SESSION['csrf_token'];
is_valid_csrf_token($token);

if(is_logined() === true){
  redirect_to(HOME_URL);
}

include_once VIEW_PATH . 'signup_view.php';



