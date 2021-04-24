<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';
/**
 * クエリを実行し、ユーザーのカート情報を取得
 * @param obj $db dbハンドル
 * @param str $user_id ユーザid
 * @return array|bool カート情報|false
 */
function get_user_carts($db, $user_id){
  // .(ドット)は、itemsテーブルのitem_idカラムという意味
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
  return fetch_all_query($db, $sql, array($user_id));
}
/**
 * クエリを実行し、指定のカート情報を取得
 * @param obj $db dbハンドル
 * @param str $user_id ユーザid
 * @param str $item_id 商品id
 * @return array|bool カート情報|false
 */
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

  return fetch_query($db, $sql, array($user_id,$item_id));

}
/**
 * カートに商品を追加する|カートの商品数を1個増やす
 * @param obj $db dbハンドル
 * @param str $user_id
 * @param str $item_id
 * return bool
 */
function add_cart($db, $user_id, $item_id ) {
  $cart = get_user_cart($db, $user_id, $item_id);
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}
/**
 * クエリを実行し、cartsテーブルに商品を追加
 * @param obj $db dbハンドル
 * @param str $user_id
 * @param str $item_id
 * return bool
 */
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

  return execute_query($db, $sql, array($item_id,$user_id,$amount));
}
/**
 * クエリを実行し、カートの在庫数を更新
 * @param obj $db dbハンドル
 * @param str $cart_id カートid
 * @param int $amount 在庫数
 * return bool
 */
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
  return execute_query($db, $sql, array($amount,$cart_id));
}
/**
 * クエリを実行し、カートから商品を削除
 * @param obj $db dbハンドル
 * @param str $cart_id カートid
 * return bool
 */
function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = ?
    LIMIT 1
  ";

  return execute_query($db, $sql, array($cart_id));
}
/**
 * 商品購入処理時に、バリデーション処理を呼び出す
 * @param obj $db dbハンドル
 * @param array $carts カート情報(2次元配列
 * return bool
 */
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
  // 購入履歴に追加する
  if (regist_order_transaction($db,$carts) === false) {
      set_error('購入商品の登録に失敗しました。');
      return false;
  }
  
  // カート商品の削除処理
  delete_user_carts($db, $carts[0]['user_id']);
}
/**
 * クエリを実行し、userのカートから商品を削除
 * @param obj $db dbハンドル
 * @param str $user_id ユーザid
 * return bool
 */
function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = ?
  ";

  execute_query($db, $sql, array($user_id));
}

/**
 * カート情報から、合計金額を計算
 * @param array $carts カート情報(2次元配列)
 * @return int $total_price 合計金額
 */
function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}
/**
 * カート商品購入時のバリデーション処理(カート商品数、ステータス、在庫数)
 * @param array $carts カート情報(2次元配列)
 * @return bool
 */
function validate_cart_purchase($carts){
  // カートに商品が追加されているかチェック
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach($carts as $cart){
    // 商品が公開されているかチェック
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    // 商品在庫が足りているかチェック
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  if(has_error() === true){
    return false;
  }
  return true;
}
/**
 * クエリを実行し、購入履歴・購入明細テーブルに追加処理
 * @param obj $db dbハンドル
 * @param int $user_id ユーザーID
 * @param int $item_id 商品ID
 * @param int $price 購入価格
 * @param int $amount 購入数
 * return bool
 */
function regist_order_transaction($db,$carts) {
  // トランザクション開始
  $db->beginTransaction();
  if( ($order_id = insert_order($db,$carts[0]['user_id'])) && insert_details($db,$order_id,$carts) !== false){
    // コミット処理
    $db->commit();
    return true;
  } else {
    // ロールバック処理
    $db->rollback();
    return false;
  }
}
/**
 * クエリを実行し、購入履歴テーブルに追加
 * @param obj $db dbハンドル
 * @param int $user_id ユーザーID
 * @return int|bool $last_id|false
 */
function insert_order($db,$user_id) {
  $sql = "
  INSERT INTO
    orders(
      user_id
    )
  VALUES(?)
";

return execute_query_lastInsertId($db, $sql, array($user_id));
}
/**
 * 購入明細テーブルに追加
 * @param obj $db dbハンドル
 * @param int $order_id 注文番号
 * @param int $carts カート情報（2次元配列）
 * @return bool
 */
function insert_details($db,$order_id,$carts) {
  foreach($carts as $cart) {
    if(insert_details_query(
      $db,
      $order_id,
      $cart['item_id'],
      $cart['price'],
      $cart['amount']
    ) === false) {
      return false;
    }
  }
  return true;
}

/**
 * クエリを実行し、購入明細テーブルに追加
 * @param obj $db dbハンドル
 * @param int $order_id 注文番号
 * @param int $item_id 商品ID
 * @param int $price 価格
 * @param int $amount 購入数
 * @return bool
 */
function insert_details_query($db,$order_id,$item_id,$price,$amount) {
  $sql = "
  INSERT INTO
    details(
      order_id,
      item_id,
      price,
      amount
    )
  VALUES(?,?,?,?)
";

return execute_query($db, $sql, array($order_id,$item_id,$price,$amount));
}