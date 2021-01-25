<?php
//定義ファイル読み込み
require_once '../conf/const.php';
//汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
//userデータに関するファイル読み込み
require_once MODEL_PATH . 'user.php';
//itemデータに関するファイル読み込み
require_once MODEL_PATH . 'item.php';
//cartデータに関するファイル読み込み
require_once MODEL_PATH . 'cart.php';

//ログインチェックするため、セッション開始
session_start();

//ログインチェック用関数を利用
if(is_logined() === false){
  //ログインしていない場合はログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

//DB接続
$db = get_db_connect();
//ログインユーザーのデータを取得
$user = get_login_user($db);

//postデータの取得
$item_id = get_post('item_id');

//カートにアイテムを入れて数量を変更する
if(add_cart($db,$user['user_id'], $item_id)){
  //セッション変数にメッセージ表示
  set_message('カートに商品を追加しました。');
} else {
  //セッション変数にエラー表示
  set_error('カートの更新に失敗しました。');
}

//ホームページへ遷移
redirect_to(HOME_URL);