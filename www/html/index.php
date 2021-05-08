<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();
//ログインしていなかったらログインページにリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();//PDOを利用してDBに接続
$user = get_login_user($db);//DBからログインユーザ情報を取得

$items = get_open_items($db);//DBからステータスが”公開”の商品を取得
$token = get_csrf_token();
//商品一覧ページの読み込み
include_once VIEW_PATH . 'index_view.php';