<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';

// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';

//セッションをスタートする
session_start();
//ログインが成功した時の処理をする
if(is_logined() === true){

//ホームページに誘導
  redirect_to(HOME_URL);
}
// ビューの読み込み。
include_once '../view/login_view.php';