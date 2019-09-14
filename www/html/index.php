<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';

// 汎用関数ファイルを読み込み
require_once '../model/functions.php';

// userデータに関する関数ファイルを読み込み
require_once '../model/user.php';

// itemデータに関する関数ファイルを読み込み。
require_once '../model/item.php';

require_once '../model/cart.php';
//セッションをスタートする
session_start();

// ログインチェック用関数を利用
if(is_logined() === false){

// ログインしていない場合はログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

// PDOを取得
$db = get_db_connect();

// PDOを利用してログインユーザーのデータを取得
$user = get_login_user($db);

//item_idを取得する
$items = get_open_items($db);
$ranking=ranking($db);
// ビューの読み込み。
include_once '../view/index_view.php';