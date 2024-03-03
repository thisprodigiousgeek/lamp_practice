<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

//セッション開始
session_start();

//ログイン中でなければログインページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//データベース接続
$db = get_db_connect();

//ログイン中のユーザーのユーザーidを取得
$user = get_login_user($db);

$sort = get_get('sort');

//商品の並び替え指定があった場合
if($sort === ''){

  $sort = 'new';

}

//並び替え
$items = get_sort_items($db,$sort);

//トークンの生成
$token = get_csrf_token();

//エラーが起きてもindex_view.phpは表示する
include_once VIEW_PATH . 'index_view.php';