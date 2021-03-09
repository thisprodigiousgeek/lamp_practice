<?php
//関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
//データ取得ファイル読み込み
require_once MODEL_PATH . 'db.php';

// DB利用

//商品情報一覧取得
function get_item($db, $item_id){
  //商品一覧情報取得
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

  //SQL文の準備、実行、レコード取得
  return fetch_query($db, $sql);
}

//商品アイテム情報のステータスが非公開であればfalse、公開であればtrue
function get_items($db, $is_open = false){
  //商品一覧情報取得
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
    //SQL文の連結
    $sql .= '
      WHERE status = 1
    ';
  }

  //SQL文の準備、実行、レコード取得
  return fetch_all_query($db, $sql);
}

//商品一覧情報に接続
function get_all_items($db){
  return get_items($db);
}

//商品一覧情報に接続し、trueを返す
function get_open_items($db){
  return get_items($db, true);
}

//商品情報の登録
function regist_item($db, $name, $price, $stock, $status, $image){
  //アップロードした画像を定義
  $filename = get_upload_filename($image);
  //有効な商品でなければfalseを返す 
  if(validate_item($name, $price, $stock, $filename, $status) === false){
    return false;
  }
  //アップロードできればトランザクション処理
  return regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename);
}

function regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename){
  //トランザクション処理
  $db->beginTransaction();
  //商品追加、画像の保存処理
  if(insert_item($db, $name, $price, $stock, $filename, $status) 
    && save_image($image, $filename)){
    //コミット処理
      $db->commit();
    return true;
  }
  //ロールバック　処理
  $db->rollback();
  return false;
  
}

//商品追加処理
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

//ステータスの更新処理
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
  
  return execute_query($db, $sql);
}

//在庫更新処理
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

//商品削除処理
function destroy_item($db, $item_id){
  $item = get_item($db, $item_id);
  if($item === false){
    return false;
  }
  //トランザクション処理
  $db->beginTransaction();
  //商品情報、画像削除
  if(delete_item($db, $item['item_id'])
    && delete_image($item['image'])){
    //コミット処理
    $db->commit();
    return true;
  }
  //ロールバック
  $db->rollback();
  return false;
}

//商品削除
function delete_item($db, $item_id){
  $sql = "
    DELETE FROM
      items
    WHERE
      item_id = {$item_id}
    LIMIT 1
  ";
  
  return execute_query($db, $sql);
}


// 非DB
//ステータスの公開
function is_open($item){
  return $item['status'] === 1;
}

//有効な商品の設定
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

//有効な商品名の設定
function is_valid_item_name($name){
  $is_valid = true;
  if(is_valid_length($name, ITEM_NAME_LENGTH_MIN, ITEM_NAME_LENGTH_MAX) === false){
    set_error('商品名は'. ITEM_NAME_LENGTH_MIN . '文字以上、' . ITEM_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  return $is_valid;
}

//有効な商品価格の設定
function is_valid_item_price($price){
  $is_valid = true;
  if(is_positive_integer($price) === false){
    set_error('価格は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

//有効な商品在庫の設定
function is_valid_item_stock($stock){
  $is_valid = true;
  if(is_positive_integer($stock) === false){
    set_error('在庫数は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

//有効な画像名の設定
function is_valid_item_filename($filename){
  $is_valid = true;
  if($filename === ''){
    $is_valid = false;
  }
  return $is_valid;
}

//有効な商品ステータスの設定
function is_valid_item_status($status){
  $is_valid = true;
  if(isset(PERMITTED_ITEM_STATUSES[$status]) === false){
    $is_valid = false;
  }
  return $is_valid;
}