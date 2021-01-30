<?php
//汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
//dbデータに関するファイル読み込み
require_once MODEL_PATH . 'db.php';

// DB利用
//DBからitem_idのデータを取得
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
  //DBのSQLを実行し１行のみレコード取得
  return fetch_query($db, $sql, $params = array($item_id));
}
//DBからアイテムデータを取得
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
  //$is_openがtrueだった場合、statusが１のアイテムを表示
  if($is_open === true){
    $sql .= '
      WHERE status = 1
    ';
  }
  //DBのSQLを実行し全ての結果行レコード取得
  return fetch_all_query($db, $sql);
}

//アイテムを全表示
function get_all_items($db){
  return get_items($db);
}

//ステータスが１のアイテムを表示
function get_open_items($db){
  return get_items($db, true);
}

//アイテム登録
function regist_item($db, $name, $price, $stock, $status, $image){
  //画像ファイルであった場合
  $filename = get_upload_filename($image);
  //それぞれの変数にエラーがあった場合
  if(validate_item($name, $price, $stock, $filename, $status) === false){
    return false;
  }
  //itemsテーブルにアイテム登録＆ファイルを移動
  return regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename);
}

//itemsテーブルにアイテム登録＆ファイルを移動
function regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename){
  //トランザクション開始
  $db->beginTransaction();
  //DBにitemsテーブルにアイテムを登録
  if(insert_item($db, $name, $price, $stock, $filename, $status) 
    //ファイルを一時フォルダから指定したディレクトリに移動
    && save_image($image, $filename)){
    //コミット処理
    $db->commit();
    return true;
  }
  //ロールバック処理
  $db->rollback();
  return false;
  
}

//DBのitemsテーブルにアイテムを登録
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
    VALUES( ?, ?, ?, ?, ?);
  ";
  //SQLを実行
  return execute_query($db, $sql, $params = array($name, $price, $stock, $filename, $status));
}

//itemsテーブルのstatusをアップデート
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
  //SQLを実行
  return execute_query($db, $sql, $params = array($status, $item_id));
}

//itemsテーブルのstockをアップデート
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
  //SQLを実行
  return execute_query($db, $sql, $params = array($stock, $item_id));
}

//アイテムと画像削除
function destroy_item($db, $item_id){
  //itemデータを取得
  $item = get_item($db, $item_id);
  //itemが無かったら
  if($item === false){
    //falseを返す
    return false;
  }
  //トランザクション開始
  $db->beginTransaction();
  //item削除と画像削除
  if(delete_item($db, $item['item_id'])
    && delete_image($item['image'])){
    //コミット処理
    $db->commit();
    return true;
  }
  //ロールバック処理
  $db->rollback();
  return false;
}

//DBのitemsテーブルのitemを削除
function delete_item($db, $item_id){
  $sql = "
    DELETE FROM
      items
    WHERE
      item_id = ?
    LIMIT 1
  ";
  //SQLを実行
  return execute_query($db, $sql, $params = array($item_id));
}

// 非DB
//$itemのステータスが１の時
function is_open($item){
  return $item['status'] === 1;
}

//有効なアイテムのデータ
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

//商品名のエラーチェック
function is_valid_item_name($name){
  $is_valid = true;
  //文字の長さを取得し$nameが１〜１００でない場合
  if(is_valid_length($name, ITEM_NAME_LENGTH_MIN, ITEM_NAME_LENGTH_MAX) === false){
    //セッション変数にエラー表示
    set_error('商品名は'. ITEM_NAME_LENGTH_MIN . '文字以上、' . ITEM_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  return $is_valid;
}

//価格のエラーチェック
function is_valid_item_price($price){
  $is_valid = true;
  //文字列が０以上の整数であるかチェック
  if(is_positive_integer($price) === false){
    //NGの場合、セッション変数にエラー表示
    set_error('価格は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

//ストックのエラーチェク
function is_valid_item_stock($stock){
  $is_valid = true;
  //文字列が０以上の整数であるかチェック
  if(is_positive_integer($stock) === false){
    //NGの場合、セッション変数にエラー表示
    set_error('在庫数は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

//ファイルのエラーチェック
function is_valid_item_filename($filename){
  $is_valid = true;
  //$filenameが空だった場合
  if($filename === ''){
    $is_valid = false;
  }
  return $is_valid;
}

//ステータスのエラーチェック
function is_valid_item_status($status){
  $is_valid = true;
  //$statusが配列キーにあるかどうかチェック、NGの場合
  if(array_key_exists($status, PERMITTED_ITEM_STATUSES) === false){
    $is_valid = false;
  }
  return $is_valid;
}