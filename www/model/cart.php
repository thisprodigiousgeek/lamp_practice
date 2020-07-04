<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

//ログインユーザーのカート内商品情報を取得する関数
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
      carts.item_id,
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

  //クエリ実行。失敗でfalse
  return fetch_query($db, $sql, [$user_id, $item_id]); 
                                //一つ目の？　2つ目の？　に当てはめられるようになる　//3つ目は必ず配列で。渡す値が１つでも。
}                                                                               //1つだけの場合[$user_id]

//カートに入れるボタンを押したときの処理を記述した関数
//カートに商品がある⇒購入予定数を＋１
//カートに商品がない⇒新しくカートに商品情報を登録
function add_cart($db, $user_id, $item_id ) {
  //カート内商品情報を取得するget_user_cart関数を実行し、結果を$cartに代入する。
  $cart = get_user_cart($db, $user_id, $item_id);
  //もしカート内商品情報が取得できなかった場合(falseが返ってきた場合)、insert_cart関数を返す(実行する)
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  //cartsテーブル内の購入予定数を＋１する関数を返す(実行する)
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

//cartsテーブルに、選択した商品の情報を登録する　※user_id,item_idは、送られてくる値。
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
  
  //クエリを実行する
  return execute_query($db, $sql, [$item_id, $user_id, $amount]);
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

  //クエリを実行する
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

  //falseだった場合(在庫がない・在庫が足りない・非公開・その他エラーが存在)、falseを返す。
  if(validate_cart_purchase($carts) === false){ 
    return false;
  }

  //1⃣～4⃣の処理を、トランザクション処理  
  $db->beginTransaction(); 

  // 1⃣ カート内商品の、在庫数更新に失敗した場合、エラーメッセージを返す
  foreach($carts as $cart){
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      set_error($cart['name'] . 'の購入に失敗しました。');
    } 
  }

  // 2⃣ ユーザーIDに応じた、カート内の商品を、カートから削除する
  delete_user_carts($db, $carts[0]['user_id']);

  // 3⃣ 購入履歴への商品情報登録　4⃣ 購入明細への商品情報登録
  //statementsテーブルに1商品しか登録できない。1つなら購入（登録）できるが、商品を複数購入（登録）するとエラーが出る。
  //　→　statementsテーブルの先頭にstatements_idを追加。A_I指定。そのほかの値は全て重複を許可することで成功　7/4
  order_products_statements($db, $carts);

  //エラーがある場合はロールバック(処理を取り消す)
  if(has_error() === true){
    $db->rollback();
  //エラーが無ければコミット(処理を確定)
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
  //在庫数チェック 0ならエラーメッセージを表示し、falseを返す
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach($carts as $cart){
    //公開・非公開チェック　非公開ならエラーメッセージを表示
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    //在庫数<購入数ならエラーメッセージを表示
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  //エラーが存在すると、falseを返す。
  if(has_error() === true){
    return false;
  }
  //上記の条件に引っかからなければtrueを返す。
  return true;
}

