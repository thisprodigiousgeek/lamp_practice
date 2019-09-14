<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';

// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';

// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';

// itemデータに関する関数ファイルを読み込み。
require_once MODEL_PATH . 'item.php';

//セッションをスタートする
session_start();

// ログインチェック用関数を利用
if(is_logined() === false){

  // ログインしていない場合はログインページにリダイレクト
  redirect_to(LOGIN_URL);
}
// PDOを取得
$db = get_db_connect();

// PDOを利用してログインユーザーのデータを取得
$user = get_login_user($db);

//admin用のログインチェック用関数を利用
if(is_admin($user) === false){

// ユーザー名が違った場合はログインページにリダイレクト
  redirect_to(LOGIN_URL);
}
//item_idデータを関数get_postを利用し取得
$item_id = get_post('item_id');

//changes_toデータを関数get_postを利用し取得
$changes_to = get_post('changes_to');

// ステータス変更用関数を利用
if($changes_to === 'open'){

//update_item_status関数を利用してステータスを変更する
  update_item_status($db, $item_id, ITEM_STATUS_OPEN);

//ステータスの変更に成功した場合下記のメッセージを表示
  set_message('ステータスを変更しました。');
//$changes_toがcloseだった場合
}else if($changes_to === 'close'){

//update_item_status関数を利用してステータスを変更する
  update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
  
//ステータスの変更が成功した場合下記のメッセージを表示
  set_message('ステータスを変更しました。');
}else {
//ステータスの変更が失敗した場合下記のメッセージを表示
  set_error('不正なリクエストです。');
}

// ビューの読み込み。
redirect_to(ADMIN_URL);