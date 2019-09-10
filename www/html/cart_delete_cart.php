<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';

// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';

// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';

// itemデータに関する関数ファイルを読み込み。
require_once MODEL_PATH . 'item.php';

// cartデータに関する関数ファイルを読み込み。
require_once MODEL_PATH . 'cart.php';

//セッションをスタートする
session_start();

// ログインチェック用関数を利用
if(is_logined() === false){
  // ログインしていない場合はログインページにリダイレクト
  redirect_to(LOGIN_URL);

}
// PDOを取得
$db = get_db_connect();

// PDOを利用してログインユーザーのデータを取得
$user = get_login_user($db);

//cart_idデータを関数get_postを利用し取得
$cart_id = get_post('cart_id');

//delete_cartを利用しカートの中身の消去を実行する
if(delete_cart($db, $cart_id)){

//カートの中身の消去が成功した場合下記のメッセージを表示する
  set_message('カートを削除しました。');
} else {

  //カートの中身の消去が失敗した場合下記のメッセージを表示する
  set_error('カートの削除に失敗しました。');
}

// ビューの読み込み。
redirect_to(CART_URL);