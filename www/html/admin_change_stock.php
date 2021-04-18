<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

//セッションスタート
session_start();

//セッションにトークンが保存されていなければログイン画面にリダイレクト
if(is_valid_csrf_token($_POST['token']) === false){
  redirect_to(LOGIN_URL);
}else{
  //トークンの破棄
  unset($_SESSION['token']);
}

//ログインされていない状態ならばログイン画面にリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//DB接続
$db = get_db_connect();

//ユーザー情報を取得
$user = get_login_user($db);

//adminユーザーでなければログイン画面にリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

//post送信を取得
$item_id = get_post('item_id');
$stock = get_post('stock');

//在庫数変更処理
if(update_item_stock($db, $item_id, $stock)){
  set_message('在庫数を変更しました。');
} else {
  set_error('在庫数の変更に失敗しました。');
}

redirect_to(ADMIN_URL);