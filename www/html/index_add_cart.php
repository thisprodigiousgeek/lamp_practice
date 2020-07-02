<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

// ログイン済みか確認し、falseならloginページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// DB接続
$db = get_db_connect();
// login済みのuser_idをセッションから取得して変数に格納
$user = get_login_user($db);

// hiddenで送信されたitem_idを変数に格納
$item_id = get_post('item_id');

// 商品を追加する（追加or更新）
if(add_cart($db,$user['user_id'], $item_id)){
  set_message('カートに商品を追加しました。');
} else {
  set_error('カートの更新に失敗しました。');
}

redirect_to(HOME_URL);