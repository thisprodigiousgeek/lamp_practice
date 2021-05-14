<?php 
// MODEL_PATHとはモデルを定義しているディレクトリへの道筋
// ここではモデルのfunctions.phpを読み込む
require_once MODEL_PATH . 'functions.php';
// ここではモデルのdb.PHPを読み込む
require_once MODEL_PATH . 'db.php';

// この関数はテーブルcartsからユーザーが商品を取得する
function get_user_carts($db, $user_id){
// SELECT文でcartsとitemsテーブルから商品を取得
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

    // prepareでSQL文を実行する準備
    $stmt = $db->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
    // SQLを実行
    $stmt->execute();
    // 実行したらfetch_all_queryに返す
    return fetch_all_query($db, $sql);

  return fetch_all_query($db, $sql);
}
// この関数はユーザーのカートにデータを取得する
function get_user_cart($db, $user_id, $item_id){
// SELECT文でcartsとitemsのテーブルからデータを取得する
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

    // prepareでSQL文を実行する準備
    $stmt = $db->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $item_id, PDO::PARAM_INT);
    // SQLを実行
    $stmt->execute();
    // fetch_all_queryに返す
    return fetch_all_query($db, $sql);

  return fetch_query($db, $sql);

}
// この関数はカートに商品を追加する
// get_user_cartを$cartの変数にいれる
function add_cart($db, $user_id, $item_id ) {
  $cart = get_user_cart($db, $user_id, $item_id);
// カートにデータを取得できなければinsert_cartに返す
// update_cart_amountを返す
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}
// この関数はユーザーのカートにデータを登録する
function insert_cart($db, $user_id, $item_id, $amount = 1){
  // INSERT INTOでcartsテーブルを登録する
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES(?, ?, 1)
  ";
    // prepareでSQL文を実行する準備
    $stmt = $db->prepare($sql);
    $stmt->bindValue(1, $item_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $user_id, PDO::PARAM_INT);
    // SQLを実行
    $stmt->execute();
    // fetch_all_queryを返す
    return fetch_all_query($db, $sql);
    // execute_queryに返す
  return execute_query($db, $sql);
}
// この関数はカートに個数を更新する
function update_cart_amount($db, $cart_id, $amount){
// UPDATE文でcartsテーブルのcart_idの個数を更新する
  $sql = '
    UPDATE 
      carts
    SET
      amount = ?
    WHERE
      cart_id = ?
    LIMIT 1';
// prepareでSQL文の実行準備
    $stmt = $db->prepare($sql);
// プレースホルダで値をバインドバリューする
    $stmt->bindValue(1, $amount, PDO::PARAM_INT);
    $stmt->bindValue(2, $cart_id, PDO::PARAM_INT);
// SQL文を実行する
    $stmt->execute();
// execute_queryに返す
  return execute_query($db, $sql);
}
// この関数は カートの中身を削除する
function delete_cart($db, $cart_id){
// DELETE文でcartsテーブルの指定したcart_idを削除する
  $sql = '
          DELETE 
          FROM
              carts
          WHERE
            cart_id = ?
          LIMIT 1';
// prepareでSQL文の実行準備
          $stmt = $db->prepare($sql);
// プレースホルダで値をバインドバリュー
          $stmt->bindValue(1, $cart_id, PDO::PARAM_INT);
// SQL文を実行する
          $stmt->execute();
// execute_queryに返す
  return execute_query($db, $sql);
}
// この関数はカートの中に入っているものを購入する
function purchase_carts($db, $carts){
// カートの中身が何もなければfalseを返す
  if(validate_cart_purchase($carts) === false){
    return false;
  }
// foreachで配列をループ
  foreach($carts as $cart){
// update_item_stockのデータベース、cartのitem_id、
// cartの在庫から個数を引いたものが、falseの場合エラー
// 購入できない場合エラーメッセージを表示する
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      set_error($cart['name'] . 'の購入に失敗しました。');
    }
  }
// 
  delete_user_carts($db, $carts[0]['user_id']);
}
// この関数はユーザーのカートの中身からデータを削除する
function delete_user_carts($db, $user_id){
// DELETE文でcartsテーブルからuser_idを削除
  $sql = '
          DELETE 
          FROM
            carts
          WHERE
            user_id = ?';
// prepareでSQL文を実行準備
          $stmt = $db->prepare($sql);
// プレースホルダで値をバインドバリュー
          $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
// SQL文を実行する
          $stmt->execute();
//
  execute_query($db, $sql);
}

// この関数は合計金額を計算する
function sum_carts($carts){
  $total_price = 0;
// foreachでcarts配列をループする
// 減算式での結果を$total_priceに返す
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}
// この関数はカートに商品が入っていない場合のエラーを表示する
// falseを返す
function validate_cart_purchase($carts){
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
// foreachでcarts配列をループする
  foreach($carts as $cart){
// カートの中身がfalseならエラーメッセージを表示する
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
// 在庫が足りない場合、エラーを表示する
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
// エラーがある場合falseを返す、なければtrueを返す
  if(has_error() === true){
    return false;
  }
  return true;
}