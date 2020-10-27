<?php 
//それぞれphpファイルの読み込み
//汎用関数ファイルの読み込み
require_once MODEL_PATH . 'functions.php';
//データベースの関数ファイルの読み込み
require_once MODEL_PATH . 'db.php';
//ユーザーごとのカート内商品取得SQL文
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
      carts.user_id = :user_id
  ";
  $array=array(':user_id'=>$user_id);
  return fetch_all_query($db, $sql, $array);
}

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
      carts.user_id = :user_id
    AND
      items.item_id = :item_id
  ";

  $array=array(':user_id'=>$user_id, ':item_id'=>$item_id);
  return fetch_query($db, $sql, $array);

}

//カート内に同じ商品があれば+1、なければ追加
function add_cart($db, $user_id, $item_id ) {
  //関数でuser_idとitem_idから同じユーザーで同じ商品があるか確認
  $cart = get_user_cart($db, $user_id, $item_id);
  if($cart === false){
    //なければinsert_cartで新しく追加する
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

function insert_cart($db, $user_id, $item_id, $amount = 1){
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES(:item_id, :user_id, :amount)
  ";
  
  $array=array(':item_id'=>$item_id, ':user_id'=>$user_id, ':amount'=>$amount);
  return execute_query($db, $sql, $array);
}

function update_cart_amount($db, $cart_id, $amount){
  $sql = "
    UPDATE
      carts
    SET
      amount = :amount
    WHERE
      cart_id = :cart_id
    LIMIT 1
  ";

  $array=array(':amount'=>$amount, ':cart_id'=>$cart_id);
  return execute_query($db, $sql, $array);
}

function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = :cart_id
    LIMIT 1
  ";

  $array=array(':cart_id'=>$cart_id);
  return execute_query($db, $sql, $array);
}



function purchase_carts($db, $carts){
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  foreach($carts as $cart){
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      set_error($cart['name'] . 'の購入に失敗しました。');
    }
  }
  
  delete_user_carts($db, $carts[0]['user_id']);
}

function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = :user_id
  ";

  $array=array(':user_id'=>$user_id);
  execute_query($db, $sql, $array);
}


function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}

function validate_cart_purchase($carts){
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach($carts as $cart){
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  if(has_error() === true){
    return false;
  }
  return true;
}

