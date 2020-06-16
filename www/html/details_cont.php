<?php

//各モデルから関数データを取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'details.php';
header('X-Frame-Options: DENY');

session_start();

//トークンチェック
$token=get_post("token");
is_valid_csrf_token($token);
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
//データベースに接続
$db = get_db_connect();
//ユーザー情報を取得(ユーザーIDなど)
$user = get_login_user($db);

if($_SERVER['REQUEST_METHOD']==='POST'){

    if(isset($_POST['order_id']) === TRUE){

    $hidden_order_id = $_POST['order_id'];

    $details_price = select_details_price($db,$hidden_order_id);
    }
   
}
for($i=0 ; $i<count($details_price) ; $i++){
  $details_item_name = details_item_name($db,$details_price[$i]['item_id']);
}

include_once '../view/details_view.php';
?>
