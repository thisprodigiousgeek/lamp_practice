<?php
/*
* 管理者用ログインファイル
*/

require_once '../conf/const.php'; //定数関数ファイルの読み込み
require_once MODEL_PATH . 'functions.php'; //共通関数ファイルの読み込み
require_once MODEL_PATH . 'user.php'; //ユーザーデータ用関数ファイルの読み込み
require_once MODEL_PATH . 'item.php'; //商品用関数ファイルの読みこみ

//新しいセッションを開始、あるいは既存のセッションを開始（7.1.0からsession開始、falseを返すようになった）
session_start();

//ログイン可否判断
if(is_logined() === false){
  //ログインしていなかった場合、login.php
  redirect_to(LOGIN_URL);
}

//データベースへ接続（sql実行準備）
$db = get_db_connect();

//ログインユーザーの情報を取得して、変数へ代入
$user = get_login_user($db);

//管理者可否判断
if(is_admin($user) === false){
  //管理者ではなかった場合、login.php
  redirect_to(LOGIN_URL);
}

//商品一覧を取得して、変数へ代入
$items = get_all_items($db);
//管理者用ページへ遷移
include_once VIEW_PATH . '/admin_view.php';
