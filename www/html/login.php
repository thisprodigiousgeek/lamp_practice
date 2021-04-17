<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
// ログインチェック用に、セッションの開始
session_start();
// ログインチェック用の関数を呼び出し
if(is_logined() === true){
  // ログインしている場合は、index画面にリダイレクト
  redirect_to(HOME_URL);
}
// ビューファイルの読み込み
include_once VIEW_PATH . 'login_view.php';