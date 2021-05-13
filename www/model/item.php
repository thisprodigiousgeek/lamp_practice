<?php
/*
外部ファイルを読み込む
ファイルが一度読み込まれているかPHPがチェックする
既に読み込まれている場合はそのファイルを読み込まない
*/
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// DB利用

/*
データベース接続情報とアイテムidを引数として入力
itemsテーブルの中のSELECT文を選択
抽出条件は第二引数に入れたitem_id　
欲しいアイテムのIDを入れて、itemsテーブルにある情報を取り出す
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
      item_id = {$item_id}
  ";

  return fetch_query($db, $sql);
}

/*引数に代入式は初期値を設定
$is_openがtrueならstatusが1のものを取り出す
fetch_all_queryなので、取り出す情報は複数件あると考える
*/
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

/*
itemsテーブルにあるアイテム情報が欲しい時に使う
初期値としてfalseが入っているためif文をスルー
*/
function get_all_items($db){
  return get_items($db);
}

/*
itemsテーブルにあるステータスがopenの情報を取得する時に使う
初期値としてfalseが入っているため、trueに変更
*/
function get_open_items($db){
  return get_items($db, true);
}

/*
アイテムを登録

*/
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

/*
新商品を追加する
*/

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

/*
ステータスの変更する時に使う
*/
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

/*
アップデート文を使用
item_idを抽出条件に設定してstockを更新する
*/
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

/*
アイテムを破壊？笑
itemsテーブルからitem_idを抽出条件にitem情報を取得
取得に失敗、エラーが起きればfalseを返す
トランザクション開始
指定したitem_idとimageを削除する
途中でエラーが起きたらrollbackする
*/
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

/*
引数にデータベース接続情報とitem_idを入力
DELETE対象はitemsテーブルのitem_id
*/
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

/*
引数として入力されたステータスはが1ならtrue
イコール1ではなければfalseを返す　
1はステータス open（公開) 0は close(非公開)
*/
function is_open($item){
  return $item['status'] === 1;
}

/*
$is_validにtrue or falseが返ってくる
その値はreturnする
*/
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

/*
$nameの文字数がITEM_NAME_LENGTH_MIN以上、ITEM_NAME_LENGTH_MAXなのかを確認する関数
falseならエラーメッセージをset_errorに記録
*/
function is_valid_item_name($name){
  $is_valid = true;
  if(is_valid_length($name, ITEM_NAME_LENGTH_MIN, ITEM_NAME_LENGTH_MAX) === false){
    set_error('商品名は'. ITEM_NAME_LENGTH_MIN . '文字以上、' . ITEM_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  return $is_valid;
}

/*
$priceが0以上の整数かを確認している
正規表現がfalseならset_errorにエラーメッセージを記録
*/
function is_valid_item_price($price){
  $is_valid = true;
  if(is_positive_integer($price) === false){
    set_error('価格は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

/*
$stockが正の整数かを確認
正規表現がfalseならset_errorにエラーメッセージを記録
*/
function is_valid_item_stock($stock){
  $is_valid = true;
  if(is_positive_integer($stock) === false){
    set_error('在庫数は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

/*
関数の引数が空文字だった場合はfalseを代入する
*/
function is_valid_item_filename($filename){
  $is_valid = true;
  if($filename === ''){
    $is_valid = false;
  }
  return $is_valid;
}

/*
open or close　にfalseが出てくる？
*/
function is_valid_item_status($status){
  $is_valid = true;
  if(isset(PERMITTED_ITEM_STATUSES[$status]) === false){
    $is_valid = false;
  }
  return $is_valid;
}