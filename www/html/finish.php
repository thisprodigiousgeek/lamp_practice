<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'purchase.php';

//セッション開始
session_start();

//postで送られたトークンの取得
$token = get_post('token');

//セッションに保存されている'csrf_token'とpostから受け取った$tokenの値が一致しているか確認
if(is_valid_csrf_token($token) === false){

  //一致していなければログインページへリダイレクトし、ログインを要求する
  redirect_to(LOGIN_URL);

}

//一致していればtokenを削除する
unset($SESSION['csrf_token']);

//ログイン中ではなければログインページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//データベース接続
$db = get_db_connect();

//ログイン中のユーザーのユーザーidを取得
$user = get_login_user($db);

//上記のユーザーidと一致するカートデータをcartsから取得
$carts = get_user_carts($db, $user['user_id']);

//商品購入の可不可
if(purchase_carts($db, $carts) === false){

  //商品が購入できなかった場合
  set_error('商品が購入できませんでした。');

  //カートページへリダイレクト
  redirect_to(CART_URL);
} 

//cartsから取得した商品の合計金額を$total_priceに代入
$total_price = sum_carts($carts);

//トランザクション開始
$db->beginTransaction();



//$user = get_login_user($db)で取得した配列の中からキー['user_id']のみを取得
$user_id = $user['user_id'];

//purchase_historyに$user['user_id']を追加する 失敗した場合はエラーメッセージを保存してrollbackしてカートページへリダイレクトする。
insert_purchase_history($db,$user_id);

//lastInsertidを実行してorder_idを取得する
$order_id = $db->lastInsertid();

//$cartsの配列に入っているキー['item_id']と['amount']の値のみを取得
$item_id = $carts[0]['item_id'];
$amount = $carts[0]['amount'];

//purchase_detailに$cartsとorder_idを追加する 失敗した場合はエラーメッセージを保存してrollbackしてカートページへリダイレクトする。
insert_purchase_detail($db,$order_id,$item_id,$amount);

//トランザクション可不可判定
if(has_error() === false){

  $db->commit();

}else{

  $db->rollback();

}


//トークンの生成
$token = get_csrf_token();

//エラーが起きでもfinish_view.phpは表示する
include_once '../view/finish_view.php';