<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

// ログインしていなかったら、login画面へリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// DBに接続する
$db = get_db_connect();

// $user にセッションから取得したlogin済みのユーザーIDを格納　user.php
$user = get_login_user($db);

// user がadminでなければlogiｎページへリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

// $itemsにselect文で取得したデータを格納
$items = get_all_items($db);
include_once VIEW_PATH . '/admin_view.php';
