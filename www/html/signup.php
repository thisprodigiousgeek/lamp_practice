<?php
require_once '../conf/const.php';//定義ファイルを読み込み
require_once MODEL_PATH . 'functions.php'; //関数ファイルを読み込み
//セッション開始
session_start();
//$_SESSION['user_id']が存在すれば、ホーム画面へリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}

include_once '../view/signup_view.php';//サインアップのビュー画面読み込み



