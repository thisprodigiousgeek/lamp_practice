<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

//セッション開始
session_start();

//ログイン中では無い場合ログイン画面へリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//データベース接続
$db = get_db_connect();

//ログインしたユーザーのユーザーidを取得
$user = get_login_user($db);

//ログインページへリダイレクト 条件式?
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

//削除対象の商品のitem_idを$item_idに取得
$item_id = get_post('item_id');


//商品の削除の可不可
if(destroy_item($db, $item_id) === true){

  //削除できた場合
  set_message('商品を削除しました。');

  //削除できなかった場合
} else {
  set_error('商品削除に失敗しました。');
}


//?
redirect_to(ADMIN_URL);