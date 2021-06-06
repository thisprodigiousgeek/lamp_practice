<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';

//セッションスタート
session_start();

//セッションにtokenを保存し、ランダムな文字列を$tokenに代入
$token = get_csrf_token();

//ログインされている状態ならばホーム画面にリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}

include_once VIEW_PATH . 'signup_view.php';



