<?php
//定義ファイルの読み込み
require_once '../conf/const.php';
//関数ファイルの読み込み
require_once MODEL_PATH . 'functions.php';
//ユーザーファイルの読み込み
require_once MODEL_PATH . 'user.php';
//商品管理ファイルの読み込み
require_once MODEL_PATH . 'item.php';

//セッションスタート
session_start();

//ログインされなければログインページへ
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//DB接続
$db = get_db_connect();

//ログインユーザーページへ接続
$user = get_login_user($db);

//管理者ページへ接続できなければログインページへ
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

//ポストの取得
$item_id = get_post('item_id');
$changes_to = get_post('changes_to');

//ステータスの公開したら
if($changes_to === 'open'){
  //商品ステータスを更新
  update_item_status($db, $item_id, ITEM_STATUS_OPEN);
  //メッセージ
  set_message('ステータスを変更しました。');

  //商品ステータスを非公開にしたら
}else if($changes_to === 'close'){
  update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
  //メッセージ
  set_message('ステータスを変更しました。');

  //その他
}else {
  set_error('不正なリクエストです。');
}

//管理者URLへリダイレクト
redirect_to(ADMIN_URL);