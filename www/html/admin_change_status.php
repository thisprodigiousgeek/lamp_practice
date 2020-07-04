<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

//ログインしていなければ、ログイン画面へ
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//データベース接続を行う関数を変数に代入
$db = get_db_connect();

//ログイン中のユーザーIDを取得する関数を変数に代入
$user = get_login_user($db);

//取得したユーザー情報が管理者でなかった場合、ログイン画面へ
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

//送られてきたitem_idの値を取得する関数を変数に代入
$item_id = get_post('item_id');
//送られてきたchanges_toの値を取得する関数を変数に代入
$changes_to = get_post('changes_to');
//送られてきたtokenの値を取得する関数を変数に代入
$token = get_post('token');

//トークンがあれば処理を受け付ける。(CSRF対策)
if(is_valid_csrf_token($token)) { 
  if($changes_to === 'open'){
    update_item_status($db, $item_id, ITEM_STATUS_OPEN);
    set_message('ステータスを変更しました。');
  }else if($changes_to === 'close'){
    update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
    set_message('ステータスを変更しました。');
  }else {
    set_error('不正なリクエストです。');
  }
} else {
  set_error('不正な処理です');
}

//管理画面へ移行
redirect_to(ADMIN_URL);