<?php 
/*ここに書いた関数を使うファイルを読み込み（既に読み込まれている場合は読み込まない）
MODEL_PATH
*/
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

/*データベース接続情報、ユーザーID情報を入れて　データベース接続情報とSQL()情報が返ってくる
SELECTに書いてある内容をcarts.itemsテーブルから選択
cartsテーブルとitemsテーブルを結合し、cartsとitemsのidをイコール
$user_idをcarts.user_idに代入して絞り込み
return　で検索結果とデータベース接続情報を返す
*/
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

//データベース接続情報、ユーザーID、item_IDを入力してデータベース接続情報とテーブル名carts、SELECTの中身を取り出す。結合条件はON 抽出条件はwhereとAND
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
//データベース接続情報、ユーザーID、アイテムIDを入れて、$cartの情報がfalseならデータベース接続情報とuser_idとitem_idが返ってくる
//$cartがfalseでなければデータベース接続情報、$cartに入っているcart_id、amountを+1
function add_cart($db, $user_id, $item_id ) {
  $cart = get_user_cart($db, $user_id, $item_id);
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}
//データベース接続情報、user_id、item_id、初期値としてamountに1を入れる
//商品を新規追加
//データベース接続情報とcartsテーブルに追加した情報$SQLが戻ってくる
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

  return execute_query($db, $sql);
}

/*
商品テーブル(carts)の入力されたcart_IDのデータのamountを更新する
更新した結果をreturnする
*/
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

/*
入力されたcart_idをcartsテーブルから削除する
*/
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

/*
カートに商品が入っていない、ステータスが非公開である、在庫数が足りない、何かしらのエラーメッセージが__errorにある
どれかに当てはまればfalseを返す
＄cartsを$cartに展開する
購入処理を行う
抽出条件　item_id　　在庫数stock　- 購入数amountをひく
処理できなかったらfalse エラーメッセージをset_errorに記録
購入完了したらカートテーブルからuser_idの情報を削除する
$cartsの中身は
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
  /*
  [
   ["item_id" =>33,"name" => "猫","stock" => 5,"amount" => 2, "user_id" => 1],
    ["item_id" =>32,"name" => "ハリネズミ","stock" => 10,"amount" => 2, "user_id" => 1],
    ["item_id" =>34,"name" => "犬","stock" => 10,"amount" => 1, "user_id" => 1],
    ["item_id" =>35,"name" => "うさぎ","stock" => 10,"amount" => 2, "user_id" => 1],
    
  ]
  */
  delete_user_carts($db, $carts[0]['user_id']);
}

/*
抽出条件$user_idでcartsテーブルを選択
抽出されたらその値をテーブルから削除
SQLを実行
*/
function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = {$user_id}
  ";

  execute_query($db, $sql);
}

/*
carts配列の中身をcartに展開
price ✖ amount　をトータルpriceに足していく
全てを足した合計が$total_priceに代入されてreturnで返す　
*/
function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}

/*
$cartsに何も入っていなかったらset_error配列に文字をセットし、falseを返す
is_openでステータスが1ならtrue 
ストックがカートに入れている数より少ない場合は'在庫数がたりないとエラーメッセージ
エラーを持っているが===trueならfalseを返す
*/
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