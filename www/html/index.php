<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

//ログインしていなければ、ログイン画面へ
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//DB接続を行う関数を変数に代入
$db = get_db_connect();
//ログインユーザーのIDを取得する関数を変数に代入
$user = get_login_user($db);

//商品情報を取得する関数を変数に代入
$items = get_open_items($db);

include_once VIEW_PATH . 'index_view.php';