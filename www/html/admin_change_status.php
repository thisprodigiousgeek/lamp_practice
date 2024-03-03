<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

//セッション開始
session_start();

//postから送られたtokenを$tokenに代入
$token = get_post('token');

//セッションに保存されている'csrf_token'とpostから受け取った$tokenの値が一致しているか確認。
if(is_valid_csrf_token($token) === false){

  //一致していない場合ログインページへリダイレクトしログインを要求する。
  redirect_to(LOGIN_URL);

}

//一致が確認できたらtokenを削除
unset($_SESSION['csrf_token']);

//ログイン状態では無い場合ログインページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL); 
}

//データベースに接続
$db = get_db_connect();

//ログイン中のユーザー情報の取得
$user = get_login_user($db);

//ログインページへリダイレクト 条件式の部分?
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

//ステータス変更の対象の商品のitem_idを$item_idに代入
$item_id = get_post('item_id');

//ステータス変更の対象の商品のステータスをを$changes_toに代入
$changes_to = get_post('changes_to');

//$changes_toの値がopenの場合
if($changes_to === 'open'){

  //ステータスを公開状態にする
  update_item_status($db, $item_id, ITEM_STATUS_OPEN);
  set_message('ステータスを変更しました。');

//$changes_toの値がcloseの場合
}else if($changes_to === 'close'){

  //ステータスを非公開状態にする
  update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
  set_message('ステータスを変更しました。');

//$changes_toの値がopenでもcloseでも無い場合
}else {
  set_error('不正なリクエストです。');
}

//?
redirect_to(ADMIN_URL);