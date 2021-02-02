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

//トークン生成
$token = get_csrf_token();

//DB接続
$db = get_db_connect();

//ログインユーザーのデータを取得
$user = get_login_user($db);

//ユーザーチェック
if(is_admin($user) === false){
  //一致しなかった場合ログイン場面に飛ばす
  redirect_to(LOGIN_URL);
}

//商品データを取得
$items = get_all_items($db);

//ビューの読み込み
include_once VIEW_PATH . '/admin_view.php';
