<?php
// 定数ファイルの読み込み
require_once '../conf/const.php';
// 汎用関数ファイルの読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';
// itemデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'item.php';

// ログインチェックを行うため、sessionを開始する
session_start();
// ログインチェック関数を呼び出し
if(is_logined() === false){
  // ログインしていない場合は、ログインページにリダイレクト
  redirect_to(LOGIN_URL);
}
// POSTリクエストのデータを取得
$sort = get_get('sort');

// PDOを取得
$db = get_db_connect();
// PDOを利用してログインユーザーのデータを取得
$user = get_login_user($db);
// 商品一覧用の商品データを取得
$items = get_open_items($db,$sort);
// ビューの読み込み
include_once VIEW_PATH . 'index_view.php';