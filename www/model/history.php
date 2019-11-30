<?php
function insert_user_history($db, $user_id){
    $sql = 'INSERT INTO histories (user_id) VALUES(:user_id)';
    $params = array(':user_id' => $user_id);
    
    return execute_query($db, $sql, $params);
}