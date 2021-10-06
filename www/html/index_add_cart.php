<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

//セッション開始
session_start();

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