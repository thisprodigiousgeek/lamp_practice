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
$item_id = get_post('item_id');

// item_id照会：商品テーブルから商品削除
if(destroy_item($db, $item_id) === true){
  // 正常メッセージ
  set_message('商品を削除しました。');
} else {
  // 異常メッセージ
  set_error('商品削除に失敗しました。');
}

// admin.phpにリダイレクト
redirect_to(ADMIN_URL);