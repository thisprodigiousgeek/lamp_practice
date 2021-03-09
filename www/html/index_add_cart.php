<?php
//定義ファイルの読み込み
require_once '../conf/const.php';
//関数ファイルの読み込み
require_once MODEL_PATH . 'functions.php';
//ユーザーファイルの読み込み
require_once MODEL_PATH . 'user.php';
//商品情報ファイルの読み込み
require_once MODEL_PATH . 'item.php';
//カート情報ファイルの読み込み
require_once MODEL_PATH . 'cart.php';

//セッションスタート
session_start();

//ログインされなかったらログインページへ
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//DB接続
$db = get_db_connect();
//ログインユーザー接続
$user = get_login_user($db);

//item_idのポスト取得
$item_id = get_post('item_id');

//カートに商品追加
if(add_cart($db,$user['user_id'], $item_id)){
  set_message('カートに商品を追加しました。');
} else {
  set_error('カートの更新に失敗しました。');
}

//ホームページへ
redirect_to(HOME_URL);