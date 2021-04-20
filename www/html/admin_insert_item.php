<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
// ログインチェックのために、セッションを開始
session_start();
// ログインチェック用関数の呼び出し
if(is_logined() === false){
  // ログインしていない場合、ログイン画面にリダイレクト
  redirect_to(LOGIN_URL);
}
// PDOの取得
$db = get_db_connect();
// ログインuser情報の取得用関数の呼び出し
$user = get_login_user($db);
// ユーザタイプが管理者であるか判定
if(is_admin($user) === false){
  // 管理者でない場合、ログイン画面にリダイレクト
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

$name = get_post('name');
$price = get_post('price');
$status = get_post('status');
$stock = get_post('stock');
$image = get_file('image');

// 新規商品追加用の関数の呼び出し
if(regist_item($db, $name, $price, $stock, $status, $image)){
  // セッションにメッセージを追加する関数の呼び出し
  set_message('商品を登録しました。');
}else {
  // セッションにメッセージを追加する関数の呼び出し
  set_error('商品の登録に失敗しました。');
}

// 管理者画面にリダイレクト
redirect_to(ADMIN_URL);