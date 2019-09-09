<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';

// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';

// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';

// itemデータに関する関数ファイルを読み込み。
require_once MODEL_PATH . 'item.php';

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

//admin用のログインチェック用関数を利用
if(is_admin($user) === false){
  
// ユーザー名が違った場合はログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

//nameデータを関数get_postを利用し取得
$name = get_post('name');

//priceデータを関数get_postを利用し取得
$price = get_post('price');

//statusデータを関数get_postを利用し取得
$status = get_post('status');

//stockデータを関数get_postを利用し取得
$stock = get_post('stock');

//iamgeデータを関数get_fileを利用し取得
$image = get_file('image');

//regist_item関数を利用して商品を登録する
if(regist_item($db, $name, $price, $stock, $status, $image)){

//商品登録成功した場合下記のメッセージを表示
  set_message('商品を登録しました。');

}else {

//商品登録失敗した場合下記のメッセージを表示
  set_error('商品の登録に失敗しました。');
}

// ビューの読み込み。
redirect_to(ADMIN_URL);