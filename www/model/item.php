<?php
// conf...configの略 configとは設定という意味
// constとは　定数という意味
// ここでは定数を定義しているconst.phpを読み込む
require_once MODEL_PATH . 'functions.php';
// MODEL_PATHとはモデルを定義しているディレクトリへの道筋
// この定数はconst.phpに定義されている
// ここではモデルのdb.phpを読み込む
require_once MODEL_PATH . 'db.php';

// DB利用

// この関数は商品を取得する
// SELECT文でテーブルitemsから商品を取得
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
      item_id = ?";
    // prepareでSQLを実行準備
    $stmt = $db->prepare($sql);
    // プレースホルダで値をバインドバリュー
    $stmt->bindValue(1, $item_id, PDO::PARAM_INT);
    // SQL文を実行する
    $stmt->execute();
  // fetch_queryに返す
  return fetch_query($db, $sql);
}
// この関数は$is_openがfalseの場合itemsから商品を取得
// SELECT文でitemsから商品を取得
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
    // prepareでSQL文を実行準備
    $stmt = $db->prepare($sql);
    // SQL文を実行
    $stmt->execute();
    // 
  if($is_open === true){
    $sql .= '
      WHERE status = 1
    ';
    $stmt = $db->prepare($sql);
    $stmt->execute();
  }
// fetch_all_queryに返す
  return fetch_all_query($db, $sql);
}
// この関数は全ての商品をget_itemsに返す 
function get_all_items($db){
  return get_items($db);
}
// この関数は公開されている商品をget_itemsに返す
function get_open_items($db){
  return get_items($db, true);
}
// この関数は登録した商品が有効なものか確認
// 商品がfalseの場合falseを返す
function regist_item($db, $name, $price, $stock, $status, $image){
  $filename = get_upload_filename($image);
  if(validate_item($name, $price, $stock, $filename, $status) === false){
    return false;
  }
  // regist_item_transactionに返す
  return regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename);
}
// この関数は登録した商品をデータベースに繋げる
// 登録した商品をかつ商品のイメージをデータベースに保存し、trueを返す
function regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename){
  $db->beginTransaction();
  if(insert_item($db, $name, $price, $stock, $filename, $status) 
    && save_image($image, $filename)){
    $db->commit();
    return true;
  }
  // やりかけの処理を取り消し、falseを返す
  $db->rollback();
  return false;
  
}
// この関数は商品を登録する
// 許可した商品のステータスを$status_value変数に返す
function insert_item($db, $name, $price, $stock, $filename, $status){
  $status_value = PERMITTED_ITEM_STATUSES[$status];
// INSERT INTOでitemsから登録
  $sql = "
    INSERT INTO
      items(
        name,
        price,
        stock,
        image,
        status
      )
    VALUES(?, ?, ?, ?, ?)";
        // prepareでSQLを実行準備
        $stmt = $db->preapre($sql);
        // プレースホルダで値をバインドバリュー
        $stmt->bindValue(1, $name, PDO::PARAM_STR);
        $stmt->bindValue(2, $price, PDO::PARAM_INT);
        $stmt->bindValue(3, $stock, PDO::PARAM_INT);
        $stmt->bindValue(4, $image, PDO::PARAM_STR);
        $stmt->bindValue(5, $status, PDO::PARAM_INT);
        // SQL文を実行
        $stmt->execute();
        // execute_queryに返す
  return execute_query($db, $sql);
}
// この関数は商品のステータスを更新する
// UPDATE文でstatusとitem_idを更新
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
  // prepareでSQL文を実行準備
  $stmt = $db->prepare($sql);
  // プレースホルダで値をバインドバリュー
  $stmt->bindValue(1, $status, PDO::PARAM_INT);
  $stmt->bindValue(2, $item_id, PDO::PARAM_INT);
  // SQL文を実行する
  $stmt->execute();
  // execute_queryに返す
  return execute_query($db, $sql);
}
// この関数は商品の在庫を更新する
// UPDATE文でstock,item_idを更新する
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
  // prepareでSQL文を実行準備
  $stmt = $db->prepare($sql);
  // プレースホルダで値をバインドバリュー
  $stmt->bindValue(1, $stock, PDO::PARAM_INT);
  $stmt->bindValue(2, $item_id, PDO::PARAM_INT);
  // SQL文を実行する
  $stmt->execute();
  // execute_queryを返す
  return execute_query($db, $sql);
}
// この関数は商品を削除する
// get_itemを$item変数に代入
// $itemがfalseの場合falseを返す
function destroy_item($db, $item_id){
  $item = get_item($db, $item_id);
  if($item === false){
    return false;
  }
  // トランザクションを開始する
  // 削除する商品かつイメージをデータベースに保存しtrueを返す
  $db->beginTransaction();
  if(delete_item($db, $item['item_id'])
    && delete_image($item['image'])){
    $db->commit();
    return true;
  }
  // やりかけの処理を取り消し、falseを返す
  $db->rollback();
  return false;
}
// この関数は商品を削除する
// DELETE文でitemsからitem_idを削除
function delete_item($db, $item_id){
  $sql = "
    DELETE FROM
      items
    WHERE
      item_id = ?
    LIMIT 1
  ";
// prepareでSQL文を実行準備
  $stmt = $db->prepare($sql);
// プレースホルダで値をバインドバリュー
  $stmt->bindValue(1, $item_id, PDO::PARAM_INT);
// SQL文を実行する
  $stmt->execute();
// execute_queryを返す
  return execute_query($db, $sql);
}


