<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';

//外部からページが埋め込まれるのを制限する
header('X-FRAME-OPTIONS: DENY');

session_start();

if(is_logined() === true){
  redirect_to(HOME_URL);
}

//トークンの生成
$token = get_csrf_token();

include_once VIEW_PATH . 'signup_view.php';



