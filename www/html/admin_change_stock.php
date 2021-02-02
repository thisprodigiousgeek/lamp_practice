<?php
//定義ファイル読み込み
require_once '../conf/const.php';
//汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
//userデータに関するファイル読み込み
require_once MODEL_PATH . 'user.php';
//itemデータに関するファイル読み込み
require_once MODEL_PATH . 'item.php';

//ログインチェックするため、セッション開始
session_start();

//ログインチェック用関数を利用
if(is_logined() === false){
  //ログインしていない場合はログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

//データベース接続
$db = get_db_connect();
//ログインユーザーのデータを取得
$user = get_login_user($db);

//ユーザーチェック
if(is_admin($user) === false){
  //一致しなかった場合ログイン画面に飛ばす
  redirect_to(LOGIN_URL);
}

//postで送られてきたデータ
$item_id = get_post('item_id');
$stock = get_post('stock');
$token = get_post('token');

//トークンチェック
if(is_valid_csrf_token($token) === false){
  //ログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

//在庫数変更
if(update_item_stock($db, $item_id, $stock)){
  set_message('在庫数を変更しました。');
//それ以外
} else {
  set_error('在庫数の変更に失敗しました。');
}

//商品管理ページへ遷移
redirect_to(ADMIN_URL);