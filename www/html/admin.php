<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

//セッション開始
session_start();

//ログイン中では無い場合ログインページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//データベースに接続
$db = get_db_connect();

//ログイン中のユーザーのユーザーidを取得
$user = get_login_user($db);

//ログインページへリダイレクト 条件式?
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

//?
$items = get_all_items($db);
include_once VIEW_PATH . '/admin_view.php';
