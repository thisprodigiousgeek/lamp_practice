<?php 
//汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
//dbデータに関するファイル読み込み
require_once MODEL_PATH . 'db.php';

//カートに入っているアイテムをitemsとcartsテーブルからユーザーごとに表示
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
  //DBのSQLを実行し全ての結果行レコード取得
  return fetch_all_query($db, $sql);
}

//itemsとcartsテーブルをユーザーとアイテムごとに表示
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
  //DBのSQLを実行し１行のみレコード取得
  return fetch_query($db, $sql);

}

//カートにアイテムを入れて数量を変更する
function add_cart($db, $user_id, $item_id ) {
  //itemsとcartsテーブルをユーザーとアイテムごとに表示
  $cart = get_user_cart($db, $user_id, $item_id);
  //$cartが無かった場合
  if($cart === false){
    //DBのカートごとにテーブルを作成
    return insert_cart($db, $user_id, $item_id);
  }
  //DBのカート内のアイテム数量を変更
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

//DBのカートごとにテーブルを作成
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
  //SQLを実行
  return execute_query($db, $sql);
}

//DBのカート内のアイテム数量を変更
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
  //SQLを実行
  return execute_query($db, $sql);
}

//DBカートテーブルをカートごとに削除
function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = {$cart_id}
    LIMIT 1
  ";
  //SQLを実行
  return execute_query($db, $sql);
}

//カート購入成功したらカートテーブル削除
function purchase_carts($db, $carts){
  //購入する際のカートの中身チェック
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  foreach($carts as $cart){
    //itemsテーブルのstockをアップデート失敗した場合
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      //セッション変数にエラー表示
      set_error($cart['name'] . 'の購入に失敗しました。');
    }
  }
  //DBカートテーブルをユーザーごとに削除
  delete_user_carts($db, $carts[0]['user_id']);
}

//DBカートテーブルをユーザーごとに削除
function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = {$user_id}
  ";
  //SQLを実行
  execute_query($db, $sql);
}

//カートの合計金額計算
function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}

//購入する際のカートの中身チェック
function validate_cart_purchase($carts){
  //カートが０だった場合
  if(count($carts) === 0){
    //セッション変数にエラー表示
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach($carts as $cart){
    //ステータスが０（非公開）の時
    if(is_open($cart) === false){
      //セッション変数にエラー表示
      set_error($cart['name'] . 'は現在購入できません。');
    }
    //在庫数が購入したい数量より少ない場合
    if($cart['stock'] - $cart['amount'] < 0){
      //セッション変数にエラー表示
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  
  //セッション変数に値が入っている場合
  if(has_error() === true){
    return false;
  }
  return true;
}

