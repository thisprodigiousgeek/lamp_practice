<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
//セッション開始をコール
session_start();
//＄_SESSION['user_id']が格納されていなければログイン画面へリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
//データベース接続
$db = get_db_connect();
//ログインしているユーザーの情報を$userに格納
$user = get_login_user($db);
//adminユーザーかを確認
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
//フォームから送られてきたデータを格納
$item_id = get_post('item_id');

//該当する商品の情報を削除
if(destroy_item($db, $item_id) === true){
  set_message('商品を削除しました。');
} else {
  set_error('商品削除に失敗しました。');
}


//リダイレクト
redirect_to(ADMIN_URL);