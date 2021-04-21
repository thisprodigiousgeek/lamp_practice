<?php
// 定数ファイルの読み込み
require_once '../conf/const.php';
// 汎用関数ファイルの読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関する関数ファイルの読み込み
require_once MODEL_PATH . 'user.php';
// ログインチェックのために、セッションを開始
session_start();
// ログインチェック用の関数を呼び出し
if(is_logined() === true){
  // ログインしていない場合、index画面にリダイレクト
  redirect_to(HOME_URL);
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

// ユーザ名の取得、postデータ取得用関数の呼び出し
$name = get_post('name');
// pwの取得、postデータ取得用関数の呼び出し
$password = get_post('password');
// 確認用pwの取得、postデータ取得用関数の呼び出し
$password_confirmation = get_post('password_confirmation');
// PDOの取得
$db = get_db_connect();

try{
  // 新規ユーザー登録用の関数の呼び出し
  $result = regist_user($db, $name, $password, $password_confirmation);
  if( $result=== false){
    // sessionにメッセージを追加する関数の呼び出し
    set_error('ユーザー登録に失敗しました。');
    // サインアップ画面にリダイレクト
    redirect_to(SIGNUP_URL);
  }
  // 例外処理
}catch(PDOException $e){
  // sessionにメッセージを追加する関数の呼び出し
  set_error('ユーザー登録に失敗しました。');
  // サインアップ画面にリダイレクト
  redirect_to(SIGNUP_URL);
}
// sessionにメッセージを追加する関数の呼び出し
set_message('ユーザー登録が完了しました。');
// ログイン処理用の関数の呼び出し
login_as($db, $name, $password);
// index画面にリダイレクト
redirect_to(HOME_URL);