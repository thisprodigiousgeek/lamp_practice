<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
// ログインチェックのために、セッションを開始
session_start();
// ログインチェック用関数の呼び出し
if(is_logined() === false){
  // ログインしていない場合は、ログイン画面にリダイレクト
  redirect_to(LOGIN_URL);
}
// PDOの取得
$db = get_db_connect();
// ログインuser情報の取得用関数の呼び出し
$user = get_login_user($db);
// ユーザータイプが管理者であるか判定
if(is_admin($user) === false){
  // 異なる場合は、ログイン画面にリダイレクト
  redirect_to(LOGIN_URL);
}
//商品情報の取得用関数の呼び出し
$items = get_all_items($db);
include_once VIEW_PATH . '/admin_view.php';
