<?php
//定義ファイル読み込み
require_once '../conf/const.php';
//関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';

//セッションスタート
session_start();

//ログインされたらホームページ
if(is_logined() === true){
  redirect_to(HOME_URL);
}

//ビューの読み込み
include_once VIEW_PATH . 'signup_view.php';



