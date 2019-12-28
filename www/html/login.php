<?php
require_once '../conf/const.php';//定数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';//関数ファイルを読み込み
//セッション開始
session_start();
//$_SESSION['user_id']が存在しなければログイン画面へリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}

include_once '../view/login_view.php'; //viewファイルの読み込み