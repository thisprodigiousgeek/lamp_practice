<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

/*
usersテーブルuser_idで抽出しSELECTしたものをfetchする
*/
function get_user($db, $user_id){
  $sql = "
    SELECT
      user_id, 
      name,
      password,
      type
    FROM
      users
    WHERE
      user_id = {$user_id}
    LIMIT 1
  ";

  return fetch_query($db, $sql);
}

/*
$nameで抽出、usersテーブルからSELECTの内容を選択
*/
function get_user_by_name($db, $name){
  $sql = "
    SELECT
      user_id, 
      name,
      password,
      type
    FROM
      users
    WHERE
      name = '{$name}'
    LIMIT 1
  ";

  return fetch_query($db, $sql);
}

/*
$userに名前とパスワードとuser_idとtypeを代入
$userで情報取得できずfalse　or　引数として入れたパスワードと$userに代入したパスワードが一致しなければ、falseを返す
セッション配列にuser_idを記録
*/
function login_as($db, $name, $password){
  $user = get_user_by_name($db, $name);
  if($user === false || $user['password'] !== $password){
    return false;
  }
  set_session('user_id', $user['user_id']);
  return $user;
}

/*
$_SESSION配列にuser_idが入っているかを確認
これはログインしているかを確認？
user_id,type,name,passwordを取得してreturn
*/
function get_login_user($db){
  $login_user_id = get_session('user_id');

  return get_user($db, $login_user_id);
}

/*
データベース接続情報、名前、パスワード、パスワード確認を入力

*/
function regist_user($db, $name, $password, $password_confirmation) {
  if( is_valid_user($name, $password, $password_confirmation) === false){
    return false;
  }
  
  return insert_user($db, $name, $password);
}

/*
テーブルにtypeが存在して　USER_TYPE_ADMIN(1)が型も含めて=ならtrueを返す
true or falseをreturnする
ADMINかその他を判別する
*/
function is_admin($user){
  return $user['type'] === USER_TYPE_ADMIN;
}

/*
名前、パスワード、確認パスワードを引数として入力

*/
function is_valid_user($name, $password, $password_confirmation){
  // 短絡評価を避けるため一旦代入。
  $is_valid_user_name = is_valid_user_name($name);
  $is_valid_password = is_valid_password($password, $password_confirmation);
  return $is_valid_user_name && $is_valid_password ;
}

/*
$is_validにtrueを入れる
$nameがUSER_NAME_LENGTH_MIN以上USER_NAME_LENGTH_MAX以下ならture
falseならエラーメッセージ

*/
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

/*
$passwordの文字数を確認してfalseならエラーメッセージを表示
$passwordに正規表現　
falseならエラーメッセージを表示
もしパスワードと確認用パスワードが一致しなければエラーメッセージ
*/
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

/*
データベース接続情報と名前とパスワードを入力

*/
function insert_user($db, $name, $password){
  $sql = "
    INSERT INTO
      users(name, password)
    VALUES ('{$name}', '{$password}');
  ";

  return execute_query($db, $sql);
}

