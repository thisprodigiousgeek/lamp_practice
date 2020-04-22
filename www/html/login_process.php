<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

session_start();
if (isset($_POST["csrf_token"]) && $_POST["csrf_token"]===$_SESSION['csrf_token']){
  if(is_logined() === true){
    redirect_to(HOME_URL);
  }

  $name = get_post('name');
  $password = get_post('password');

  $db = get_db_connect();


  $user = login_as($db, $name, $password);
  if( $user === false){
    set_error('ログインに失敗しました。');
    redirect_to(LOGIN_URL);
  }

  set_message('ログインしました。');
  if ($user['type'] === USER_TYPE_ADMIN){
    redirect_to(ADMIN_URL);
  }
  redirect_to(HOME_URL);
} else {
  print '不正なアクセスです';
}