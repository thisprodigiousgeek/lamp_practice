<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();
//session(user_id)が設定されてるかの確認されてないならloginページへ
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
//dbからログインＩＤを取得
$db = get_db_connect();
$user = get_login_user($db);


$item_id = get_post('item_id');
//model/cart.phpに関数 エラーが出たらメッセージを出す
if(add_cart($db,$user['user_id'], $item_id)){
  set_message('カートに商品を追加しました。');
} else {
  set_error('カートの更新に失敗しました。');
}

redirect_to(HOME_URL);