<?php
// それぞれのページから関数を取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

// セッションを開始
session_start();

// ログインがされていなければログインページに返す
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// データベースに接続
$db = get_db_connect();

// ユーザ情報の取得
$user = get_login_user($db);

// 管理者でなければログインページに移動
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

// POSTから商品IDを取得
$item_id = get_post('item_id');

// 商品を削除
if(destroy_item($db, $item_id) === true){
  set_message('商品を削除しました。');
} else {
  set_error('商品削除に失敗しました。');
}

// adminページに戻る
redirect_to(ADMIN_URL);