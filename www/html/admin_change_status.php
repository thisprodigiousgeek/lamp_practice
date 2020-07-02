<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

// ログイン済みか確認し、falseならloginページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// DBに接続する
$db = get_db_connect();

// login済みのuser_idをセッションから取得して変数に格納
$user = get_login_user($db);

// 管理者かどうかチェックして、falseならloginページへリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

// post送信されたitem_idを変数に格納
$item_id = get_post('item_id');
// hidden送信された値を変数に格納（close or open）
$changes_to = get_post('changes_to');

// ステータスの変更処理
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