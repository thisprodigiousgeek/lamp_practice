<!-- <?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

function get_history($db, $user_id){
  $sql = "
    SELECT
      history.history_id,
      history.user_id,
      history.created
    FROM
      history
    JOIN
      users
    WHERE
      history.user_id = ?
  ";
  return fetch_all_query($db, $sql, array($user_id));
}
?> -->