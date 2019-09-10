<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';

// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';

//セッションをスタートする
session_start();

// ログインチェック用関数を利用
if(is_logined() === true){

// ログインしていない場合はログインページにリダイレクト
  redirect_to(HOME_URL);
}

// ビューの読み込み。
include_once '../view/signup_view.php';



