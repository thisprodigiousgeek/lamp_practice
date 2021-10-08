<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

//ユーザー毎にそれぞれのカートデータを取得する
function get_user_carts($db, $user_id){

  //sql文作成
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

  //fetch_all_queryにsql文を返して実行
  return fetch_all_query($db, $sql,array(':user_id' => $user_id));
}

//ユーザー毎に且つitem_idが一致するレコードのみ取得する(削除、購入個数、在庫数の変更時など?)
function get_user_cart($db, $user_id, $item_id){

  //sql文の作成
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
  //fetch_queryにsql文を返して実行
  return fetch_query($db, $sql,array(':user_id' => $user_id,':item_id' => $item_id));

}

//商品カート追加処理
function add_cart($db, $user_id, $item_id ) {

  $cart = get_user_cart($db, $user_id, $item_id);

  //get_user_cart→fetch_queryの戻り値がfalseの場合(初めてカートに入れる商品の場合)
  if($cart === false){

    //カートに入れる商品のデータをインサートする
    return insert_cart($db, $user_id, $item_id);
  }

  //既に商品がカートに入っている場合購入予定個数を+1する
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

//初めて商品をカートに入れる場合の処理
function insert_cart($db, $user_id, $item_id, $amount = 1){

  //sql作成
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES(:item_id, :user_id, :amount)
  ";

  //sql文をexecute_queryに返して実行
  return execute_query($db, $sql,array(':item_id'=>$item_id,':user_id'=>$user_id,':amount'=>$amount));
}

//既に商品がカートに入っている場合の処理
function update_cart_amount($db, $cart_id, $amount){

  //sql作成
  $sql = "
    UPDATE
      carts
    SET
      amount = :amount
    WHERE
      cart_id = :cart_id
    LIMIT 1
  ";

  //execute_queryにsql文を返して実行
  return execute_query($db, $sql,array(':amount'=>$amount,':cart_id'=>$cart_id));
}

//カートから商品を削除する場合の処理
function delete_cart($db, $cart_id){

  //sql文作成
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = :cart_id
    LIMIT 1
  ";

  //execute_queryにsql文を返して実行
  return execute_query($db, $sql,array(':cart_id'=>$cart_id));
}

//カートの中にある商品を購入する場合の処理
function purchase_carts($db, $carts){

  //カートに商品が入っていない場合
  if(validate_cart_purchase($carts) === false){
    return false;
  }

  //配列「carts」がどこからきてるのかわからない
  foreach($carts as $cart){
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      set_error($cart['name'] . 'の購入に失敗しました。');
    }
  }
  
  //購入できた場合カートデータを削除する
  delete_user_carts($db, $carts[0]['user_id']);
}

//カートデータを削除する場合の処理
function delete_user_carts($db, $user_id){

  //sql文作成
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = :user_id
  ";

  //execute_queryにsql文を返して実行する
  execute_query($db, $sql,array('user_id'=>$user_id));
}


//カートの中の商品の合計金額を計算する
function sum_carts($carts){

  //合計金額初期値
  $total_price = 0;

  //$cartsがどこからきているのかわからない
  foreach($carts as $cart){

    //foreachでカートの中に入っている商品の文だけ繰り返し$total_priceに足していく
    $total_price += $cart['price'] * $cart['amount'];
  }

  //$total_priceを返す
  return $total_price;
}

//購入ができるかどうかの確認
function validate_cart_purchase($carts){

  //カートの中に何も入っていない場合
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }

  //購入時に商品が非公開になった場合?
  foreach($carts as $cart){
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }

    //購入個数が在庫数を超えた場合
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }

  //わからない
  if(has_error() === true){
    return false;
  }
  return true;
}

