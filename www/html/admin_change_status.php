<?php
require_once '../conf/const.php'; //定義ファイル読み込み
require_once MODEL_PATH . 'functions.php'; //function.php読み込み
require_once MODEL_PATH . 'user.php';//user.php読み込み
require_once MODEL_PATH . 'item.php';//item.php読み込み
//セッション開始をコール
session_start();
//$_SESSION['user_id']が格納されていなければリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
//データベース接続
$db = get_db_connect();
//ログインしている（セッションに登録されているIDの）ユーザーの情報を$userに格納
$user = get_login_user($db);
//adminユーザーか確認
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
//$_POST['']　フォームから送られてきた情報を代入
$item_id = get_post('item_id');
$changes_to = get_post('changes_to');
//公開ステータスを変更する
if($changes_to === 'open'){
  update_item_status($db, $item_id, ITEM_STATUS_OPEN);
  set_message('ステータスを変更しました。');
}else if($changes_to === 'close'){
  update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
  set_message('ステータスを変更しました。');
}else {
  set_error('不正なリクエストです。');
}

//リダイレクト
redirect_to(ADMIN_URL);