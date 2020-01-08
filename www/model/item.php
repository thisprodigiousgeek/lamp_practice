<?php
require_once 'functions.php'; //function.phpファイルを読み込み
require_once 'db.php';//db.phpファイルを読み込み
// DB利用
//特定の商品の情報を返り値として返す関数
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
  
  return fetch_query($db, $sql,array($item_id));
}
//商品の情報を返り値として返す。第２引数を更新しなければ全ての情報を、更新してtrueにすれば公開中の商品のみの情報を返り値として返す
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
//全ての商品の情報を返り値として返す
function get_all_items($db){
  return get_items($db);
}
//公開中の商品の情報を返り値として返す
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
//アイテムテーブルへ商品情報の追加と、画像ファイルのディレクトリへの保存の両方行う（片方のみ実行されることはない）
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
//アイテムテーブルに新しく商品情報を追加する
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

  return execute_query($db, $sql, array($name, $price, $stock, $filename, $status_value));
}
//商品の公開ステータスを更新する
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
//特定の商品の在庫数を変更する
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
  
  return execute_query($db, $sql, array($stock, $item_id));
}
//商品の情報とその写真ファイルを削除する（どちらか一方のみが削除されることはない）
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
//特定の商品を削除する
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

function is_open($item){
  return $item['status'] === 1;
}
//商品の名前、価格、在庫数、ファイル名,公開ステータスが正しく入力されている時、その値を返り値として返す
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
//商品の名前が正しい長さで入寮されている時はtrue,それ以外はfalseを返す
function is_valid_item_name($name){
  $is_valid = true;
  if(is_valid_length($name, ITEM_NAME_LENGTH_MIN, ITEM_NAME_LENGTH_MAX) === false){
    set_error('商品名は'. ITEM_NAME_LENGTH_MIN . '文字以上、' . ITEM_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  return $is_valid;
}
//商品価格が0以上の整数で入力されているときはtrue,それ以外の時はfalseを返す
function is_valid_item_price($price){
  $is_valid = true;
  if(is_positive_integer($price) === false){
    set_error('価格は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}
//在庫数が0以上の整数で入力されているときはtrue,それ以外はfalseを返す
function is_valid_item_stock($stock){
  $is_valid = true;
  if(is_positive_integer($stock) === false){
    set_error('在庫数は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}
//ファイル名が指定されているときはtrueを、指定されていないときはfalseを返す
function is_valid_item_filename($filename){
  $is_valid = true;
  if($filename === ''){
    $is_valid = false;
  }
  return $is_valid;
}
//公開ステータスが指定されていないときはfalseを返し、指定されているときはtrueを返す
function is_valid_item_status($status){
  $is_valid = true;
  if(isset(PERMITTED_ITEM_STATUSES[$status]) === false){
    $is_valid = false;
  }
  return $is_valid;
}