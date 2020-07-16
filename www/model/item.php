<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// DB利用

//指定した商品情報を取得する関数
function get_item($db, $item_id){
  $sql = "
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
    WHERE
      item_id = ?
  ";

  return fetch_query($db, $sql, [$item_id]);
}

//並べ替えた商品情報を取得する関数(created DESC)
function get_created_desc_items($db){
  $sql = "
    SELECT           
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM 
      items
    WHERE 
      status = 1
    ORDER BY
      created DESC
    ";
  return fetch_all_query($db, $sql);
}

//並べ替えた商品情報を取得する関数(price ASC)
function get_price_asc_items($db){
  $sql = "
    SELECT           
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM 
      items
    WHERE 
      status = 1
    ORDER BY
      price ASC
    ";
  return fetch_all_query($db, $sql);
}

//並べ替えた商品情報を取得する関数(price DESC)
function get_price_desc_items($db){
  $sql = "
    SELECT           
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM 
      items
    WHERE 
      status = 1
    ORDER BY
      price DESC
    ";
  return fetch_all_query($db, $sql);
}

//全ての商品情報を取得する関数
function get_items($db, $is_open = false){
  $sql = '
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
  ';
  if($is_open === true){
    $sql .= '
      WHERE status = 1
    ';
  }

  return fetch_all_query($db, $sql);
}

function get_all_items($db){
  return get_items($db);
}

function get_open_items($db){
  return get_items($db, true);
}

function regist_item($db, $name, $price, $stock, $status, $image){
  $filename = get_upload_filename($image);
  //var_dump($filename);  //OK
  if(validate_item($name, $price, $stock, $filename, $status) === false){
    return false;
  }
  return regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename);
}

function regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename){
  $db->beginTransaction();
  if(insert_item($db, $name, $price, $stock, $filename, $status) 
    && save_image($image, $filename)){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
}

function insert_item($db, $name, $price, $stock, $filename, $status){
  $status_value = PERMITTED_ITEM_STATUSES[$status];
  $sql = "
    INSERT INTO
      items(
        name,
        price,
        stock,
        image,
        status
      )
    VALUES(?, ?, ?, ?, ?);
  ";

  return execute_query($db, $sql, [$name, $price, $stock, $filename, $status_value]);
}

function update_item_status($db, $item_id, $status){
  $sql = "
    UPDATE
      items
    SET
      status = ?
    WHERE
      item_id = ?
    LIMIT 1
  ";
  
  return execute_query($db, $sql, [$status, $item_id]);
}

function update_item_stock($db, $item_id, $stock){
  $sql = "
    UPDATE
      items
    SET
      stock = ?
    WHERE
      item_id = ?
    LIMIT 1
  ";
  
  return execute_query($db, $sql, [$stock, $item_id]);
}

function destroy_item($db, $item_id){
  //商品IDに応じた、商品の情報を取得する関数を変数に保存
  $item = get_item($db, $item_id);
  //取得に失敗した場合はfalseを返す
  if($item === false){
    return false;
  }

  $db->beginTransaction();
  if(delete_item($db, $item['item_id'])
    && delete_image($item['image'])){
    //コミット時、trueを返す
    $db->commit();
    return true;
  }
  //ロールバック時、falseを返す
  $db->rollback();
  return false;
}

function delete_item($db, $item_id){
  $sql = "
    DELETE FROM
      items
    WHERE
      item_id = ?
    LIMIT 1
  ";
  
  return execute_query($db, $sql, [$item_id]);
}

//購入履歴・購入明細への商品情報登録を行うユーザー定義関数　7.1 テーブル設計
//$cartsはユーザーIDごとのカート内商品情報。
function order_products_statements($db, $carts){
  // ★ 'ユーザーIDに応じ、購入した商品の情報を購入履歴テーブルに登録する関数' を$resultに代入
  $result=insert_order_products($db, $carts[0]['user_id']); 
  if($result === true){
    //最後に取得したorder_idを取得し、$order_idに代入 
    $order_id = $db->lastInsertId();
    foreach($carts as $cart){
      // ★ 購入明細テーブルに、購入した商品の情報を登録(取得したorder_idに応じた情報) 
      $result = insert_statements($db, $order_id, $cart['item_id'], $cart['name'], $cart['price'], $cart['amount']);  
      //もし$resultからfalseが返ってきていた場合()、foreachから抜け出す。(明細への登録を行わない) 
      if($result === false){
        break;
      }
    }
  }

  //$resultを返す。
  return $result;
}

//購入履歴テーブル(order_products)に購入した商品の情報を登録  7.1 テーブル設計
function insert_order_products($db, $user){
  $sql = "
  INSERT INTO
    order_products(
      user_id
    )
  VALUES(?);
  "; 

  return execute_query($db, $sql, [$user]);
}

//購入明細テーブルに購入した商品の情報を登録  7.1 テーブル設計
function insert_statements($db, $order_id, $item_id, $item_name, $price, $amount){ //7.3
  $sql = "
  INSERT INTO
    statements(
      order_id,
      item_id,
      item_name,
      price,
      amount
    )
  VALUES(?, ?, ?, ?, ?);
  "; 

  return execute_query($db, $sql, [$order_id, $item_id, $item_name, $price, $amount]);
}


// 非DB

function is_open($item){
  return $item['status'] === 1;
}

function validate_item($name, $price, $stock, $filename, $status){
  $is_valid_item_name = is_valid_item_name($name);
  $is_valid_item_price = is_valid_item_price($price);
  $is_valid_item_stock = is_valid_item_stock($stock);
  $is_valid_item_filename = is_valid_item_filename($filename);
  $is_valid_item_status = is_valid_item_status($status);

  return $is_valid_item_name
    && $is_valid_item_price
    && $is_valid_item_stock
    && $is_valid_item_filename
    && $is_valid_item_status;
}

function is_valid_item_name($name){
  $is_valid = true;
  if(is_valid_length($name, ITEM_NAME_LENGTH_MIN, ITEM_NAME_LENGTH_MAX) === false){
    set_error('商品名は'. ITEM_NAME_LENGTH_MIN . '文字以上、' . ITEM_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  return $is_valid;
}

function is_valid_item_price($price){
  $is_valid = true;
  if(is_positive_integer($price) === false){
    set_error('価格は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

function is_valid_item_stock($stock){
  $is_valid = true;
  if(is_positive_integer($stock) === false){
    set_error('在庫数は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

function is_valid_item_filename($filename){
  $is_valid = true;
  if($filename === ''){
    $is_valid = false;
  }
  return $is_valid;
}

function is_valid_item_status($status){
  $is_valid = true;
  if(isset(PERMITTED_ITEM_STATUSES[$status]) === false){
    $is_valid = false;
  }
  return $is_valid;
}