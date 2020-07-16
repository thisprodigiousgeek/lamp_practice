<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// DB利用

function get_item($db, $item_id){
  $params = array($item_id);
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

  return fetch_query($db, $sql, $params);
}

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

function get_all_items($db){
  return get_items($db);
}

function get_open_items($db){
  return get_items($db, true);
}

function get_history($db, $user_id)
{
  $params = array($user_id);
  $sql = "
  SELECT
    h.history_id,
    h.create_datetime,
    sum(i.price * hd.amount) as total_price
  FROM
    histories as h
  INNER JOIN
    history_dateils as hd
  ON
    h.history_id = hd.history_id
  INNER JOIN
    items as i
  ON
    i.item_id = hd.item_id
  WHERE
    user_id = ?
  GROUP BY
    h.history_id
  ORDER BY
    h.create_datetime
  DESC
    ";
  return fetch_all_query($db, $sql, $params);
}

function get_all_history($db)
{
  $sql = "
  SELECT
    h.history_id,
    h.create_datetime,
    sum(i.price * hd.amount) as total_price
  FROM
    histories as h
  INNER JOIN
    history_dateils as hd
  ON
    h.history_id = hd.history_id
  INNER JOIN
    items as i
  ON
    i.item_id = hd.item_id
  GROUP BY
    h.history_id
  ORDER BY
    h.create_datetime
    DESC
  ";
  return fetch_all_query($db, $sql);
}

function get_history_detail($db, $user_id, $history_id){
  $params = array($user_id, $history_id);
  $sql = "
SELECT
    i.name,
    hd.price,
    hd.amount,
    hd.price * hd.amount as sub_total_price,
    hd.create_datetime
  FROM
    history_dateils as hd
  INNER JOIN
    items as i
  ON
    i.item_id = hd.item_id
  INNER JOIN
    histories as h
  ON
    h.history_id = hd.history_id
  WHERE
    h.user_id = ?
    and
    hd.history_id = ?
  ";
  return fetch_all_query($db, $sql, $params);
}

function get_all_history_detail($db, $history_id){
  $params = array($history_id);
  $sql = "
  SELECT
    i.name,
    hd.price,
    hd.amount,
    hd.price * hd.amount as sub_total_price
  FROM
    history_dateils as hd
  INNER JOIN
    items as i
  ON
    hd.item_id = i.item_id
  INNER JOIN
    histories as h
  ON
    h.history_id = hd.history_id
  WHERE
    h.history_id = ?
  ";
  return fetch_all_query($db, $sql, $params);
}

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

function insert_item($db, $name, $price, $stock, $filename, $status){
  $status_value = PERMITTED_ITEM_STATUSES[$status];
  $params = array($name, $price, $stock, $filename, $status_value);
  $sql = "
    INSERT INTO
      items(
        name,
        price,
        stock,
        image,
        status
      )
    VALUES(?, ?, ?, ?, ?)
  ";

  return execute_query($db, $sql, $params);
}

function update_item_status($db, $item_id, $status){
  $params = array($status, $item_id);
  $sql = "
    UPDATE
      items
    SET
      status = ?
    WHERE
      item_id = ?
    LIMIT 1
  ";

  return execute_query($db, $sql, $params);
}

function update_item_stock($db, $item_id, $stock){
  $params = array($stock, $item_id);
  $sql = "
    UPDATE
      items
    SET
      stock = ?
    WHERE
      item_id = ?
    LIMIT 1
  ";

  return execute_query($db, $sql, $params);
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

function delete_item($db, $item_id){
  $params = array($item_id);
  $sql = "
    DELETE FROM
      items
    WHERE
      item_id = ?
    LIMIT 1
  ";

  return execute_query($db, $sql, $params);
}

function insert_history($db, $user_id){
  $params = array($user_id);
  $sql = "
  INSERT INTO
  histories(
    user_id,
    create_datetime
    )
  VALUES(?, now())
  ";

  return execute_query($db, $sql, $params);
}

function insert_history_dateils($db, $history_id, $item_id, $amount, $price){
  $params = array($history_id, $item_id, $amount, $price);
  $sql = "
  INSERT INTO
  history_dateils(
    history_id,
    item_id,
    amount,
    price,
    create_datetime
    )
    VALUES(?, ?, ?, ?, now())
  ";

  return execute_query($db, $sql, $params);
}
// 非DB

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