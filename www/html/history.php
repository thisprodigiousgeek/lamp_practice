<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';
header('X-Frame-Options: DENY');
session_start();

function order_history($db,$user_id){
    $sql = "
    INSERT INTO
      history(
        user_id
      )
    VALUES(?);
  ";

  return execute_query($db, $sql, array($user_id));
}