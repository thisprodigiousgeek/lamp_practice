<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

// ログイン済みか確認し、falseならloginページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// DB接続
$db = get_db_connect();
// login済みのuser_idをセッションから取得して変数に格納
$user = get_login_user($db);

// 公開中の商品のみを取得する
$items = get_open_items($db);

include_once VIEW_PATH . 'index_view.php';