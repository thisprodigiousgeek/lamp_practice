<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();
//ログインしていなかったらログインページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
//PDOを利用してデータベース接続
$db = get_db_connect();
//PDOを利用してログインユーザーのデータをを取得
$user = get_login_user($db);
//ログインユーザーが管理者アカウントではなかったらログインページへリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

$name = get_post('name');//ポストされたユーザー名取得
$price = get_post('price');//価格を取得
$status = get_post('status');//ステータスを取得
$stock = get_post('stock');//在庫数を取得

$image = get_file('image');//画像ファイル取得
//商品登録関数の利用、成功したら完了メッセージ
if(regist_item($db, $name, $price, $stock, $status, $image)){
  set_message('商品を登録しました。');
//失敗したらエラーメッセージ
}else {
  set_error('商品の登録に失敗しました。');
}

//管理者ページにリダイレクト
redirect_to(ADMIN_URL);