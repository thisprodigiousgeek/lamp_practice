<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

//ログインチェックを行うため、セッションを開始する
session_start();

// $_SESSION['user_id']があるかチェック
if(is_logined() === false){
  // ログインしてないときはログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

// PDOを取得
$db = get_db_connect();
// PDOを利用してログインユーザーのデータを取得
$user = get_login_user($db);

// POSTで取得
$item_id = get_post('item_id');

if(add_cart($db,$user['user_id'], $item_id)){
  // $_SESSION[]変数に文字列を代入
  set_message('カートに商品を追加しました。');
} else {
  // $_SESSION[]変数に文字列を代入
  set_error('カートの更新に失敗しました。');
}

redirect_to(HOME_URL);