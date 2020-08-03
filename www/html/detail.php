<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'order.php';

session_start();

if(is_logined() === false){
    redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);


if(get_post('order_id') !== ''){
    $order = get_post('order_id');
    set_session($order_id, $order);
    $order_id = get_session($order_id);
}else if(get_post('order_id') === ''){
    if(get_session($order_id) !== ''){
        $order_id = get_session($order_id);
        var_dump($order_id);
    }else{
        redirect_to(ORDER_URL);
    }
}


$detail = select_detail($db, $order_id);

include_once VIEW_PATH . 'detail_view.php';