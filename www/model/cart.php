<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

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
      carts.user_id = ?
  ";
  return fetch_all_query($db, $sql, [$user_id]);
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
      carts.user_id = ?
    AND
      items.item_id = ?
  ";

  return fetch_query($db, $sql,[$user_id,$item_id]);

}

function get_purchased_history($db, $user_id = null){
  $params = [];
  $sql = "
  SELECT
    history.purchased_history_id,
    history.created,
    SUM(details.price * details.amount) as totalprice
  FROM
    history
  JOIN
    details
  ON
    history.purchased_history_id = details.purchased_history_id";
    if($user_id !== null){
      $sql.=" WHERE
      history.user_id = ?";
      $params[] = $user_id;
    } 
  $sql.="
  GROUP BY
    history.purchased_history_id
  ORDER BY 
    history.purchased_history_id DESC
  ";
  return fetch_all_query($db, $sql, $params);
}

function get_purchased_allhistory($db){
  $sql = "
  SELECT
    history.purchased_history_id,
    history.created,
    SUM(details.price * details.amount) as totalprice
  FROM
    history
  JOIN
    details
  ON
    history.purchased_history_id = details.purchased_history_id
  GROUP BY
    history.purchased_history_id
  ORDER BY 
    history.purchased_history_id DESC
  ";
  return fetch_all_query($db, $sql);
}

function get_details_list($db, $details_id, $user_id = null){
  $params = [$details_id];
  $sql = "
  SELECT
    details.purchased_history_id,
    details.amount,
    details.price,
    items.name
  FROM
    details
  JOIN
    items
  ON
    details.item_id = items.item_id
  WHERE
    details.purchased_history_id = ?";
  if($user_id !== null) {
    $sql.=" AND 
    EXISTS(SELECT * FROM history WHERE purchased_history_id = ? AND user_id = ?)
    ";
    $params[] = $details_id;
    $params[] = $user_id;
  }
  return fetch_all_query($db, $sql, $params);
}

function get_history_list($db, $details_id, $user_id = null){
  $params = [$details_id];
  $sql = "
  SELECT
    history.purchased_history_id,
    history.created,
    SUM(details.price * details.amount) as totalprice
  FROM
    history
  JOIN
    details
  ON
    history.purchased_history_id = details.purchased_history_id
  WHERE
    history.purchased_history_id = ?";
  if($user_id !== null) {
    $sql.= " AND history.user_id = ?";
    $params[] = $user_id;
  }
  $sql.= " 
  GROUP BY
    history.purchased_history_id
  ";
  return fetch_all_query($db, $sql, $params);
}


function add_cart($db, $user_id, $item_id ) {
  //カートの情報を取得する
  $cart = get_user_cart($db, $user_id, $item_id);
  //もし取得に失敗したら
  if($cart === false){
    //cartに書き込み処理を行う
    return insert_cart($db, $user_id, $item_id);
  }
  //カートへの書き込み処理を行わなけえればカートの数量を更新する
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
    VALUES(?, ?, ?)
  ";

  return execute_query($db, $sql,[$item_id,$user_id,$amount]);
}

function insert_history($db, $user_id){
  $sql = "
    INSERT INTO
      history(
        user_id,
        created
      )
    VALUES(?, now())
  ";

  return execute_query($db, $sql,[$user_id]);
}

function insert_details($db, $history_id, $item_id, $amount,$price){
  $sql = "
    INSERT INTO
      details(
        purchased_history_id,
        item_id,
        amount,
        price
      )
    VALUES(?, ?, ?, ?)
  ";

  return execute_query($db, $sql,[$history_id,$item_id,$amount,$price] );
}

function bulk_regist($db,$carts){
  $history = insert_history($db, $carts[0]['user_id']);
  if($history === false){
    return false;
  }
  $history_id = $db->lastInsertId();
  foreach($carts as $cart){
    $details = insert_details($db, $history_id, $cart['item_id'], $cart['amount'] , $cart['price']);
    if($details === false){
        set_error($cart['name'] . 'の書き込みに失敗しました。');
        return false;
      }
    }
    return true;
  }

function update_cart_amount($db, $cart_id, $amount){
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

function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = ?
    LIMIT 1
  ";

  return execute_query($db, $sql, [$cart_id]);
}

function purchase_carts($db, $carts){
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  $db->beginTransaction();
  foreach($carts as $cart){
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      set_error($cart['name'] . 'の購入に失敗しました。');
    }
  }
  bulk_regist($db,$carts);
  delete_user_carts($db, $carts[0]['user_id']);
  //if文を書く has_errorを入れて条件分岐
  if(has_error() === true){
    $db->rollback();
  } else {
    $db->commit();
  }
}

function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = ?
  ";

  execute_query($db, $sql, [$user_id]);
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

