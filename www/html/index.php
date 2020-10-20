<?php
//定数ファイルの読み込み
require_once '../conf/const.php';
//汎用関数ファイルの読み込み
require_once MODEL_PATH . 'functions.php';
//userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';
//itemデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'item.php';

//ログインのチェックのためセッションをスタート
session_start();
//is_loginedから返された値が空とされていたらログインページにリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
//a
$db = get_db_connect();
$user = get_login_user($db);

$items = get_open_items($db);

//トークンの生成関数の使用
$token = get_csrf_token();

include_once VIEW_PATH . 'index_view.php';