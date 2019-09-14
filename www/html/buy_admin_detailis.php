<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';

// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';

// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';

// itemデータに関する関数ファイルを読み込み。
require_once MODEL_PATH . 'item.php';

// cartデータに関する関数ファイルを読み込み。
require_once MODEL_PATH . 'cart.php';

//セッションをスタートする
session_start();

// ログインチェック用関数を利用
if(is_logined() === false){

 // ログインしていない場合はログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

// PDOを取得
$db = get_db_connect();
$buy_id=get_post('buy_id');
// PDOを利用してログインユーザーのデータを取得
$user = get_login_user($db);
//get_user_cartsにデータを表示
$buys=buy_ADMIN_detailis($db,$buy_id);
$bu=buy_ADMIN($db,$buy_id);

// ビューの読み込み。
include_once '../view/buy_details_view.php';



