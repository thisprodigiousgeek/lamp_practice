<?php

// データベースに接続する関数
function get_db_connect(){
  // MySQL用DSN文字列
  $dsn = 'mysql:dbname='. DB_NAME .';host='. DB_HOST .';charset='.DB_CHARSET;
 
  try {
    $dbh = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    exit('接続できませんでした。理由：'.$e->getMessage() );
  }
  return $dbh;
}

function fetch_query($db, $sql, $params = array()){  
  try{
    $statement = $db->prepare($sql);
    $statement->execute($params); 
    return $statement->fetch();
  }catch(PDOException $e){
    set_error('データ取得に失敗しました。');
  }
  return false;
}

function fetch_all_query($db, $sql, $params = array()){
  try{
    $statement = $db->prepare($sql);
    $statement->execute($params);
    return $statement->fetchAll();
  }catch(PDOException $e){
    set_error('データ取得に失敗しました。');
  }
  return false;
}

function execute_query($db, $sql, $params = array()){
  try{
    $statement = $db->prepare($sql);
    return $statement->execute($params);
  }catch(PDOException $e){
    set_error('更新に失敗しました。');
    set_error($e->getMessage());
  }
  return false;
}