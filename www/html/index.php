<?php
// 設定ファイル読込
require_once '../conf/const.php';
// 関数ファイル読込
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
// セッション開始
session_start();
// ログインしていない場合ログイン画面にリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
// DB接続
$db = get_db_connect();
// ログインユーザ情報取得
$user = get_login_user($db);
// 購入可能な商品の情報を取得
$items = get_open_items($db);
// HTMLエンティティ化
$user = entity_array($user);
$items = entity_arrays($items);
// ホームページのviewファイル出力
include_once VIEW_PATH . 'index_view.php';