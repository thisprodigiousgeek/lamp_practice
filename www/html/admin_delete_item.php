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
$item_id = get_post('item_id');

//アイテムと画像の削除
if(destroy_item($db, $item_id) === true){
  //削除出来たらセッション関数のメッセージへ
  set_message('商品を削除しました。');
} else {
  //削除失敗したらセッション関数のエラーへ
  set_error('商品削除に失敗しました。');
}

//商品管理ページへ遷移
redirect_to(ADMIN_URL);