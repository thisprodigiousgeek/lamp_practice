<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';

session_start();
// ログインしていたら商品一覧ページにリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}
// ユーザ登録ページを読み込み
include_once VIEW_PATH . 'signup_view.php';



