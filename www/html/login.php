<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';

session_start();
//ログインしていたら商品一覧ページに飛ぶ
if(is_logined() === true){
  redirect_to(HOME_URL);
}
// ログインページの読み込み
include_once VIEW_PATH . 'login_view.php';