<?php
//定義ファイル読み込み
require_once '../conf/const.php';
//汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
//userデータに関するファイル読み込み
require_once MODEL_PATH . 'user.php';
//itemデータに関するファイル読み込み
require_once MODEL_PATH . 'item.php';

//ログインチェックするため、セッション開始
session_start();

//ログインチェック用関数を利用
if(is_logined() === false){
  //ログインしていない場合はログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

//データベース接続
$db = get_db_connect();
//ログインユーザーのデータを取得
$user = get_login_user($db);

//ユーザーチェック
if(is_admin($user) === false){
  //一致しなかった場合ログイン画面に飛ばす
  redirect_to(LOGIN_URL);
}

//postで送られてきたデータ
$name = get_post('name');
$price = get_post('price');
$status = get_post('status');
$stock = get_post('stock');
//ファイル名の取得
$image = get_file('image');

//アイテム登録
if(regist_item($db, $name, $price, $stock, $status, $image)){
  set_message('商品を登録しました。');
}else {
  set_error('商品の登録に失敗しました。');
}

//商品管理ページへ遷移
redirect_to(ADMIN_URL);