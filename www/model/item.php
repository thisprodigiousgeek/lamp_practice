<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// DB利用

function get_item($db, $item_id){//itemsテーブルからデータ1行取ってくる
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
    ";//$item_idのとこは何かしらのアイテムのIDが入る。その時のお楽しみ
  $params = array($item_id);
  return fetch_query($db, $sql, $params);//「select文でデータベースから選んでくる関数（function.php）」が実行される。
}//エラーちゃうかったら。該当するデータを1行だけ取得した配列が返ってくる

function get_items($db, $is_open = false){//アイテムを取得する関数
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
    ';//ステータスが１のやつだけ
  }

  return fetch_all_query($db, $sql);//「select文でデータベースからぜーんぶ選んでくる関数(function.php)」
}//エラーちゃうかったら。該当するデータをぜーんぶ取得した配列が返ってくる

function get_all_items($db){//ぜんぶのアイテムほしいときの関数
  return get_items($db);//get_items全部もろたで
}

function get_open_items($db){//ぜんぶのアイテム見たいときの関数
  return get_items($db, true);//get_itemsで全部もろたで
}

function regist_item($db, $name, $price, $stock, $status, $image){//
  $filename = get_upload_filename($image);//ファイルをアップロードできたらランダムな名前付けられてるからそれ自体にあだ名つける
  if(validate_item($name, $price, $stock, $filename, $status) === false){//バリデに失敗したら
    return false;//処理やめぴ
  }
  return regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename);//すぐ下の関数をぶちかます
}

function regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename){//アイテムがインサートと保存どっちもちゃんとしいや関数
  $db->beginTransaction();//トランザクション開始
  if(insert_item($db, $name, $price, $stock, $filename, $status) 
    && save_image($image, $filename)){//アイテムをテーブルにインサートできて、かつ、画像の保存もできたら
    $db->commit();//コミットさん
    return true;//できたな、おめ
  }
  $db->rollback();//振り出しに戻る
  return false;//処理やめぴ
}//

function insert_item($db, $name, $price, $stock, $filename, $status){//itemsテーブルにインサートする関数
  $status_value = PERMITTED_ITEM_STATUSES[$status];//PERMITTED_ITEM_STATUSESは１か０で$statusはお楽しみ。$status_valueっていうあだ名つける
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
    ";//
  $params = array($name, $price, $stock, $filename, $status_value);
  return execute_query($db, $sql, $params);//実行してインサート完了
}

function update_item_status($db, $item_id, $status){//ステータスを変更する関数
  // try{
    $sql = "
      UPDATE
        items
      SET
        status = ?
      WHERE
        item_id = ?
      LIMIT 1
    ";//１行だけやで
  $params = array($status, $item_id);
  return execute_query($db, $sql,$params);//実行してアプデ完了
}

function update_item_stock($db, $item_id, $stock){//ストックを更新する関数
    $sql = "
      UPDATE
        items
      SET
        stock = ?
      WHERE
        item_id = ?
      LIMIT 1
    ";//１行だけやで
  $params = array($stock, $item_id);
  return execute_query($db, $sql, $params);//実行してアプデ完了
}

function destroy_item($db, $item_id){//item_id次第ではぶち壊す関数
  $item = get_item($db, $item_id);//指定された$item_idの行を取得
  if($item === false){//取得できひんかったら
    return false;//処理やめぴ
  }
  $db->beginTransaction();//よっしゃ！トランザクション開始！！
  if(delete_item($db, $item['item_id'])//指定されたitem_idの行を消す！と同時に画像のデータも消す！
    && delete_image($item['image'])){//
    $db->commit();//コミットちゃん
    return true;//よくできました
  }
  $db->rollback();//無理やったら最初に戻る
  return false;//処理やめぴ
}//

function delete_item($db, $item_id){//指定されたitem_idのを１行消す関数
    $sql = "
      DELETE FROM
        items
      WHERE
        item_id = ?
      LIMIT 1
    ";//
  $params = array($item_id);
  return execute_query($db, $sql, $params);//execute_queryでデリート文実行
}


// 非DB

function is_open($item){//ステータスが1のやつだけ開く関数
  return $item['status'] === 1;//1だけ返すねん
}

function validate_item($name, $price, $stock, $filename, $status){//合格した子たちにあだ名つける関数
  $is_valid_item_name = is_valid_item_name($name);//以下、ぜーんぶそれぞれのあだ名
  $is_valid_item_price = is_valid_item_price($price);//
  $is_valid_item_stock = is_valid_item_stock($stock);//
  $is_valid_item_filename = is_valid_item_filename($filename);//
  $is_valid_item_status = is_valid_item_status($status);//

  return $is_valid_item_name//以下一気に返す
    && $is_valid_item_price//
    && $is_valid_item_stock//
    && $is_valid_item_filename//
    && $is_valid_item_status;//
}

function is_valid_item_name($name){//名前のバリデ
  $is_valid = true;//ある？…あるで！
  if(is_valid_length($name, ITEM_NAME_LENGTH_MIN, ITEM_NAME_LENGTH_MAX) === false){//バリデかまして、falseやったら
    set_error('商品名は'. ITEM_NAME_LENGTH_MIN . '文字以上、' . ITEM_NAME_LENGTH_MAX . '文字以内にしてください。');//セッション箱にエラーメッセージｂちこむ
    $is_valid = false;//ないやん
  }
  return $is_valid;//あんた合格！
}

function is_valid_item_price($price){//価格のバリデ
  $is_valid = true;//ある？…あるで！
  if(is_positive_integer($price) === false){//バリデかまして、falseやったら
    set_error('価格は0以上の整数で入力してください。');//セッション箱にエラーメッセージｂちこむ
    $is_valid = false;//ないやん
  }//
  return $is_valid;//あんた合格！
}//

function is_valid_item_stock($stock){//ストックのバリデ
  $is_valid = true;//ある？…あるで！
  if(is_positive_integer($stock) === false){//バリデかまして、falseやったら
    set_error('在庫数は0以上の整数で入力してください。');//セッション箱にエラーメッセージｂちこむ
    $is_valid = false;//ないやん
  }//
  return $is_valid;//あんた合格！
}

function is_valid_item_filename($filename){//ファイル名のバリデ
  $is_valid = true;//ある？…あるで！
  if($filename === ''){//空やったら
    $is_valid = false;//ないやん
  }
  return $is_valid;//あんた合格！
}

function is_valid_item_status($status){//ステータスのバリデ
  $is_valid = true;//ある？…あるで！
  if(isset(PERMITTED_ITEM_STATUSES[$status]) === false){//バリデかまして、falseやったら
    $is_valid = false;//ないやん
  }
  return $is_valid;//あんた合格！
}