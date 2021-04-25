<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'ranking.php';

//セッションスタート
session_start();

//セッションにtokenを保存し、ランダムな文字列を$tokenに代入
$token = get_csrf_token();

//ログインされていない状態ならばログイン画面にリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//DB接続
$db = get_db_connect();
//ユーザー情報を取得
$user = get_login_user($db);
//公開中の商品情報を取得
$items = get_open_items($db);
//ランキング情報を取得
$ranking = get_ranking($db);
include_once VIEW_PATH . 'index_view.php';