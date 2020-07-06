<?php
// それぞれのページから情報を取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';

// セッションを開始
session_start();

// ログインされていればホームページに移動
if(is_logined() === true){
  redirect_to(HOME_URL);
}

// VIEWの読み込み
include_once VIEW_PATH . 'login_view.php';