<?php
//定義ファイル読み込み
require_once '../conf/const.php';
//汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';

//ログインチェックするため、セッション開始
session_start();

//ログインチェック用関数を利用
if(is_logined() === true){
  //ログインしている場合はホームページにリダイレクト
  redirect_to(HOME_URL);
}

//ビュー読み込み
include_once VIEW_PATH . 'signup_view.php';



