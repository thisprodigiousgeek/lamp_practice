<?php
// MODEL_RATHとはモデルを定義しているディレクトリへの道筋
// ここではモデルのfunctions.phpを読み込む
require_once MODEL_PATH . 'functions.php';
// ここではモデルのdb.phpを読み込む
require_once MODEL_PATH . 'db.php';

// この関数はどのユーザーIDでログインしているのかを確認
function get_user($db, $user_id){
// SELECT文でusersのテーブルからuser_id,name,password,type,を検索
// WHEREでuser_idを指定し、SQLインジェクション対策で直接変数をいれずに？にする
  $sql = "
    SELECT
      user_id, 
      name,
      password,
      type
    FROM
      users
    WHERE
      user_id = ?
    LIMIT 1
  ";
  $stmt = $db->prepare($sql);

  $stmt->bindValue(1, $user_id, PDO::PARAM_INT);

  $stmt->execute();

  return fetch_query($db, $sql);
}
// この関数はどのユーザー名でログインしているのかを確認
function get_user_by_name($db, $name){
// SELECT文でusersのテーブルからuser_id,name,password,type,を検索
// WHEREでnameを指定し、SQLインジェクション対策で直接変数をいれずに？にする
  $sql = "
    SELECT
      user_id, 
      name,
      password,
      type
    FROM
      users
    WHERE
      name = ?
    LIMIT 1
  ";
  //SQL文を実行の準備(prepare)
  $stmt = $db->prepare($sql);
  // プレースホルダでSQLインジェクション対策
  $stmt->bindValue(1, $name, PDO::PARAM_STR);
  // SQL文を実行する
  $stmt->execute();

  return fetch_query($db, $sql);
}
// この関数はログインする時、ユーザー名とパスワードを確認している
// ユーザー名とパスワードのどちらかでも合っていない場合falseを返す
function login_as($db, $name, $password){
  $user = get_user_by_name($db, $name);
  if($user === false || $user['password'] !== $password){
    return false;
  }
// セッションにユーザーIDとユーザー名を置いておくことで重複しないIDを作る
// $userに返す
  set_session('user_id', $user['user_id']);
  return $user;
}
// この関数はユーザーがログインすると時,ログインするユーザーIDが重複しているものか確認
// get_userを返す
function get_login_user($db){
  $login_user_id = get_session('user_id');

  return get_user($db, $login_user_id);
}
//ユーザーが登録する時、ユーザー名やパスワードが有効な文字で登録しているかを確認
//有効ではない文字で登録した場合falseを返し、有効な場合insert_userに返す
function regist_user($db, $name, $password, $password_confirmation) {
  if( is_valid_user($name, $password, $password_confirmation) === false){
    return false;
  }
  
  return insert_user($db, $name, $password);
}

function is_admin($user){
  return $user['type'] === USER_TYPE_ADMIN;
}

function is_valid_user($name, $password, $password_confirmation){
  // 短絡評価を避けるため一旦代入。
  $is_valid_user_name = is_valid_user_name($name);
  $is_valid_password = is_valid_password($password, $password_confirmation);
  return $is_valid_user_name && $is_valid_password ;
}

function is_valid_user_name($name) {
  $is_valid = true;
  if(is_valid_length($name, USER_NAME_LENGTH_MIN, USER_NAME_LENGTH_MAX) === false){
    set_error('ユーザー名は'. USER_NAME_LENGTH_MIN . '文字以上、' . USER_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  if(is_alphanumeric($name) === false){
    set_error('ユーザー名は半角英数字で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

function is_valid_password($password, $password_confirmation){
  $is_valid = true;
  if(is_valid_length($password, USER_PASSWORD_LENGTH_MIN, USER_PASSWORD_LENGTH_MAX) === false){
    set_error('パスワードは'. USER_PASSWORD_LENGTH_MIN . '文字以上、' . USER_PASSWORD_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  if(is_alphanumeric($password) === false){
    set_error('パスワードは半角英数字で入力してください。');
    $is_valid = false;
  }
  if($password !== $password_confirmation){
    set_error('パスワードがパスワード(確認用)と一致しません。');
    $is_valid = false;
  }
  return $is_valid;
}

function insert_user($db, $name, $password){
  $sql = "
    INSERT INTO
      users(name, password)
    VALUES (?, ?);
  ";
  $stmt = $db->prepare($sql);

  $stmt->bindValue(1, $name, PDO::PARAM_STR);
  $stmt->bindValue(2, $password, PDO::PARAM_STR);

  $stmt->execute();

  return execute_query($db, $sql);
}

