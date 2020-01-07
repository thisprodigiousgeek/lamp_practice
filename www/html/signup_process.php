<?php
require_once '../conf/const.php';//定義ファイル読み込み
require_once MODEL_PATH . 'functions.php';//関数ファイル読み込み
require_once MODEL_PATH . 'user.php';//関数ファイル読み込み
//セッション開始
session_start();
//$_SESSION['user_id']が存在しなければログイン画面へリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}
//フォームから送られてきた情報を格納
$name = get_post('name');
$password = get_post('password');
$password_confirmation = get_post('password_confirmation');
//データベース接続
$db = get_db_connect();

try{
  $result = regist_user($db, $name, $password, $password_confirmation);//ユーザーの入力形式が正しければユーザーを新しく登録。登録が正常に実行されればTRUE、問題が発生すればFALSEを返り値として返す
    if( $result=== false){
      set_error('ユーザー登録に失敗しました。');
      redirect_to(SIGNUP_URL);//サインアップ画面へリダイレクト
  }
}catch(PDOException $e){ //上記の過程で何かしらの問題が発生した場合
  set_error('ユーザー登録に失敗しました。');
  redirect_to(SIGNUP_URL);//サインアップ画面へリダイレクト
}

set_message('ユーザー登録が完了しました。');
login_as($db, $name, $password);//ログイン
redirect_to(HOME_URL);//ホーム画面へリダイレクト