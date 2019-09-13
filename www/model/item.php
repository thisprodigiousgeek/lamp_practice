<?php
require_once 'functions.php';
require_once 'db.php';

// DB利用

//テーブルitemsを呼び出す
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
      item_id =?
  ";
$stmt=$db->prepare($sql);
$stmt->bindValue(1,$item_id,PDO::PARAM_INT);
$stmt->execute();
return $item=$stmt->fetchALL();
}

function get_items($db, $is_open = false){
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
      
  ";
  if($is_open === true){
    $sql .= '
      WHERE status = 1
    ';
  }
  $stmt=$db->prepare($sql);

  $stmt->execute();
  return $item=$stmt->fetchALL();
}

function get_all_items($db){
  return get_items($db);
}

function get_open_items($db){
  return get_items($db, true);
}

function regist_item($db, $name, $price, $stock, $status, $image){
  $filename = get_upload_filename($image);
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
  try{
  $sql = "
    INSERT INTO
      items(
        name,
        price,
        stock,
        image,
        status
      )
    VALUES(?,?,?,?,?);
  ";
  $stmt=$db->prepare($sql);
  $stmt->bindValue(1,$name,PDO::PARAM_STR);
  $stmt->bindValue(2,$price,PDO::PARAM_INT);
  $stmt->bindValue(3,$stock,PDO::PARAM_INT);
  $stmt->bindValue(4,$filename,PDO::PARAM_STR);
  $stmt->bindValue(5,$status_value,PDO::PARAM_INT);
  $stmt->execute();
return true;
    }catch(PDOException $e){
      return false;
      throw $e;
    }
}

function update_item_status($db, $item_id, $status){
  try{
  $sql = "
    UPDATE
      items
    SET
      status = ?
    WHERE
      item_id = ?
    LIMIT 1
  ";
  
  $stmt=$db->prepare($sql);
  $stmt->bindValue(1,$status,PDO::PARAM_INT);
  $stmt->bindValue(2,$item_id,PDO::PARAM_INT);
  $stmt->execute();
  }catch(PDOException $e){
    return false;
    throw $e;
  }
  
}

function update_item_stock($db, $item_id, $stock){
  $sql = "
    UPDATE
      items
    SET
      stock = {$stock}
    WHERE
      item_id = {$item_id}
    LIMIT 1
  ";
  
  return execute_query($db, $sql);
}

function destroy_item($db, $item_id){
  $item = get_item($db, $item_id);
  if($item === false){
    return false;
  }
  $db->beginTransaction();
  if(delete_item($db, $item['item_id'])
    && delete_image($item['image'])){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
}

function delete_item($db, $item_id){
  try{
  $sql = "
    DELETE FROM
      items
    WHERE
      item_id =?
    LIMIT 1
  ";
  $stmt=$db->prepare($sql);
  $stmt->bindValue(1,$item_id,PDO::PARAM_INT);
  $stmt->execute();
  return true;
  }catch(PDOException $e){
    return false;
    throw $e;
  }
  
}


// 非DB

function is_open($item){
  return $item['status'] === 1;
}
//各種エラーテェックをvalidate_itemにまとめて代入
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

//nameエラーチェック
function is_valid_item_name($name){
  $is_valid = true;
  if(is_valid_length($name, ITEM_NAME_LENGTH_MIN, ITEM_NAME_LENGTH_MAX) === false){
    set_error('商品名は'. ITEM_NAME_LENGTH_MIN . '文字以上、' . ITEM_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  return $is_valid;
}

//priceエラーチェック
function is_valid_item_price($price){
  $is_valid = true;
  if(is_positive_integer($price) === false){
    set_error('価格は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}
//stockエラーチェック
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
            