<?php
// 設定ファイル読込
require_once '../conf/const.php';
// 関数ファイル読込
require_once MODEL_PATH . 'functions.php';
// セッション開始
session_start();
// ログインしていない場合ログイン画面にリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}
// サインアップ画面のviewファイル出力
include_once VIEW_PATH . 'signup_view.php';



