<?php
　// この関数はDBに接続する
function get_db_connect(){
  // DSN(Data Source Name)は、接続先データベースの種類やサーバー名を記述する文字列
  // MySQLに接続する場合、「mysql:dbname='データベース名';host='サーバー名'」
  // MySQL用のDSN文字列
  $dsn = 'mysql:dbname='. DB_NAME .';host='. DB_HOST .';charset='.DB_CHARSET;
  //try~catch（例外処理）
  //エラーが発生した場合、現在の処理を中断して別の処理を行うことができる
  try {
    // データベースに接続
    // PDO(PHP,Date,Objects)の略
    $dbh = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
    //setAttribute()メソッドはデータベース接続時の設定やSQL実行時の動作などの意味
    // エラーモードの設定
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // プリペアドステートメントとは、SQLを実行前に色々と準備して実行すること
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // PDOクラスのコンストラクタ（PDO::__construct())は指定されたデータベースへの接続に失敗した場合
    // 例外として、PDOExceptionを投げる。
    // try文の実行途中で中断し、catch文に移り、エラーメッセージを表示する。
  } catch (PDOException $e) {
    exit('接続できませんでした。理由：'.$e->getMessage() );
  }
  // データベースに返す
  return $dbh;
}
  // この関数はデータベースから指定したデータを取ってくる
function fetch_query($db, $sql, $params = array()){
  // エラーが発生した場合、現在の処理を中断して別の処理を行うことができる
  try{
  // prepareでSQL文を実行準備
    $statement = $db->prepare($sql);
  // SQLを実行
    $statement->execute($params);
  // 指定したものを取ってくる
    return $statement->fetch();
  // 例外が発生したら、エラーを表示し、falseを返す
  }catch(PDOException $e){
    set_error('データ取得に失敗しました。');
  }
  return false;
}
　  // この関数はデータベースから複数のデータを取ってくる
function fetch_all_query($db, $sql, $params = array()){
　  // エラーが発生した場合、現在の処理を中断して別の処理を行うことができる
  try{
  　// prepareでSQL文を実行準備
    $statement = $db->prepare($sql);
    // SQLを実行
    $statement->execute($params);
    // 複数のデータを取ってくる
    return $statement->fetchAll();
  // 例外が発生したら、エラーを表示し、falseを返す
  }catch(PDOException $e){
    set_error('データ取得に失敗しました。');
  }
  return false;
}
// この関数はSQL文を実行する
function execute_query($db, $sql, $params = array()){
// エラーが発生した場合、現在の処理を中断して別の処理を行うことができる
  try{
  // prepareでSQL文を実行の準備
    $statement = $db->prepare($sql);
  // SQL文を実行
    return $statement->execute($params);
  // 例外が発生したら、エラーを表示し、falseを返す
  }catch(PDOException $e){
    set_error('更新に失敗しました。');
  }
  return false;
}