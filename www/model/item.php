<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// DB利用
/**
 * クエリを実行し、商品情報を取得
 * @param obj $db dbハンドル
 * @param int $item_id 商品id
 * @return bool
 */
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

  return fetch_query($db, $sql, array($item_id));
}
/**
 * クエリを実行し、item情報を全取得
 * @param obj $db dbハンドル
 * @param bool $is_open status情報
 * @return array|bool 結果配列|false
 */
function get_items($db, $is_open = false){
  //trueの場合、結合代入演算子'.='で条件を追加
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
/**
 * ステータス情報に関係なく、全ての商品情報を取得
 * @param obj $db dbハンドル
 * @return array|bool 結果配列|false
 */
function get_open_items($db){
  return get_items($db, true);
}

function regist_item($db, $name, $price, $stock, $status, $image){
  // ファイルアップロードに関するバリデーション関数の呼び出し
  $filename = get_upload_filename($image);
  // ファイル以外のバリデーション関数の呼び出し
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
/**
 * クエリを実行し、新規商品の追加
 * @param obj $db dbハンドル
 * @param str $name 商品名
 * @param int $price 価格
 * @param int $stock 在庫数
 * @param str $filename 画像ファイル名
 * @param str $status ステータス情報
 * @return bool
 */
function insert_item($db, $name, $price, $stock, $filename, $status){
  // 定数ファイルより、ステータス情報の取得
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

  return execute_query($db, $sql, array($name, $price, $stock, $filename, $status_value));
}
/**
 * クエリを実行し、商品のステータス情報を更新
 * @param obj $db dbハンドル
 * @param int $item_id 商品id
 * @param int $status ステータス情報
 * @return bool
 */
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
  
  return execute_query($db, $sql, array($status, $item_id));
}
/**
 * クエリを実行し、在庫数の更新
 * @param obj $db dbハンドル
 * @param int $item_id 商品id
 * @param int $stock 在庫数
 * @return bool
 */
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
  return execute_query($db, $sql,array($stock,$item_id));
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
/**
 * クエリを実行し、商品を削除
 * @param obj $db dbハンドル
 * @param int $item_id 商品id
 * @return bool
 */
function delete_item($db, $item_id){
  $sql = "
    DELETE FROM
      items
    WHERE
      item_id = ?
    LIMIT 1
  ";
  
  return execute_query($db, $sql, array($item_id));
}


// 非DB
/**
 * ステータス情報が公開であるか判定
 * @param array $item 商品情報(2次元配列)
 * @return bool
 */
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