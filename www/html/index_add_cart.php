<?php
// 設定ファイル読込
require_once '../conf/const.php';
// 関数ファイル読込
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
// セッション開始
session_start();
// ログインしていない場合ログイン画面にリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
// DB接続
$db = get_db_connect();
// ログインユーザ情報取得
$user = get_login_user($db);

// POST値取得
$item_id = get_post('item_id');
// カートに商品追加
if(add_cart($db,$user['user_id'], $item_id)){
  // 正常メッセージ
  set_message('カートに商品を追加しました。');
} else {
  // 異常メッセージ
  set_error('カートの更新に失敗しました。');
}
// ホームページにリダイレクト
redirect_to(HOME_URL);