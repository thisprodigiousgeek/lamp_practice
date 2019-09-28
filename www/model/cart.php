<?php 
// 関数ファイルとDBファイルの読み込み
require_once 'functions.php';
require_once 'db.php';

// ログインユーザーのカートテーブルに含まれる商品一覧情報を取得
// (carsテーブルとitemsテーブルをitem_idで結合)
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
  // クエリ実行結果を返す
  return fetch_all_query($db, $sql);
}

// ログインユーザーのカートテーブルに含まれる商品単体情報を取得
// (carsテーブルとitemsテーブルをitem_idで結合)
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

  // クエリ実行結果を返す
  return fetch_query($db, $sql);

}

// カート追加処理
function add_cart($db, $item_id, $user_id) {
  // ユーザーがカートに入れている、指定した商品の情報を取得
  $cart = get_user_cart($db, $item_id, $user_id);
  if($cart === false){
    // カートに同一商品が存在していなければ、cartsテーブルに商品をinsertする
    return insert_cart($db, $user_id, $item_id);
  }
  // すでにカートに同一商品が存在していれば、カートに入っている商品の個数(amount)を1つ足す
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

// cartsテーブルに商品を1つ追加する
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

  // クエリ実行結果を返す
  return execute_query($db, $sql);
}

// cart_idで指定した商品の個数を変更
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
  // クエリの実行結果を返す
  return execute_query($db, $sql);
}

// cart_idで指定した商品を削除する
function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = {$cart_id}
    LIMIT 1
  ";

  // クエリの実行結果を返す
  return execute_query($db, $sql);
}

// 商品購入処理(stockの減算とcartsテーブル内商品の削除)
function purchase_carts($db, $carts){
  // カートない商品のエラーチェック(エラーがあればfalseが返ってくる)
  if(validate_cart_purchase($carts) === false){
    return false;
  }

  foreach($carts as $cart){
    // 商品の在庫数減算処理
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      set_error($cart['name'] . 'の購入に失敗しました。');
    }
  }
  // ログインユーザーのcartsテーブル内の商品を全て削除
  delete_user_carts($db, $carts[0]['user_id']);
}

// ログインユーザーのcartsテーブル内の商品を全て削除
function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = {$user_id}
  ";

  // クエリの実行
  execute_query($db, $sql);
}

// cart内商品の合計金額算出
function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}


function validate_cart_purchase($carts){
  // $carts(カートに入っている商品)が存在しない場合,false
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }

  // カートに入っている商品の、statusとstockを確認して、非公開or在庫数が足りない場合は
  // エラーメッセージをセッションにセットして、falseを返す。エラーがなければtrueを返す
  foreach($carts as $cart){
    // 商品が非公開商品であった場合、エラーメッセージをセッションにセットする
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }

    // 商品の在庫数から商品の個数を引いたら負の数になる場合は、エラーメッセージを
    // セッションにセットする
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }

  // セッション内にエラーが存在すればfalseを返す
  if(has_error() === true){
    return false;
  }
  // エラーがなければtrueを返す
  return true;
}

