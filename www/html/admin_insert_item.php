<?php
//商品登録

// 定数ファイルを読み込み
require_once '../conf/const.php';
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';
// itemデータに関する関数ファイルを読み込み。
require_once MODEL_PATH . 'item.php';

session_start();

// ログインしていない場合はログインページにリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//DB接続
$db = get_db_connect();
//ログインユーザー情報取得
$user = get_login_user($db);

//管理者でない場合、ログインページへリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

$name = get_post('name');//商品名
$price = get_post('price');//商品金額
$status = get_post('status');//商品ステータス
$stock = get_post('stock');//商品在庫数

$image = get_file('image');//商品画像

//商品を登録したとき、メッセージを表示する
if(regist_item($db, $name, $price, $stock, $status, $image)){
  set_message('商品を登録しました。');
}else {
  set_error('商品の登録に失敗しました。');
}

//adminページにリダイレクト
redirect_to(ADMIN_URL);