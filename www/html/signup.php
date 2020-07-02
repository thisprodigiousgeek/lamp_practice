<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';

session_start();

// ログイン済みか確認し、trueなら    へリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}

include_once VIEW_PATH . 'signup_view.php';



