<?php

// [[ 商品ステータス更新のPOSTが送信された際の処理ページ ]]


// 定数ファイル＆関数ファイルの読み込み
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

// ユーザーログインされてなければ、ログイン画面へリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// db設定
$db = get_db_connect();

// ユーザー情報を変数へ代入
$user = get_login_user($db);

// 管理ユーザーでなければ、ログイン画面へリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

// item_idとchanges_toがPOSTで送られてきていれば、変数に代入
$item_id = get_post('item_id');
$changes_to = get_post('changes_to');

// statusの更新処理
if($changes_to === 'open'){
  // statusを１に更新
  update_item_status($db, $item_id, ITEM_STATUS_OPEN);
  set_message('ステータスを変更しました。');
}else if($changes_to === 'close'){
  // statusを0に更新
  update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
  set_message('ステータスを変更しました。');
}else {
  set_error('不正なリクエストです。');
}

// 管理ページへリダイレクト
redirect_to(ADMIN_URL);