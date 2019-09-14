<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';

// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';

// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';

// itemデータに関する関数ファイルを読み込み。
require_once MODEL_PATH . 'item.php';

// cartデータに関する関数ファイルを読み込み。
require_once MODEL_PATH . 'cart.php';


//セッションをスタートする
session_start();

// ログインチェック用関数を利用
if(is_logined() === false){

// ログインしていない場合はログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

// PDOを取得
$db = get_db_connect();

// PDOを利用してログインユーザーのデータを取得
$user = get_login_user($db);
//cart情報を取得
$carts =get_user_carts($db,$user['user_id']);
//purchase_carts関数で商品購入が失敗した時の処理をするを行う
if(purchase_carts($db, $carts) === false){
//商品購入が失敗した場合下記のメッセージを表示
  h(set_error('商品が購入できませんでした。'));
//CART_URLに誘導
  redirect_to(CART_URL);
} 

//合計金額を$total_priceに代入
 
$total_price = sum_carts($carts);
$db->beginTransaction();
try{

  buy_header($db,$user['user_id'],$total_price);
  $buy_id=$db->lastInsertId('buy_id');
  $row_no=1;
  foreach($carts as $cart){
    buy_details($db,$buy_id,$row_no,$cart['item_id'],$cart['price'],$cart['amount']);
    
    $row_no++;
    
  }
  $db->commit();
}catch(PDOException $e){
$db->rollback();
h(set_error('購入明細の追加に失敗しました'));

}

// ビューの読み込み。
include_once '../view/finish_view.php';