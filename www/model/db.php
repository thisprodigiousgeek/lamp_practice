<?php

function get_db_connect() {
 
  // コネクション取得
  $link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  if ($link === false) {
      die('error: ' . mysqli_connect_error());
  }

  // 文字コードセット
  mysqli_set_charset($link, DB_CHARSET);

  return $link;
}

function fetch_query($db, $sql, $params = array()){
  if ($result = mysqli_query($db, $sql)) {
      $row = mysqli_fetch_assoc($result);
      mysqli_free_result($result);
      if(isset($row) === false){
        return false;
      }
      return $row;
  }

  return false;
}

function fetch_all_query($db, $sql, $params = array()){
  $data = [];
  if ($result = mysqli_query($db, $sql)) {
      while ($row = mysqli_fetch_assoc($result)) {
          $data[] = $row;
      }
      mysqli_free_result($result);
  }
  return $data;
}

function execute_query($db, $sql, $params = array()){
  return mysqli_query($db, $sql);
}