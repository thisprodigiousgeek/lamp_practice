<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

//ログインしていなければ、ログイン画面へ
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//DB接続を行う関数を変数に代入
$db = get_db_connect();
//ログインユーザーの情報を取得する関数を変数に代入
$user = get_login_user($db);

//並べ替えボタンが押された場合 
if(isset($_GET['sort']) === TRUE) {
    $sort = get_get('sort_products');

    //★★もっと効率の良い書き方はないか？
    if($sort === "1") {
      $items = get_created_desc_items($db);
    } else if($sort === "2") {
      $items = get_price_asc_items($db);
    } else if($sort === "3") {
      $items = get_price_desc_items($db);
    }

//並べ替えボタンが押されなかった場合
} else {
  $items = get_open_items($db);
}

include_once VIEW_PATH . 'index_view.php';