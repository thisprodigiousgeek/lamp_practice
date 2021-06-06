<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

//セッションスタート
session_start();

//セッションにトークンが保存されていなければログイン画面にリダイレクト
if(is_valid_csrf_token($_POST['token']) === false){
  redirect_to(LOGIN_URL);
}else{
  //トークンの破棄
  unset($_SESSION['token']);
}

//ログインされていない状態ならばログイン画面にリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//DB接続
$db = get_db_connect();
//ユーザー情報を取得
$user = get_login_user($db);

//post送信されたitem_idを取得
$item_id = get_post('item_id');

//カート追加処理
if(add_cart($db,$user['user_id'], $item_id)){
  set_message('カートに商品を追加しました。');
} else {
  set_error('カートの更新に失敗しました。');
}

redirect_to(HOME_URL);