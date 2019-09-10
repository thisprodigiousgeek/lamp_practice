<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';

session_start();

if(is_logined() === true){
  redirect_to(HOME_URL);
}

//トークンを新規生成
$token = get_csrf_token();

include_once '../view/signup_view.php';



