<?php
/*
* 商品のステータス変更ファイル
*/

require_once '../conf/const.php'; //定数関数ファイルの読み込み
require_once MODEL_PATH . 'functions.php'; //共通関数ファイルの読み込み
require_once MODEL_PATH . 'user.php'; //ユーザーデータ用関数ファイルの読み込み
require_once MODEL_PATH . 'item.php'; //商品用関数ファイルの読みこみ

//セッション開始、再開
session_start();

//ログイン可否判断
if(is_logined() === false){
  //ログインしていなかった場合、login.php
  redirect_to(LOGIN_URL);
}

//データベースへ接続（sql実行準備）
$db = get_db_connect();

//ログインユーザーの情報を取得して、変数へ代入
$user = get_login_user($db);

//管理者可否判断
if(is_admin($user) === false){
  //管理者でなかった場合、login.php
  redirect_to(LOGIN_URL);
}

//POST値取得して、変数へ代入
$item_id = get_post('item_id');
$changes_to = get_post('changes_to');

//公開、非公開を変更しての真偽値
if($changes_to === 'open'){
  //該当商品のステータスを公開へ変更
  update_item_status($db, $item_id, ITEM_STATUS_OPEN);
  //メッセージを設定
  set_message('ステータスを変更しました。');
}else if($changes_to === 'close'){
  //該当商品のステータスを非公開へ変更
  update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
  //メッセージを設定
  set_message('ステータスを変更しました。');
}else {
  //メッセージを設定
  set_error('不正なリクエストです。');
}

//管理者ページへ遷移
redirect_to(ADMIN_URL);