<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';

//セッション開始
session_start();

//ログイン中のユーザーであればホームページへリダイレクトする
if(is_logined() === true){
  redirect_to(HOME_URL);
}

//エラーがあった場合でもsingup_view.phpの画面を表示する
include_once VIEW_PATH . 'signup_view.php';



