<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
//セッション開始をコール
session_start();
//$_SESSION['user_id']が格納sれていなければログイン画面へリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
//データベース接続
$db = get_db_connect();
//ログインしているユーザーの情報を$userに格納
$user = get_login_user($db);
//adminユーザーか確認
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
//フォームから送られてきた情報を格納
$name = get_post('name');
$price = get_post('price');
$status = get_post('status');
$stock = get_post('stock');

$image = get_file('image');
//商品を登録
if(regist_item($db, $name, $price, $stock, $status, $image)){
  set_message('商品を登録しました。');
}else {
  set_error('商品の登録に失敗しました。');
}

//リダイレクト
redirect_to(ADMIN_URL);