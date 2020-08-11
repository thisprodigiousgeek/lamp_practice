<?php

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
//配列として一行だけ取得する
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
//配列としてすべてのデータを取得
function fetch_all_query($db, $sql, $params = array()){
  try{
    $statement = $db->prepare($sql);
    $statement->execute($params);
    $data = $statement->fetchAll();
    
    //配列の一つ一つにh関数の処理をする
    //$dataとはどんな構造の配列か
      //配列の中に配列がある
    //一次元配列と二次元配列の違いは何か
      //一次元配列は一つのボックスに中にいくつか値が入っている
      //二次元配列は複数のボックスがありそれぞれにいくつかのあたいが入っている
    //配列の一個一個を取り出すにはどういう構文を使うか
      //foreachで一つ一つ取り出す
    //$dataの一個一個の要素をh関数で処理するにはどうしたらよいか
      //$dataをforeachで取り出して取り出したボックスからforeachで取り出す
    //returnとは何か　この関数では何をreturnするか
    // functionで定義された関数が使われたときに最終的に返される値
    

    foreach($data as &$hvalues){
      foreach($hvalues as &$hvalue){
        if(is_numeric($hvalue) === false){
          $hvalue = h($hvalue);
        }
      }
      unset($hvalue);
    }
    unset($hvalues);
    return $data;
  }catch(PDOException $e){
    set_error('データ取得に失敗しました。');
  }
  return false;
}

//クエリの実行
function execute_query($db, $sql, $params = array()){
  try{
    $statement = $db->prepare($sql);
    return $statement->execute($params);
  }catch(PDOException $e){
    set_error('更新に失敗しました。' . $sql);
  }
  return false;
}