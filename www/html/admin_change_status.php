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

if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
// POST値取得
$item_id = get_post('item_id');
$changes_to = get_post('changes_to');
// ステータス変更
if($changes_to === 'open'){
  // open -> close
  update_item_status($db, $item_id, ITEM_STATUS_OPEN);
  // 正常メッセージ
  set_message('ステータスを変更しました。');
}else if($changes_to === 'close'){
  // close -> open
  update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
  // 正常メッセージ
  set_message('ステータスを変更しました。');
}else {
  // 異常メッセージ
  set_error('不正なリクエストです。');
}

// admin.phpへリダイレクト
redirect_to(ADMIN_URL);