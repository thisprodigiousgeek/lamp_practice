<?php
//定義ファイル読み込み
require_once '../conf/const.php';
//汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
//userデータに関するファイル読み込み
require_once MODEL_PATH . 'user.php';
//itemデータに関するファイル読み込み
require_once MODEL_PATH . 'item.php';

//ログインチェックするため、セッション開始
session_start();

//ログインチェック用関数を利用
if(is_logined() === false){
  //ログインしていない場合はログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

//データベース接続
$db = get_db_connect();
//ログインユーザーのデータを取得
$user = get_login_user($db);

//ユーザーチェック
if(is_admin($user) === false){
  //一致しなかった場合ログイン画面に飛ばす
  redirect_to(LOGIN_URL);
}

//postで送られてきたデータ
$item_id = get_post('item_id');
$changes_to = get_post('changes_to');

//ステータス変更
//openだった場合
if($changes_to === 'open'){
  //DBのitemsテーブルデータを変更
  update_item_status($db, $item_id, ITEM_STATUS_OPEN);
  set_message('ステータスを変更しました。');
//closeだった場合
}else if($changes_to === 'close'){
  //DBのitemsテーブルデータを変更
  update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
  set_message('ステータスを変更しました。');
//それ以外
}else {
  set_error('不正なリクエストです。');
}

//商品管理ページへ遷移
redirect_to(ADMIN_URL);