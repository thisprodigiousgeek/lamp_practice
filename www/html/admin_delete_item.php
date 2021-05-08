<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();
if(is_valid_csrf_token($_POST['csrf_token']) === false) {
  redirect_to(LOGIN_URL);
}
//ログインしていなかったらログインページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
//PDOを取得
$db = get_db_connect();
//ログインユーザーの情報を取得
$user = get_login_user($db);
//アカウントが管理者アカウントではなかったらログインページにリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
//item_idを取得
$item_id = get_post('item_id');

//商品削除の関数,削除できたら完了メッセージ
if(destroy_item($db, $item_id) === true){
  set_message('商品を削除しました。');
//削除できなかったらエラーメッセージ
} else {
  set_error('商品削除に失敗しました。');
}


//管理者ページにリダイレクト
redirect_to(ADMIN_URL);