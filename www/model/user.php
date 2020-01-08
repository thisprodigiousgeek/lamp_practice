<?php
require_once 'functions.php'; //function.phpファイルを読み込み
require_once 'db.php';//db.phpファイルを読み込み
//usersテーブルから、ユーザーIDをもとにユーザー情報を取ってくる
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
      user_id = ?
    LIMIT 1
  ";

  return fetch_query($db, $sql, array($user_id));
}
//usersテーブルから、ユーザー名をもとにユーザー情報を取ってくる
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
      name = ?
    LIMIT 1
  ";

  return fetch_query($db, $sql, array($name));
}
//ユーザー情報の取得ができなかった時、パスワードが間違っていた時を除き＄_SESSIONにユーザーIDを格納し、返り値としてユーザー情報を含んだ配列を返す
function login_as($db, $name, $password){
  $user = get_user_by_name($db, $name);
  if($user === false || $user['password'] !== $password){
    return false;
  }
  set_session('user_id', $user['user_id']);
  return $user;
}
//＄_SESSIONに'user_id'が格納されている時そのIDを定数に代入し、そのIDをもとにユーザー情報を取ってきて返り値として返す（ログインしているユーザーの情報を返り値として返す）
function get_login_user($db){
  $login_user_id = get_session('user_id');

  return get_user($db, $login_user_id);
}
//ユーザー名、パスワードが正しい形式で入力されているときに、ユーザー登録をする
function regist_user($db, $name, $password, $password_confirmation) {
  if( is_valid_user($name, $password, $password_confirmation) === false){
    return false;
  }
  
  return insert_user($db, $name, $password);
}

function is_admin($user){
  return $user['type'] === USER_TYPE_ADMIN;
}
//ユーザー名、パスワード両方が正しい形式かを確認
function is_valid_user($name, $password, $password_confirmation){
  // 短絡評価を避けるため一旦代入。
  $is_valid_user_name = is_valid_user_name($name);
  $is_valid_password = is_valid_password($password, $password_confirmation);
  return $is_valid_user_name && $is_valid_password ;
}
//ユーザー名が正しく入力されているか確認（文字数、半角英数字かのチェック）し、正しければtrue、間違っていればfalseを返り値として返す
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
//パスワードが正しく入力されているか確認 (文字数、半角英数字)し、正しければtrue、間違っていればfalseを返り値として返す
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
//新規ユーザー登録
function insert_user($db, $name, $password){
  $sql = "
    INSERT INTO
      users(name, password)
    VALUES (?, ?);
  ";

  return execute_query($db, $sql,array($name, $password));
}

