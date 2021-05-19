<?php

function get_db_connect(){//データベースに接続する関数
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

function fetch_query($db, $sql, $params = array()){//select文でデータベースから選んでくる関数
  try{//$//$paramsはクエリを実行して実行結果を配列で取得したいとき、入れてあげるために準備。空っぽ状態で準備してるから、もし取得せんくても空でおいてるだけやから問題なっしぶる
    $statement = $db->prepare($sql);//データベースに$sqlを命令する準備して、$statementっていうあだ名つける
    $statement->execute($params);//$sqlの命令を実行する。その時、プレースホルダーがあるならは$paramsに連想配列でぶちこまれる
    return $statement->fetch();//該当するデータを1行だけ取得したでって返す。エラーじゃなかったらここで処理ストップ
  }catch(PDOException $e){//あら残念エラーやったら
    set_error('データ取得に失敗しました。');//「エラーかましてきたらどうすんの？関数（function.php内）」使って、セッション箱に入れる
  }
  return false;//処理やめぴ
}

function fetch_all_query($db, $sql, $params = array()){//select文でデータベースからぜーんぶ選んでくる関数
  try{//$//$paramsはクエリを実行して実行結果を配列で取得したいとき、入れてあげるために準備。空っぽ状態で準備してるから、もし取得せんくても空でおいてるだけやから問題なっしぶる
    $statement = $db->prepare($sql);//データベースに$sqlを命令する準備して、$statementっていうあだ名つける
    $statement->execute($params);//$sqlの命令を実行する。その時、プレースホルダーがあるなら$paramsに連想配列でぶちこまれる
    return $statement->fetchAll();//該当するデータを全部配列にして返す。エラーじゃなかったらここで処理ストップ
  }catch(PDOException $e){//あら残念エラーやったら
    set_error('データ取得に失敗しました。');//「エラーかましてきたらどうすんの？関数（function.php内）」使って、セッション箱に入れる
  }
  return false;//処理やめぴ
}

function execute_query($db, $sql, $params = array()){//insert文とupdate文でデータベースを書き込みするで関数
  try{//$paramsはクエリを実行して実行結果を配列で取得したいとき、入れてあげるために準備。空っぽ状態で準備してるから、もし取得せんくても空でおいてるだけやから問題なっしぶる
    $statement = $db->prepare($sql);//データベースに$sqlを命令する準備して、$statementっていうあだ名つける
    return $statement->execute($params);//$sqlの命令を実行する。これが戻り値。その時、プレースホルダーがあるならは$paramsに連想配列でぶちこまれる
  }catch(PDOException $e){//あら残念エラーやったら
    set_error('更新に失敗しました。');//「エラーかましてきたらどうすんの？関数（function.php内）」使って、セッション箱に入れる
  }
  return false;//処理やめぴ
}




