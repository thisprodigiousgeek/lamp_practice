<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();

$user = get_login_user($db);

if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
is_valid_csrf_token($token);

$item_id = get_post('item_id');
$changes_to = get_post('changes_to');
// $post_token = get_post('token');

if($_POST['token'] !== $_SESSION['token']){
  set_error('不正な処理が行われました');//セッション箱のエラーのとこに入れる
  $_SESSION = array();//セッション箱空にする
  redirect_to(LOGIN_URL);//ログインページに戻らせる
} else {

  if($changes_to === 'open'){
    update_item_status($db, $item_id, ITEM_STATUS_OPEN);
    set_message('ステータスを変更しました。');
  }else if($changes_to === 'close'){
    update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
    set_message('ステータスを変更しました。');
  }else {
    set_error('不正なリクエストです。');
  }
  
  $_SESSION['token'] = '';//トークンの破棄
  get_csrf_token();//トークンまた新しく作る
}


redirect_to(ADMIN_URL);