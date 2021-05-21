<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

$post_token = get_post('token');//ポストで隠されて来たトークンにあだ名つける
is_valid_csrf_token($post_token);//ポストで来たトークンをバリデする

$cart_id = get_post('cart_id');
$amount = get_post('amount');

if(is_valid_csrf_token(get_post('token')) === false){//ポストされてきたトークンがバリデしたけどfalseで返してきよったら（つまりポストされたやつとセッションに入ってるやつが一致せんかったら
  set_error('不正な処理が行われました');//セッション箱のエラーのとこに入れる
  $_SESSION = array();//セッション箱空にする
  redirect_to(LOGIN_URL);//ログインページに戻らせる
} else {

  if(update_cart_amount($db, $cart_id, $amount)){
    set_message('購入数を更新しました。');
  } else {
    set_error('購入数の更新に失敗しました。');
  }

$_SESSION['csrf_token'] = '';//トークンの破棄
get_csrf_token();//トークンまた新しく作る
}

redirect_to(CART_URL);