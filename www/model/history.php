<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';


function order_history($db,$user_id,$total_price){
    $sql = "
    INSERT INTO
      history(
        user_id,
        total_price
      )
    VALUES(?,?);
  ";

  execute_query($db, $sql, array($user_id, $total_price));
  return $db->lastInsertId();
}

function select_history($db,$user_id){
  $sql="
  SELECT
    *
  FROM
    history
  WHERE
    user_id = ?
  ";
$test = fetch_all_query($db, $sql , array($user_id));
return $test;

}
