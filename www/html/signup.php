<?php
//ユーザー登録ページ

//定数ファイルを読み込み
require_once '../conf/const.php';
//汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';

session_start();

//ログインユーザーであれば、商品一覧ページへ
if(is_logined() === true){
  redirect_to(HOME_URL);
}

//signup_viewファイルを読み込み
include_once VIEW_PATH . 'signup_view.php';



