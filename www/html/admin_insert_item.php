<?php
// 設定ファイルの読込
require_once '../conf/const.php';
// 関数ファイルの読込
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
// セッション開始
session_start();
// ログインしていなければログイン画面へリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
// DB接続
$db = get_db_connect();
// ユーザ情報取得
$user = get_login_user($db);
// ユーザ情報取得できていない場合はログイン画面へリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
// POST値取得
$name = get_post('name');
$price = get_post('price');
$status = get_post('status');
$stock = get_post('stock');
// FILES値取得
$image = get_file('image');
// 商品テーブルに商品登録
if(regist_item($db, $name, $price, $stock, $status, $image)){
  // 正常メッセージ
  set_message('商品を登録しました。');
}else {
  // 異常メッセージ
  set_error('商品の登録に失敗しました。');
}

// admin.php にリダイレクト
redirect_to(ADMIN_URL);