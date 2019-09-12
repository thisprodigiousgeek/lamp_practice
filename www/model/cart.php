<?php
// 定数ファイルを読み込み
require_once 'functions.php';

// dbデータに関する関数ファイルを読み込み
require_once 'db.php';

//ユーザーがカートに入れた商品を表示
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
//ユーザーがカートに入れた商品を表示
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

/*カートに商品が入っていればupdate_cart_amountを実行
カートにっ商品が入っていなければinsert_cartを実行*/

function add_cart($db, $item_id, $user_id) {
  $cart = get_user_cart($db, $item_id, $user_id);
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

//テーブルcartsをインサート
function insert_cart($db, $item_id, $user_id, $amount = 1){
  try{
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES(?,?,?)
  ";
  $stmt=$db->prepare($sql);
  $stmt->bindValue(1,$item_id,PDO::PARAM_INT);
  $stmt->bindValue(2,$user_id,PDO::PARAM_INT);
  $stmt->bindValue(3,$amount,PDO::PARAM_INT);
  $stmt->execute();
  return true;
      }catch(PDOExcepttion $e){
        return false;
        throw $e;
      }

}


//カートに商品が入っていればupdate_cart_amountで購入数を変更
function update_cart_amount($db, $cart_id, $amount){
  try{
  $sql = "
    UPDATE
      carts
    SET
      amount = ?
    WHERE
      cart_id = ?
    LIMIT 1
  ";
  $stmt=$db->prepare($sql);

  $stmt->bindValue(1,$amount,PDO::PARAM_INT);
$stmt->bindValue(2,$cart_id,PDO::PARAM_INT);
$stmt->execute();
return true;
  }catch(PDOException $e){
return false;
throw $e;
  }
  
}
//ユーザーが消去ボタンを押せばdelete_cartを実行し商品をキャンセルする
function delete_cart($db, $cart_id){
  try{
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = ?
    LIMIT 1
  ";
  $stmt=$db->prepare($sql);

  $stmt->bindValue(1,$cart_id,PDO::PARAM_INT);

$stmt->execute();
return true;
  }catch(PDOException $e){
return false ;
throw $e;
  }
}

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
  
  delete_user_carts($db, $carts[0]['user_id']);
}

//カートに入っている商品を消去する
function delete_user_carts($db, $user_id){
  try{
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = ?
  ";
$stmt=$db->prepare($sql);
$stmt->bindValue(1,$user_id,PDO::PARAM_INT);
$stmt->execute();
return true;
  }catch(PDOException $e){
return false;
throw $e;
  }

}

//購入予定の商品の合計を計算
function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}
//buy_detailisにINSERTするpriceを取得
function price_carts($carts){
  $price = 0;
  foreach($carts as $cart){
    $price = $cart['price'];
  }
  return $price;
}
function amount_carts($carts){
  $amount = 0;
  foreach($carts as $cart){
    $amount = $cart['amount'];
  }
  return $amount;
}
function item_carts($carts){
  $item_id = 0;
  foreach($carts as $cart){
    $item_id = $cart['item_id'];
  }
  return $item_id;
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

//購入詳細、購入履歴のテーブルをインサートte-buruwoinnsa-to

function buy_header($db,$user_id,$total_price){
  
  try{
      $sql="
    INSERT INTO
    buy_header(
    user_id,
    date,
    total
    )
    VALUES(?,now(),?)
    ";
    $stmt=$db->prepare($sql);
    $stmt->bindValue(1,$user_id,PDO::PARAM_INT);
    $stmt->bindValue(2,$total_price,PDO::PARAM_INT);
    $stmt->execute();
   
    
 return true;
  }catch(PDOException $e){
    
    return false;
    throw $e;
  }
 } 
 function buy_details($db,$buy_id,$row_no,$item_id,$price,$amount){
   try{
 $sql="
 INSERT INTO
 buy_details(
 buy_id,
 row_no,
 item_id,
 price,
 amount
 
 )
 VALUES(?,?,?,?,?)
 ";
 $stmt=$db->prepare($sql);
 $stmt->bindValue(1,$buy_id,PDO::PARAM_INT);
 $stmt->bindValue(2,$row_no,PDO::PARAM_INT);
 $stmt->bindValue(3,$item_id,PDO::PARAM_INT);
 $stmt->bindValue(4,$price,PDO::PARAM_INT);
 $stmt->bindValue(5,$amount,PDO::PARAM_INT);

 $stmt->execute();
 return true;
   }catch(PDOException $e){
    
    return false;
    throw $e;
  }
 }


 function buy_header_select($db,$user_id){
   $sql="
   SELECT
   buy_id,
   date,
   total
  
   from
   buy_header
   where
   user_id={$user_id}
   ";
   return fetch_all_query($db, $sql);

 }
 function buy_detailis($db,$user_id,$buy_id){
  $sql="
  SELECT
  buy_header.buy_id,
  buy_header.date,
  
  buy_header.total,
  buy_details.price,
  buy_details.item_id,
  buy_details.amount,
  
  items.name
  from
  buy_header
  JOIN 
  buy_details
  ON
  buy_header.buy_id = buy_details.buy_id 
 JOIN
 items
 ON
 buy_details.item_id = items.item_id
  where
  buy_header.user_id={$user_id}
  and
buy_header.buy_id={$buy_id}
  ";
  return fetch_all_query($db, $sql);
 }

  
 function buy($db,$user_id,$buy_id){
  $sql="
  SELECT
  buy_header.buy_id,
  buy_header.date,
  
  buy_header.total,
  buy_details.price,
  buy_details.item_id,
  buy_details.amount,
  
  items.name
  from
  buy_header
  JOIN 
  buy_details
  ON
  buy_header.buy_id = buy_details.buy_id 
 JOIN
 items
 ON
 buy_details.item_id = items.item_id
  where
  buy_header.user_id={$user_id}
  and
buy_header.buy_id={$buy_id}
  LIMIT 1";
  return fetch_all_query($db, $sql);
 }
 function buy_ADMIN_header($db){
  $sql="
  SELECT
  buy_id,
  date,
  total
 
  from
  buy_header
 
  ";
  return fetch_all_query($db, $sql);

}
function buy_ADMIN_detailis($db,$buy_id){
  $sql="
  SELECT
  buy_header.buy_id,
  buy_header.date,
  
  buy_header.total,
  buy_details.price,
  buy_details.item_id,
  buy_details.amount,
  
  items.name
  from
  buy_header
  JOIN 
  buy_details
  ON
  buy_header.buy_id = buy_details.buy_id 
 JOIN
 items
 ON
 buy_details.item_id = items.item_id
  where
  buy_header.buy_id={$buy_id}
  ";
  return fetch_all_query($db, $sql);
 }

  
 function buy_ADMIN($db,$buy_id){
  $sql="
  SELECT
  buy_header.buy_id,
  buy_header.date,
  
  buy_header.total,
  buy_details.price,
  buy_details.item_id,
  buy_details.amount,
  
  items.name
  from
  buy_header
  JOIN 
  buy_details
  ON
  buy_header.buy_id = buy_details.buy_id 
 JOIN
 items
 ON
 buy_details.item_id = items.item_id
  where
  
buy_header.buy_id={$buy_id}
  LIMIT 1";
  return fetch_all_query($db, $sql);
 }