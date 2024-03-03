<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

//セッション開始
session_start();

//送信されたpostから'token'を$tokenに代入
$token = get_post('token');

//セッションに保存されている'csrf_token'とpostから受け取った$tokenの値が一致しているか確認。
if(is_valid_csrf_token($token) === false){

  //一致していない場合ログインページへリダイレクトしログインを要求する。
  redirect_to(LOGIN_URL);
}

//一致が確認できたらtokenを削除
unset($_SESSION['csrf_token']);

//ログイン中では無い場合ログインページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//データベース接続
$db = get_db_connect();

//ログインしているユーザーのユーザーidを取得
$user = get_login_user($db);

//ログインページにリダイレクト 条件式?
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

//商品名を$nameに代入
$name = get_post('name');

//商品価格を$priceに代入
$price = get_post('price');

//商品の公開ステータスを$statusに代入
$status = get_post('status');

//商品の在庫数を$stockに代入
$stock = get_post('stock');

//商品の画像を$imageに代入
$image = get_file('image');

//商品登録の可不可
if(regist_item($db, $name, $price, $stock, $status, $image)){

  //商品登録できた場合
  set_message('商品を登録しました。');

  //商品登録できなかった場合
}else {
  set_error('商品の登録に失敗しました。');
}

//?
redirect_to(ADMIN_URL);