// 非DB

// この関数は商品が公開されていると、ステータスは１を返す
function is_open($item){
  return $item['status'] === 1;
}
// この関数は商品を有効にする
// is_valid_itemの値をそれぞれの変数に代入
function validate_item($name, $price, $stock, $filename, $status){
  $is_valid_item_name = is_valid_item_name($name);
  $is_valid_item_price = is_valid_item_price($price);
  $is_valid_item_stock = is_valid_item_stock($stock);
  $is_valid_item_filename = is_valid_item_filename($filename);
  $is_valid_item_status = is_valid_item_status($status);
// それぞれの変数に返す
  return $is_valid_item_name
    && $is_valid_item_price
    && $is_valid_item_stock
    && $is_valid_item_filename
    && $is_valid_item_status;
}
// この関数は商品の名前が有効か確認
// 有効の場合true
// 商品の名前の長さがfalseの場合エラーメッセージを表示し、falseを返す
function is_valid_item_name($name){
  $is_valid = true;
  if(is_valid_length($name, ITEM_NAME_LENGTH_MIN, ITEM_NAME_LENGTH_MAX) === false){
    set_error('商品名は'. ITEM_NAME_LENGTH_MIN . '文字以上、' . ITEM_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  $is_vaildに返す
  return $is_valid;
}
// この関数は商品の値段が有効か確認
// 有効の場合true
// 商品の値段が正整数ではない場合、エラーメッセージを表示
// 無効の場合$is_valid変数にfalseを代入
// $is_vaildに返す
function is_valid_item_price($price){
  $is_valid = true;
  if(is_positive_integer($price) === false){
    set_error('価格は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}
// この関数は商品の在庫の確認
// 有効の場合はtrue
// 商品の在庫数は正整数ではない場合、エラーメッセージを表示し、$is_valid変数にfalseを代入
// $is_validに返す
function is_valid_item_stock($stock){
  $is_valid = true;
  if(is_positive_integer($stock) === false){
    set_error('在庫数は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}
// この関数は商品のファイルの名前の確認
// 有効の場合$is_vaildにtrue
// 商品のファイル名が空文字の場合$is_validにfalseを代入
// $is_vaildに返す
function is_valid_item_filename($filename){
  $is_valid = true;
  if($filename === ''){
    $is_valid = false;
  }
  return $is_valid;
}
// この関数は商品のステータスの確認
// 有効の場合は$is_validにtrue
// issetで変数がセットされているか調べる
// $statusがfalseの場合、$is_validにfalseに代入
// $is_vaildに返す
function is_valid_item_status($status){
  $is_valid = true;
  if(isset(PERMITTED_ITEM_STATUSES[$status]) === false){
    $is_valid = false;
  }
  return $is_valid;
}