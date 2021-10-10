<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

//セッション開始
session_start();

//postで送信されたtokenの取得
$token = get_post('token');

//セッションに保存されている'csrf_token'とpostから受け取った$tokenの値が一致しているか確認
if(is_valid_csrf_token($token) === false){

  //一致していなければログインページへリダイレクトし、ログインを要求する
  redirect_to(LOGIN_URL);

}

//一致していればtokenを削除する
unset($_SESSION['csrf_token']);

//ログイン中では無い場合ログインページにリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//データベース接続
$db = get_db_connect();

//ログイン中のユーザーのユーザーidを取得
$user = get_login_user($db);

//カート追加の対象商品のitem_idを$item_idに取得
$item_id = get_post('item_id');

//jかーと追加の可不可
if(add_cart($db,$user['user_id'], $item_id)){

  //カートに追加できた場合
  set_message('カートに商品を追加しました。');

  //カートに追加できなかった場合
} else {
  set_error('カートの更新に失敗しました。');
}

//ホームページへリダイレクト
redirect_to(HOME_URL);