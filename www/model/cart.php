<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

function get_user_carts($db, $user_id)
{
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
      carts.user_id = ?
  ";
  return fetch_all_query($db, $sql, [$user_id]);
}

function get_user_cart($db, $user_id, $item_id)
{
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
      carts.user_id = ?
    AND
      items.item_id = ?
  ";

  return fetch_query($db, $sql, [$user_id, $item_id]);
}

function add_cart($db, $user_id, $item_id)
{
  $cart = get_user_cart($db, $user_id, $item_id);
  if ($cart === false) {
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

function insert_cart($db, $user_id, $item_id, $amount = 1)
{
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES(?,?,?)
  ";

  return execute_query($db, $sql, [$item_id, $user_id, $amount]);
}

function update_cart_amount($db, $cart_id, $amount)
{
  $sql = "
    UPDATE
      carts
    SET
      amount = ?
    WHERE
      cart_id = ?
    LIMIT 1
  ";
  return execute_query($db, $sql, [$amount, $cart_id]);
}

function delete_cart($db, $cart_id)
{
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = ?
    LIMIT 1
  ";

  return execute_query($db, $sql, [$cart_id]);
}


function purchase_carts($db, $carts)
{
  if (validate_cart_purchase($carts) === false) {
    return false;
  }
  try {
    $db->beginTransaction();
    foreach ($carts as $cart) {                                           //購入処理
      if (update_item_stock(
        $db,
        $cart['item_id'],
        $cart['stock'] - $cart['amount']
      ) === false) {
        set_error($cart['name'] . 'の購入に失敗しました。');
      }
    }
    create_histories($db, $carts);
    delete_user_carts($db, $carts[0]['user_id']);
  } catch (PDOException $e) {
    set_error('トランザクションに失敗しました。');
  }
  if (has_error() === false) {
    $db->commit();
  } else {
    $db->rollback();
  }
}


function delete_user_carts($db, $user_id)
{
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = ?
  ";

  execute_query($db, $sql, [$user_id]);
}


function sum_carts($carts)
{
  $total_price = 0;
  foreach ($carts as $cart) {
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}

function validate_cart_purchase($carts)
{
  if (count($carts) === 0) {
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach ($carts as $cart) {
    if (is_open($cart) === false) {
      set_error($cart['name'] . 'は現在購入できません。');
    }
    if ($cart['stock'] - $cart['amount'] < 0) {
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  if (has_error() === true) {
    return false;
  }
  return true;
}

function add_history($db, $user_id)
{
  $sql = "
    INSERT INTO  
      history(user_id)
    values(?)
  ";
var_dump($user_id,$sql);
  return execute_query($db, $sql, [$user_id]);
}

function add_detail($db, $history_id,$item_id,$amount,$price)
{
  $sql = "
    INSERT INTO  
      purchase_detail(history_id,item_id,amount,price)
    values(?,?,?,?)
  ";

  return execute_query($db, $sql, [$history_id,$item_id,$amount,$price]);
}

function create_histories($db, $carts)
{
  if (add_history($db, $carts[0]['user_id']) === false) {
    set_error('購入履歴作成に失敗。');
    return false;
  }
  $h_id = $db->lastInsertId();
  foreach ($carts as $cart) {
    if (add_detail($db, $h_id,$cart['item_id'],$cart['amount'],$cart['price']) === false) {
      set_error($cart['name'] . 'の購入履歴詳細作成に失敗。');
      return false;
    }
  }
  return true;
}
