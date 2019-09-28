<?php
// 関数ファイル読み込み
require_once 'functions.php';
// DBファイル読み込み
require_once 'db.php';

// DB利用

// 指定した商品表示
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

  // クエリを実行
  return fetch_query($db, $sql);
}

// 商品一覧を表示
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

  // 公開商品のみ表示
  if($is_open === true){
    $sql .= '
      WHERE status = 1
    ';
  }

  // クエリ実行
  return fetch_all_query($db, $sql);
}

// 商品一覧表示
function get_all_items($db){
  return get_items($db);
}

// 公開のみの商品一覧表示
function get_open_items($db){
  return get_items($db, true);
}

// 商品追加処理
function regist_item($db, $name, $price, $stock, $status, $image){
  $filename = get_upload_filename($image);
  // エラーチェック
  if(validate_item($name, $price, $stock, $filename, $status) === false){
    return false;
  }
  // エラーがなければトランザクション実行
  return regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename);
}

// 商品追加のトランザクション処理(画像と画像以外)
function regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename){
  $db->beginTransaction();
  // 商品追加
  if(insert_item($db, $name, $price, $stock, $filename, $status) 
  // 画像ファイル保存
    && save_image($image, $filename)){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
  
}

// 商品追加
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

  // クエリ実行
  return execute_query($db, $sql);
}

// 公開or非公開を更新(0or1)
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
  
  // クエリ実行
  return execute_query($db, $sql);
}

// 在庫数の更新
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
  
  // クエリ実行
  return execute_query($db, $sql);
}

// 商品削除処理
function destroy_item($db, $item_id){
  // $item_idから商品単体情報取得
  $item = get_item($db, $item_id);
  if($item === false){
    return false;
  }
  // 商品追加のトランザクション処理(画像と画像以外)
  $db->beginTransaction();
  // 商品削除
  if(delete_item($db, $item['item_id'])
  // 画像ファイル削除
    && delete_image($item['image'])){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
}

// 商品削除
function delete_item($db, $item_id){
  $sql = "
    DELETE FROM
      items
    WHERE
      item_id = {$item_id}
    LIMIT 1
  ";
  
  // クエリ実行
  return execute_query($db, $sql);
}


// 非DB

// statusが公開中
function is_open($item){
  return $item['status'] === 1;
}

// 各項目のバリデーションチェック(エラーがあればfalse, なければtrueが返ってくる)
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

// 商品名のバリデーションチェック
function is_valid_item_name($name){
  $is_valid = true;
  // 商品名(1文字〜100文字まで) 範囲外の場合はエラーメッセージセット
  if(is_valid_length($name, ITEM_NAME_LENGTH_MIN, ITEM_NAME_LENGTH_MAX) === false){
    set_error('商品名は'. ITEM_NAME_LENGTH_MIN . '文字以上、' . ITEM_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  return $is_valid;
}

// 商品価格のバリデーションチェック
function is_valid_item_price($price){
  $is_valid = true;
  // ('/\A([1-9][0-9]*|0)\z/')の正規表現チェック。falseの場合はエラーメッセージセット
  if(is_positive_integer($price) === false){
    set_error('価格は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

// 在庫数のバリデーションチェック
function is_valid_item_stock($stock){
  $is_valid = true;
  // ('/\A([1-9][0-9]*|0)\z/')の正規表現チェック。falseの場合はエラーメッセージセット
  if(is_positive_integer($stock) === false){
    set_error('在庫数は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

// 画像ファイルのバリデーションチェック
function is_valid_item_filename($filename){
  $is_valid = true;
  if($filename === ''){
    $is_valid = false;
  }
  return $is_valid;
}

// 公開or非公開のバリデーションチェック
function is_valid_item_status($status){
  $is_valid = true;
  // 1 = open , 0 = close　それ以外はfalse
  if(isset(PERMITTED_ITEM_STATUSES[$status]) === false){
    $is_valid = false;
  }
  return $is_valid;
}