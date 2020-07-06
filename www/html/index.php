<?php
// それぞれのページから関数を取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

// セッションを開始
session_start();

// ログインされていなければログインページに戻る
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// データベースに接続
$db = get_db_connect();

// ユーザー情報の取得
$user = get_login_user($db);

// 公開されている商品情報の取得
$items = get_open_items($db);

// VIEWに出力
include_once VIEW_PATH . 'index_view.php';