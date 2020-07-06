<?php
// それぞれのページから情報を取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

// セッションを開始
session_start();

// ログインが実行されなればログインページ戻る
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// データベースに接続
$db = get_db_connect();

// ユーザー情報の取得
$user = get_login_user($db);

// 管理者でなければログインページに移動
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

// 商品情報の取得
$items = get_all_items($db);

// VIEWの読み込み
include_once VIEW_PATH . '/admin_view.php';
