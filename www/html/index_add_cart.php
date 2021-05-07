<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();
//ログインしていなかったらログインページにリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();//PDO利用でDB接続
$user = get_login_user($db);//DBからログインユーザ情報を取得


$item_id = get_post('item_id');//ポストされたitem_idを取得
//カートに商品を追加する関数を利用
if(add_cart($db,$user['user_id'], $item_id)){
  set_message('カートに商品を追加しました。');
//失敗したらエラーメッセージ
} else {
  set_error('カートの更新に失敗しました。');
}
//商品一覧ページにリダイレクト
redirect_to(HOME_URL);