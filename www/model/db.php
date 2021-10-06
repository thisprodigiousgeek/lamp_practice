<?php

//データベース接続
function get_db_connect(){
  // MySQL用のDSN文字列
  $dsn = 'mysql:dbname='. DB_NAME .';host='. DB_HOST .';charset='.DB_CHARSET;
 
  try {
    // データベースに接続
    $dbh = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    exit('接続できませんでした。理由：'.$e->getMessage() );
  }

  //$dbhを返す
  return $dbh;
}

//sqlの結果を1行だけ取得する場合
function fetch_query($db, $sql, $params = array()){
  try{

    //sqlの実行準備
    $statement = $db->prepare($sql);

    //sqlの実行
    $statement->execute($params);

    //レコードの取得
    return $statement->fetch();
  }catch(PDOException $e){
    set_error('データ取得に失敗しました。');
  }
  return false;
}

//sqlの結果を全て取得する場合
function fetch_all_query($db, $sql, $params = array()){
  try{

    //sql実行準備
    $statement = $db->prepare($sql);

    //sql実行
    $statement->execute($params);

    //レコードの取得
    return $statement->fetchAll();
  }catch(PDOException $e){
    set_error('データ取得に失敗しました。');
  }
  return false;
}

//レコードは取得せずsqlの実行だけを行う場合(カートの削除、購入数変更など)
function execute_query($db, $sql, $params = array()){
  try{

    //sql実行準備
    $statement = $db->prepare($sql);

    //sql実行
    return $statement->execute($params);
  }catch(PDOException $e){
    set_error('更新に失敗しました。');
  }
  return false;
}