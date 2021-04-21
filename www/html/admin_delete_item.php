<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
// ログインチェックのために、セッション開始
session_start();
// ログインチェック用の関数を呼び出し
if(is_logined() === false){
  // ログインしていない場合、ログイン画面にリダイレクト
  redirect_to(LOGIN_URL);
}

// トークンをpostから受け取る
$token = get_post('csrf_token');
// sessionに格納しているトークンと照合
if (is_valid_csrf_token($token) === false) {
  // トークンの削除
  set_session('csrf_token','');
  // 処理の中断
  exit('不正なアクセスです');

} else {
  // トークンの削除
  set_session('csrf_token','');
  // トークンの生成
  $token = get_csrf_token();
}

// PDOの取得
$db = get_db_connect();
// ログインuserの情報取得用の関数の呼び出し
$user = get_login_user($db);
// userが管理者であるか判定
if(is_admin($user) === false){
  // 異なる場合、ログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

$item_id = get_post('item_id');

// 商品テーブルから削除、商品画像ファイルを削除する
if(destroy_item($db, $item_id) === true){
  // セッションにメッセージを追加する関数の呼び出し
  set_message('商品を削除しました。');
} else {
  // セッションにメッセージを追加する関数の呼び出し
  set_error('商品削除に失敗しました。');
}


// 管理画面にリダイレクト
redirect_to(ADMIN_URL);