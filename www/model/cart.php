<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';
// ユーザID照会：カート情報取得
function get_user_carts($db, $user_id){
  // SQL文作成
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
  // クエリを実行して、成功すればtrue、失敗すればfalseを返す
  return fetch_all_query($db, $sql);
}
// ユーザID・商品ID照会：指定した商品がカートに存在するか確認
function get_user_cart($db, $user_id, $item_id){
  // SQL文作成
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
  // クエリを実行し、成功すればレコード1行（１次元）を返し、失敗すればfalseを返す
  return fetch_query($db, $sql);

}
// カートに商品追加
function add_cart($db, $user_id, $item_id ) {
  // 同じ商品がカートに存在するか確認
  $cart = get_user_cart($db, $user_id, $item_id);
  // 同じ商品がカートに無ければカートに商品を新規追加
  if($cart === false){
    // 返り値：true
    return insert_cart($db, $user_id, $item_id);
  }
  // 同じ商品がカートにあれば数量変更　返り値：true
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}
// カートに商品を新規追加
function insert_cart($db, $user_id, $item_id, $amount = 1){
  // SQL文作成
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES({$item_id}, {$user_id}, {$amount})
  ";
  // クエリを実行し、成功すればtrue、失敗すればfalseを返す
  return execute_query($db, $sql);
}
// カードID照会：購入数変更
function update_cart_amount($db, $cart_id, $amount){
  // SQL文作成
  $sql = "
    UPDATE
      carts
    SET
      amount = {$amount}
    WHERE
      cart_id = {$cart_id}
    LIMIT 1
  ";
  // クエリを実行し、成功すればtrue、失敗すればfalseを返す
  return execute_query($db, $sql);
}
// カートID照会：カート削除（1行のみ）
function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = {$cart_id}
    LIMIT 1
  ";
  // クエリを実行し、成功すればtrue、失敗すればfalseを返す
  return execute_query($db, $sql);
}
// 購入処理
function purchase_carts($db, $carts){
  // 購入情報のバリデーション
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  // カート商品ごとに繰り返し処理
  foreach($carts as $cart){
    // 在庫更新
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      // 異常メッセージ
      set_error($cart['name'] . 'の購入に失敗しました。');
    }
  }
  // ユーザのカート削除
  delete_user_carts($db, $carts[0]['user_id']);
}
// ユーザID照会：ユーザのカート削除
function delete_user_carts($db, $user_id){
  // SQL文作成
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = {$user_id}
  ";
  // クエリを実行し、成功すればtrue、失敗すればfalseを返す
  execute_query($db, $sql);
}

// カート内の合計金額を取得する
function sum_carts($carts){
  $total_price = 0;
  // 金額*数量を繰り返し足す
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}
// 購入情報のバリデーション
function validate_cart_purchase($carts){
  // カートに商品があるか確認
  if(count($carts) === 0){
    // 異常メッセージ
    set_error('カートに商品が入っていません。');
    return false;
  }
  // カートの商品を順番にチェック
  foreach($carts as $cart){
    // 取り扱い中か確認
    if(is_open($cart) === false){
      // 異常メッセージ
      set_error($cart['name'] . 'は現在購入できません。');
    }
    // 在庫が足りているか確認
    if($cart['stock'] - $cart['amount'] < 0){
      // 異常メッセージ
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  // エラー確認
  if(has_error() === true){
    // エラー有：false
    return false;
  }
  // エラー無し：true
  return true;
}

