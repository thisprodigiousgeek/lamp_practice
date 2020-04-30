<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';
// 汎用関数ファイルを読み込み
require_once '../model/functions.php';
// userデータに関するファイルを読み込み
require_once '../model/user.php';
// itemデータに関するファイルを読み込み
require_once '../model/item.php';

// ログインチェックを行うため、セッションを開始する
session_start();

// $_SESSION['user_id']があるかチェック
if(is_logined() === false){
  // ログインしていない場合はログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

// PDOを取得
$db = get_db_connect();
// PDOを利用してログインユーザーのデータを取得
$user = get_login_user($db);

// 商品一覧用の商品データを取得
$items = get_open_items($db);

// ビューの読み込み
include_once VIEW_PATH . 'index_view.php';