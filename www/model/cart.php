<?php 
require_once 'functions.php';
require_once 'db.php';

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

/*カートに商品が入っていればupdate_cart_amountを実行
カートにっ商品が入っていなければinsert_cartを実行*/

function add_cart($db, $item_id, $user_id) {
  $cart = get_user_cart($db, $item_id, $user_id);
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

function insert_cart($db, $item_id, $user_id, $amount = 1){
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
function inser_cart($db, $item_id, $user_id, $amount){
  $sql = "
    INSERT INTO
      cart(
        item_id,
        user_id,
        amount
      )
    VALUES({$item_id}, {$user_id}, {$amount})
  ";

  return execute_query($db, $sql);
}

//カートに商品が入っていればupdate_cart_amountで購入数を変更
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
//ユーザーが消去ボタンを押せばdelete_cartを実行し商品をキャンセルする
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
      user_id = {$user_id}
  ";

  execute_query($db, $sql);
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

function buy_all($db,$item_id,$user_id,$price,$amount,$total_price){
  $row_no='';
  $db->beginTransaction();
  try{
      $sql="
    INSERT INTO
    buy_header(
    user_id,
    date
    )
    VALUES(?,now())
    ";
    $db->prepare($sql);
    $db->bindValue(1,$user_id,PDO::PARAM_INT);
    $db->execute();
    $buy_id=$db->lastInsertId();
    $sql="
    INSERT INTO
    buy_details(
    buy_id,
    row_no,
    item_id,
    amount,
    price,
    total
    )
    VALUES(?,?,?,?,?,?)
    ";
    $db->prepare($sql);
    $db->bindValue(1,$buy_id,PDO::PARAM_INT);
    $db->bindValue(2,$row_no,PDO::PARAM_INT);
    $db->bindValue(3,$item_id,PDO::PARAM_INT);
    $db->bindValue(4,$amount,PDO::PARAM_INT);
    $db->bindValue(5,$price,PDO::PARAM_INT);
    $db->bindValue(6,$total_price,PDO::PARAM_INT);
    $db->execute();
    $db->commit();
    return true;
  }catch(PDOException $e){
    $db->rollback();
    return false;
  }
 } 