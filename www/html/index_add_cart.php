<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
//セッション開始をコール
session_start();
//$_SESSION['user_id']が存在しなければログイン画面へリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
//データベース接続、ログインしているユーザーの情報を$userに格納
$db = get_db_connect();
$user = get_login_user($db);

//フォームから送られてきた情報を格納
$item_id = get_post('item_id');
//商品をカートに追加
if(add_cart($db,$user['user_id'], $item_id)){
  set_message('カートに商品を追加しました。');
} else {
  set_error('カートの更新に失敗しました。');
}

redirect_to(HOME_URL);