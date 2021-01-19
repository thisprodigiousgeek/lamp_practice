<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// DB利用

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
      item_id = {$item_id}
  ";

  return fetch_query($db, $sql);
}
//商品一覧（複数取得行）取得のsql文
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
  //ステータスの公開、非公開を設定
  if($is_open === true){
    $sql .= '
      WHERE status = 1
    ';
  }
  //sql文を実行して、配列を返す
  return fetch_all_query($db, $sql);
}
//商品一覧を取得する
function get_all_items($db){
  return get_items($db);
}

function get_open_items($db){
  return get_items($db, true);
}
//商品を新規追加するステップ
function regist_item($db, $name, $price, $stock, $status, $image){
  //指定ファイル形式かを判断
  $filename = get_upload_filename($image);
  //新規追加の商品が規約にかかってないか確認
  if(validate_item($name, $price, $stock, $filename, $status) === false){
    return false;
  }
  //商品を追加しての真偽値を返す
  return regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename);
}
//商品を追加しての真偽値を返す
function regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename){
  $db->beginTransaction();
  //商品の新規追加と画像アップロードが成功はtrue、失敗はfalse
  if(insert_item($db, $name, $price, $stock, $filename, $status) 
    && save_image($image, $filename)){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
  
}
//商品の新規追加sql文
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
    VALUES('{$name}', {$price}, {$stock}, '{$filename}', {$status_value});
  ";

  return execute_query($db, $sql);
}
//該当商品のステータスを更新するsql文
function update_item_status($db, $item_id, $status){
  $sql = "
    UPDATE
      items
    SET
      status = {$status}
    WHERE
      item_id = {$item_id}
    LIMIT 1
  ";
  //データを更新して、真偽値を取得
  return execute_query($db, $sql);
}
//該当商品の在庫数を更新するsql文
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
  //データを更新して、真偽値を取得
  return execute_query($db, $sql);
}
//該当商品をするまでのステップ
function destroy_item($db, $item_id){
  //該当商品の情報を変数へ代入
  $item = get_item($db, $item_id);
  //該当商品がない場合はfalse
  if($item === false){
    return false;
  }
  $db->beginTransaction();
  //商品情報と保存先画像を削除実行
  if(delete_item($db, $item['item_id'])
    && delete_image($item['image'])){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
}
//商品を削除するslq文
function delete_item($db, $item_id){
  $sql = "
    DELETE FROM
      items
    WHERE
      item_id = {$item_id}
    LIMIT 1
  ";
  //データを削除して、真偽値を取得
  return execute_query($db, $sql);
}


// 非DB

function is_open($item){
  return $item['status'] === 1;
}
//商品名の規約を確認しての真偽値を返す
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
//商品名の規約確認
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