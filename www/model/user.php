<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

//$user_idの値とusersテーブルのuser_idカラムが一致するユーザーのデータを取得
function get_user($db, $user_id){

  //sql文作成
  $sql = "
    SELECT
      user_id, 
      name,
      password,
      type
    FROM
      users
    WHERE
      user_id = :user_id
    LIMIT 1
  ";

  //fetch_queryにsql文を返して実行
  return fetch_query($db, $sql,array(':user_id'=>$user_id));
}

//同じ名前のユーザーがいないか確認?
function get_user_by_name($db, $name){

  //sql文を作成
  $sql = "
    SELECT
      user_id, 
      name,
      password,
      type
    FROM
      users
    WHERE
      name = :name
    LIMIT 1
  ";
  
  //sql文をfetch_queryに返して実行
  return fetch_query($db, $sql,array(':name'=>$name));
}

//
function login_as($db, $name, $password){
  $user = get_user_by_name($db, $name);

  //わからない
  if($user === false || $user['password'] !== $password){
    return false;
  }
  set_session('user_id', $user['user_id']);
  return $user;
}

//ログイン中のユーザーのセッション変数'user_id'を取得
function get_login_user($db){
  $login_user_id = get_session('user_id');

  return get_user($db, $login_user_id);
}

//新規登録で入力が正しくされているか確認?
function regist_user($db, $name, $password, $password_confirmation) {
  if( is_valid_user($name, $password, $password_confirmation) === false){
    return false;
  }
  
  return insert_user($db, $name, $password);
}

//ログインユーザーが管理者の場合?
function is_admin($user){
  return $user['type'] === USER_TYPE_ADMIN;
}

//新規登録で入力された内容を$is_valid_user_nameと$is_valid_passwordにそれぞれ代入
function is_valid_user($name, $password, $password_confirmation){
  // 短絡評価を避けるため一旦代入。
  $is_valid_user_name = is_valid_user_name($name);
  $is_valid_password = is_valid_password($password, $password_confirmation);
  return $is_valid_user_name && $is_valid_password ;
}

//新規登録で入力されたユーザーネームの入力確認
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

//新規登録で入力されたパスワードの入力確認
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

//正当な入力がされていた場合新規登録
function insert_user($db, $name, $password){

  //sql文作成
  $sql = "
    INSERT INTO
      users(name, password)
    VALUES (:name, :password);
  ";
  
  //sql文をexecute_queryに返して実行
  return execute_query($db, $sql,array(':name'=>$name,':password'=>$password));
}

