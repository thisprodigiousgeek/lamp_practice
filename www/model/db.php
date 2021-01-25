<?php
//DBに接続
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
  return $dbh;
}

//DBのSQLを実行し１行のみレコード取得
function fetch_query($db, $sql, $params = array()){
  try{
    //SQL文を実行する準備
    $statement = $db->prepare($sql);
    //SQLを実行
    $statement->execute($params);
    //１行のみレコード取得
    return $statement->fetch();
  }catch(PDOException $e){
    //セッション変数にエラー表示
    set_error('データ取得に失敗しました。');
  }
  return false;
}

//DBのSQLを実行し全ての結果行レコード取得
function fetch_all_query($db, $sql, $params = array()){
  try{
    //SQL文を実行する準備
    $statement = $db->prepare($sql);
    //SQLを実行
    $statement->execute($params);
    //全ての結果行のレコードの取得
    return $statement->fetchAll();
  }catch(PDOException $e){
    //セッション変数にエラー表示
    set_error('データ取得に失敗しました。');
  }
  return false;
}

//DBのSQLを実行するのみ
function execute_query($db, $sql, $params = array()){
  try{
    //SQL文を実行する準備
    $statement = $db->prepare($sql);
    //SQLを実行
    return $statement->execute($params);
  }catch(PDOException $e){
    set_error('更新に失敗しました。');
  }
  return false;
}