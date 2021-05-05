<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();
//ログインしていない場合はログインページにリダイレクトする処理
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();

$user = get_login_user($db);
//ログインしたアカウントが管理者アカウントじゃない場合ログインページにリダイレクトする処理
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

$item_id = get_post('item_id');
$changes_to = get_post('changes_to');
//ポストされた値がopenであればステータスを公開にするにして完了メッセージを表示する
if($changes_to === 'open'){
  update_item_status($db, $item_id, ITEM_STATUS_OPEN);
  set_message('ステータスを変更しました。');
//ポストされた値がcloseであればステータスを非公開にして完了メッセージを表示する
}else if($changes_to === 'close'){
  update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
  set_message('ステータスを変更しました。');
}else {
//open,close以外の値がポストされたらエラーメッセージを表示する
  set_error('不正なリクエストです。');
}

//管理ページにリダイレクトする
redirect_to(ADMIN_URL);