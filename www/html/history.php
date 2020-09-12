<!-- <?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'history.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();

$history = get_history($db, $cart['user_id']);

$carts = get_user_carts($db, $user['user_id']);

$total_price = sum_carts($carts);

include_once VIEW_PATH . 'history_view.php';
?> -->