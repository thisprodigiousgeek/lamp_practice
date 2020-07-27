<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// DB利用
//アイテムの情報を文字列として1行ずつ取得する
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
//公開されているアイテムの情報を取得する
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
//アイテムのデータベースの情報
function get_all_items($db){
  return get_items($db);
}
//公開されているアイテムのデータベースの情報
function get_open_items($db){
  return get_items($db, true);
}
//追加しようとしている商品情報が問題ないか確認する
function regist_item($db, $name, $price, $stock, $status, $image){
  $filename = get_upload_filename($image);
  if(validate_item($name, $price, $stock, $filename, $status) === false){
    return false;
  }
  return regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename);
}
//追加しようとしている商品が問題なければインサートされる
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
//アイテムをインサートする
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

  return execute_query($db, $sql, array($name, $price, $stock, $image, $status));
}
//アイテムのステータス情報をアップデートする
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
//アイテムの在庫情報をアップデートする
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

  // $params = array(
  //   ':stock' => $stock,
  //   ':item_id' => $item_id
  // );

  //このままではだめなので変数を直接展開せずにいったん？にしてバインドする
  //execute_queryをそのままいかす
  //$stmt->execute()ではなく$stmt->execute($params)になっているのはどうゆうことか
    //bindValueの文を書かなくても$paramsにarrayで書き込めばいいから
  //execute_queryの第三引数が$params=array()という式になっているがこれがどういう意味か
  return execute_query($db, $sql, array($stock, $item_id));
}
//アイテムの削除の実行
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
//アイテムの削除
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
//ステータスが公開かどうか確認する
function is_open($item){
  return $item['status'] === 1;
}
//追加しようとしている商品情報がしっかり入力されているか確認する
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
//名前が条件にあっているか確認
function is_valid_item_name($name){
  $is_valid = true;
  if(is_valid_length($name, ITEM_NAME_LENGTH_MIN, ITEM_NAME_LENGTH_MAX) === false){
    set_error('商品名は'. ITEM_NAME_LENGTH_MIN . '文字以上、' . ITEM_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  return $is_valid;
}
//値段が条件にあっているか確認
function is_valid_item_price($price){
  $is_valid = true;
  if(is_positive_integer($price) === false){
    set_error('価格は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}
//在庫が条件にあっているか確認
function is_valid_item_stock($stock){
  $is_valid = true;
  if(is_positive_integer($stock) === false){
    set_error('在庫数は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}
//ファイルが登録されているかの確認
function is_valid_item_filename($filename){
  $is_valid = true;
  if($filename === ''){
    $is_valid = false;
  }
  return $is_valid;
}
//ステータスが設定されているかの確認
function is_valid_item_status($status){
  $is_valid = true;
  if(isset(PERMITTED_ITEM_STATUSES[$status]) === false){
    $is_valid = false;
  }
  return $is_valid;
}