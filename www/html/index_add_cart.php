<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

//ログインしていなければ、ログイン画面へ
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//DB接続を行う関数を変数に代入
$db = get_db_connect();
//ログインユーザーの情報を取得する関数を変数に代入
$user = get_login_user($db);

//送られてきたitem_idの値を取得する関数を変数に代入
$item_id = get_post('item_id');

//ユーザーIDに応じた、"カートに入れる"を押した時の処理が成功した時、以下のメッセージを表示。
if(add_cart($db,$user['user_id'], $item_id)){
  set_message('カートに商品を追加しました。');
} else {
  set_error('カートの更新に失敗しました。');
}

redirect_to(HOME_URL);