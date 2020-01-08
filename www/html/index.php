<?php
require_once '../conf/const.php';
require_once '../model/functions.php';
require_once '../model/user.php';
require_once '../model/item.php';
//セッション開始
session_start();
//$_SESSION['user_id']が存在しなければ、ログイン画面へリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
//データベースへ接続、ログインしているユーザーの情報を$userへ格納
$db = get_db_connect();
$user = get_login_user($db);
//公開されている商品情報のみを取得
$items = get_open_items($db);
//リダイレクト
include_once '../view/index_view.php';