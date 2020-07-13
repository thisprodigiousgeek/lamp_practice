<?php 
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
//下の二つで同じ変数を別々に定義しているとエラーになるので注意。
require_once MODEL_PATH . 'order.php';
require_once MODEL_PATH . 'statement.php';




session_start();

//ログインしていなければ (=ユーザーIDがセッションに保存されていなければ)、ログイン画面へ
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//DBへの接続を行う関数を$dbに代入
$db = get_db_connect();
//ログインしているユーザーの情報を取得する関数を$userに代入
$user = get_login_user($db);

//一般ユーザー(type=2)か、管理者ユーザー(type=1)かで$ordersに入れる値を変更
if ($user['type'] === 2){
  //user_idごとに、購入履歴情報を取得する
  $orders = get_orders($db,$user['user_id']);
} else {
  //adminでログイン中は、全てのユーザーの購入履歴を取得する
  $orders = get_admin_orders($db);
}


include_once VIEW_PATH . '/order_view.php';
?>