<?php
// [[ カートから商品を削除するPOSTを実行した時 ]]


// 定数ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// 各モデル(関数)ファイル読み込み
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

// ログインされてなければログインページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

// DB関連の設定
$db = get_db_connect();
// user_idがセッションにセットされていれば、ユーザー情報を取得して変数に代入
$user = get_login_user($db);

// ポストでcart_idが送られてきたことを確認して、変数に代入
$cart_id = get_post('cart_id');

// 指定した商品の削除
if(delete_cart($db, $cart_id)){
  set_message('カートを削除しました。');
} else {
  set_error('カートの削除に失敗しました。');
}

// カート画面にリダイレクト
redirect_to(CART_URL);