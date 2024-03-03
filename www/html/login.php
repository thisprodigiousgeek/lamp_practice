<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';

//セッション開始
session_start();

//ログイン中ならホームページへリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}

//トークン生成
$token = get_csrf_token();

//エラーがあってもlogin_view.phpは表示する
include_once VIEW_PATH . 'login_view.php';