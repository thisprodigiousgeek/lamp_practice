<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'order.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

$carts = get_user_carts($db, $user['user_id']);

// トランザクション開始
$db->beginTransaction();

try {  
  
  // ①購入後の在庫数の更新
  if(purchase_carts($db, $carts) === false){
    // 失敗の場合、(※)execute_query関数(db.php)で、「PDOException $e」を使用しているので、新しい例外（new Exception）を作りthrow
    throw new Exception('商品が購入できませんでした。');
  } 

  // ②「購入履歴」の登録 & 成功の場合、その登録データのIDを$order_idに代入（購入明細と紐付けの為）
  $order_id = insert_order($db, $user['user_id']); 

  // ②に成功した場合、
  if($order_id !== false){
    // 上記IDを利用して、③「購入明細」の登録。
    if(insert_order_detail($db, $carts, $order_id) === false){
      // l27(※)に同じ
      throw new Exception('購入明細が登録できませんでした。');
    } 
  // ②に失敗した場合、
  }else {
    // l27(※)に同じ
    throw new Exception('購入履歴が登録できませんでした。');
  }

  // コミット処理
  $db->commit();

// いずれか1つでも失敗した場合、
} catch (Exception $e) {
  // ロールバック処理
  $db->rollback();
  // 設定したエラーメッセージを受け取り、セッションに保存
  set_error($e->getMessage());
  redirect_to(CART_URL);
}

$total_price = sum_carts($carts);

include_once '../view/finish_view.php';