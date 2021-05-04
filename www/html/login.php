<?php
//ログイン済みかどうかチェックする

// 設定ファイル読み込み
require_once '../conf/const.php';
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';

// セッション開始
session_start();

//ログイン済みのユーザーである場合、商品一覧ページへリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}

// login_viewファイル読み込み
include_once VIEW_PATH . 'login_view.php';