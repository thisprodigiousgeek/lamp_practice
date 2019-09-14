<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';

// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';

// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';

// itemデータに関する関数ファイルを読み込み。
require_once MODEL_PATH . 'item.php';

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

//admin用のログインチェック用関数を利用
if(is_admin($user) === false){

// ユーザー名が違った場合はログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

//item_idデータを関数get_postを利用し取得
$item_id = get_post('item_id');

//destroy_item関数を利用して商品の消去機能を実行
if(destroy_item($db, $item_id) === true){

//商品消去が成功した場合下記のメッセージを表示
  h(set_message('商品を削除しました。'));
} else {
//商品消去が失敗した場合下記のメッセージを表示
  h(set_error('商品削除に失敗しました。'));
}

is_valid_csrf_token($token);
// ビューの読み込み。
redirect_to(ADMIN_URL); 