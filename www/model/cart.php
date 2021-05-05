<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';
//引数として$db、$user_idを渡すとDBからユーザーのカート情報を配列として返す関数
function get_user_carts($db, $user_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = {$user_id}
  ";
  return fetch_all_query($db, $sql);
}
//引数として$db、$user_id,$item_idを渡すとDBからユーザーのカートからitem_idが一致する情報を配列で返す関数
function get_user_cart($db, $user_id, $item_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = {$user_id}
    AND
      items.item_id = {$item_id}
  ";

  return fetch_query($db, $sql);

}
//引数として$db、$user_id,$item_idを渡すとカートに商品を追加する関数
function add_cart($db, $user_id, $item_id ) {
  $cart = get_user_cart($db, $user_id, $item_id);//ユーザーのカート情報を取得
//カートに商品が入っていない場合は商品をインサートする
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  //すでに商品が入っている場合は現在の数量に１つ足して更新する
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}
// 引数として$db、$user_id,$item_idを渡すとカートテーブルに商品情報をinsertする関数
function insert_cart($db, $user_id, $item_id, $amount = 1){
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES({$item_id}, {$user_id}, {$amount})
  ";

  return execute_query($db, $sql);
}
// 引数として$db、$cart_id、$amountをを渡すとカート内の商品の数量を更新する関数
function update_cart_amount($db, $cart_id, $amount){
  $sql = "
    UPDATE
      carts
    SET
      amount = {$amount}
    WHERE
      cart_id = {$cart_id}
    LIMIT 1
  ";
  return execute_query($db, $sql);
}
//引数として$db、$cart_idを渡すとカートの情報を削除する
function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = {$cart_id}
    LIMIT 1
  ";

  return execute_query($db, $sql);
}
//引数として$db、カート情報を渡すと商品の購入処理をする関数
function purchase_carts($db, $carts){
// カートの中身を確認する関数の返り値がfalseであればfalseを返す
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  // 取得したカート情報の配列を展開
  foreach($carts as $cart){
    // 関数update_item_stockの結果がfalseであればエラーメッセージ
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      set_error($cart['name'] . 'の購入に失敗しました。');
    }
  }
  //ユーザーのカートから商品を削除
  delete_user_carts($db, $carts[0]['user_id']);
}
//引数として$db,$user_idを渡すとDBのcartテーブルからuser_idが合致した商品を削除する
function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = {$user_id}
  ";

  execute_query($db, $sql);
}

//カート内の商品の価格を計算して合計金額を返す関数
function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
  //価格と数量をかけて$total_priceと足す
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}
//カートの中身を確認する関数問題なければtrueを返す
function validate_cart_purchase($carts){
  // カートに中身がなかった場合エラーメッセージfalseを返す
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach($carts as $cart){
    // カート内の商品のステータスが公開ではなかったらエラーメッセージ
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    //在庫の数量と購入数量を比べて購入数量が多かった場合エラーメッセージ
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  //エラーメッセージがセットされている場合falseを返す
  if(has_error() === true){
    return false;
  }
  return true;
}

