<?php
// 設定ファイルの読込
require_once '../conf/const.php';
// 関数ファイルの読込
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
// セッション開始
session_start();
// ログインしていなければログイン画面へリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
// DB接続
$db = get_db_connect();
// 管理ユーザの情報取得
$user = get_login_user($db);
// 管理ユーザの情報取得できていない場合はログイン画面へリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
// 全商品情報を取得
$items = get_all_items($db);
// HTMLエンティティ化
$user = entity_array($user);
$items = entity_arrays($items);
// admin.phpのviewファイル出力
include_once VIEW_PATH . '/admin_view.php';
