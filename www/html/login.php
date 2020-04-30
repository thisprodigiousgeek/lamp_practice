<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';

session_start();

// ログイン状態ならリダイレクト
if(is_logined() === true){
  //index.phpへリダイレクト
  redirect_to(HOME_URL);
}

include_once VIEW_PATH . 'login_view.php';