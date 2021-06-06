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

//ユーザー情報の取得
$user = get_login_user($db);

//adminユーザーでなければログイン画面にリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

//post送信を取得
$item_id = get_post('item_id');
$changes_to = get_post('changes_to');

//ステータス変更の処理
if($changes_to === 'open'){
  update_item_status($db, $item_id, ITEM_STATUS_OPEN);
  set_message('ステータスを変更しました。');
}else if($changes_to === 'close'){
  update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
  set_message('ステータスを変更しました。');
}else {
  set_error('不正なリクエストです。');
}


redirect_to(ADMIN_URL);