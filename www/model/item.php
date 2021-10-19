<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// DB利用

//$item_idとitem_idが一致する商品のレコードを取得
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
      item_id = :item_id
  ";
  
  //sql文をfetch_queryに返して実行
  return fetch_query($db, $sql,array(':item_id'=>$item_id));
}

//公開状態になっている商品全てを取得
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

  //公開状態のみ
  if($is_open === true){
    $sql .= '
      WHERE status = 1
    ';
  }
  
  //sql文をfetch_all_queryに返して実行
  return fetch_all_query($db, $sql);
}

//商品を新着順に取得
function get_sort_item($db,$is_open = false,$sort){

  $sql = 'SELECT
            item_id, 
            name,
            stock,
            price,
            image,
            status
          FROM
            items
          ';
  //公開状態のみ
  if($is_open === true){
    $sql .= '
      WHERE status = 1
    ';
  }

  //並び替え内容

  //価格の高い順
  if($sort === 'expensive'){
      $sql .= '
      ORDER BY price DESC';
  //価格の安い順
  }else if($sort === 'cheap'){
    $sql .= '
      ORDER BY price';

  //新着順
  }else{
    $sql .= '
      ORDER BY created DESC';
  }
  
  //sql文をfetch_all_queryに返して実行
  return fetch_all_query($db, $sql);

}

//ステータス関係なく全ての商品を取得する
function get_all_items($db){
  return get_items($db);
}

//?
function get_open_items($db){
  return get_items($db, true);
}

//商品を並び替えて取得する
function get_sort_items($db,$sort){
  return get_sort_item($db,true,$sort);
}

//新規商品登録処理
function regist_item($db, $name, $price, $stock, $status, $image){

  //商品画像名を$fileimageに代入
  $filename = get_upload_filename($image);

  //商品登録の入力時に不備がある場合?
  if(validate_item($name, $price, $stock, $filename, $status) === false){
    return false;
  }

  
  return regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename);
}

//新規商品登録処理
function regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename){

  //トランザクション開始
  $db->beginTransaction();

  //itemsテーブルにレコードがインサート且つ商品画像が指定のフォルダに移動・保存された場合のみ勝利の確定をする
  if(insert_item($db, $name, $price, $stock, $filename, $status) 
    && save_image($image, $filename)){
    $db->commit();
    return true;
  }

  //上記の条件式を満たさなかった場合、処理の取り消しをする
  $db->rollback();
  return false;
  
}

//新規商品の入力データをitemsテーブルにインサートする
function insert_item($db, $name, $price, $stock, $filename, $status){

  //わからない
  $status_value = PERMITTED_ITEM_STATUSES[$status];

  //sql作成
  $sql = "
    INSERT INTO
      items(
        name,
        price,
        stock,
        image,
        status
      )
    VALUES(:name,:price,:stock,:filename,:status_value);
  ";
  
  //sql文をexecute_queryに返して実行
  return execute_query($db, $sql,array(':name'=>$name,':price'=>$price,':stock'=>$stock,':filename'=>$filename,':status_value'=>$status_value));
}

//商品のステータス更新処理
function update_item_status($db, $item_id, $status){

  //sql文作成
  $sql = "
    UPDATE
      items
    SET
      status = :status
    WHERE
      item_id = :item_id
    LIMIT 1
  ";
  
  //sql文をexecute_queryに返して実行
  return execute_query($db, $sql,array('status'=>$status,':item_id'=>$item_id));
}

//商品の在庫数更新処理
function update_item_stock($db, $item_id, $stock){

  //sql作成
  $sql = "
    UPDATE
      items
    SET
      stock = :stock
    WHERE
      item_id = :item_id
    LIMIT 1
  ";
  
  //sql文をexecute_queryに返して実行
  return execute_query($db, $sql,array(':stock'=>$stock,':item_id'=>$item_id));
}

//商品登録抹消処理
function destroy_item($db, $item_id){
$item = get_item($db, $item_id);

  //わからない
  if($item === false){
    return false;
  }

  //トランザクション開始
  $db->beginTransaction();

  //itemsテーブルから$item_idと一致する商品の削除且つ指定されたフォルダから画像の削除ができた場合のみ処理の確定をする
  if(delete_item($db, $item['item_id'])
    && delete_image($item['image'])){
    $db->commit();
    return true;
  }

  //上記の条件式を満たせなかった場合、処理の取り消しをする
  $db->rollback();
  return false;
}

//$item_idとitem_idカラムの一致する商品の削除をする
function delete_item($db, $item_id){
  $sql = "
    DELETE FROM
      items
    WHERE
      item_id = :item_id
    LIMIT 1
  ";
  
  //sql文をexecute_queryに返して実行
  return execute_query($db, $sql,array(':item_id'=>$item_id));
}


// 非DB

//わからない
function is_open($item){
  return $item['status'] === 1;
}

//商品のデータをそれぞれ変数に代入する
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

//入力された商品の商品名の文字数確認
function is_valid_item_name($name){
  $is_valid = true;
  if(is_valid_length($name, ITEM_NAME_LENGTH_MIN, ITEM_NAME_LENGTH_MAX) === false){
    set_error('商品名は'. ITEM_NAME_LENGTH_MIN . '文字以上、' . ITEM_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  return $is_valid;
}

//入力された商品の価格の確認
function is_valid_item_price($price){
  $is_valid = true;
  if(is_positive_integer($price) === false){
    set_error('価格は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

//入力された商品の在庫数確認
function is_valid_item_stock($stock){
  $is_valid = true;
  if(is_positive_integer($stock) === false){
    set_error('在庫数は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

//商品の画像が選択されているか確認
function is_valid_item_filename($filename){
  $is_valid = true;
  
  if($filename === ''){
    $is_valid = false;
  }
  return $is_valid;
}

//入力されたステータスの確認
function is_valid_item_status($status){
  $is_valid = true;

  //PERMITTED_ITEM_STATUSES?
  if(isset(PERMITTED_ITEM_STATUSES[$status]) === false){
    $is_valid = false;
  }
  return $is_valid;
}