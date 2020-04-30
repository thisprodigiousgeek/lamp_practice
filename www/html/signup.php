<?php
// 定義ファイルを読み込み
require_once '../conf/const.php';
// 汎用関数ファイルを読み込み
// define('MODEL_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../model/');
require_once MODEL_PATH . 'functions.php';

// ログインチェックを行うため、セッションを開始する 
session_start();

// is_loginedのreturnが trueならindex.phpにリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}

// ビューの読み込み
include_once VIEW_PATH . 'signup_view.php';



