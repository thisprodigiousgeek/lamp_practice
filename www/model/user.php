<?php
//汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
//dbデータに関するファイル読み込み
require_once MODEL_PATH . 'db.php';

//DBのusersテーブルからuser_idのデータを参照
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
  //DBのSQLを実行し１行のみレコード取得
  return fetch_query($db, $sql);
}

//DBのusersテーブルからnameのデータを取得
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
  //DBのSQLを実行し１行のみレコード取得
  return fetch_query($db, $sql);
}

//ユーザー登録されてるか確認
function login_as($db, $name, $password){
  //DBからnameのデータを取得
  $user = get_user_by_name($db, $name);
  //$userが無い又は$userのパスワードが一致しない場合
  if($user === false || $user['password'] !== $password){
    return false;
  }
  //セッション変数に$user[user_id]を代入
  set_session('user_id', $user['user_id']);
  return $user;
}

//セッション変数から$login_user_idを取得しuserのデータ取得
function get_login_user($db){
  $login_user_id = get_session('user_id');
  //DBのusersテーブルからlogin_user_idのデータを参照
  return get_user($db, $login_user_id);
}

//ユーザー登録
function regist_user($db, $name, $password, $password_confirmation) {
  //ユーザー名、パスワードともにエラーチェックでNGがあった場合
  if( is_valid_user($name, $password, $password_confirmation) === false){
    return false;
  }
  //DBにユーザーごとのテーブルを作成
  return insert_user($db, $name, $password);
}

//$userが管理者である
function is_admin($user){
  return $user['type'] === USER_TYPE_ADMIN;
}

//有効なユーザーを代入
function is_valid_user($name, $password, $password_confirmation){
  // 短絡評価を避けるため一旦代入。
  $is_valid_user_name = is_valid_user_name($name);
  $is_valid_password = is_valid_password($password, $password_confirmation);
  return $is_valid_user_name && $is_valid_password ;
}

//ユーザー名のエラーチェック
function is_valid_user_name($name) {
  $is_valid = true;
  //文字の長さチェック
  if(is_valid_length($name, USER_NAME_LENGTH_MIN, USER_NAME_LENGTH_MAX) === false){
    //NGの場合、セッション変数にエラー表示
    set_error('ユーザー名は'. USER_NAME_LENGTH_MIN . '文字以上、' . USER_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  //文字が半角英数字であるかチェック
  if(is_alphanumeric($name) === false){
    //NGの場合、セッション変数にエラー表示
    set_error('ユーザー名は半角英数字で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

//パスワードのエラーチェック
function is_valid_password($password, $password_confirmation){
  $is_valid = true;
  //文字の長さチェック
  if(is_valid_length($password, USER_PASSWORD_LENGTH_MIN, USER_PASSWORD_LENGTH_MAX) === false){
    //NGの場合、セッション変数にエラー表示
    set_error('パスワードは'. USER_PASSWORD_LENGTH_MIN . '文字以上、' . USER_PASSWORD_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  //文字が半角英数字であるかチェック
  if(is_alphanumeric($password) === false){
    //NGの場合、セッション変数にエラー表示
    set_error('パスワードは半角英数字で入力してください。');
    $is_valid = false;
  }
  //パスワードとパスワード（確認用）が一致しているかチェック
  if($password !== $password_confirmation){
    //NGの場合、セッション変数にエラー表示
    set_error('パスワードがパスワード(確認用)と一致しません。');
    $is_valid = false;
  }
  return $is_valid;
}

//DBにユーザーごとのテーブルを作成
function insert_user($db, $name, $password){
  $sql = "
    INSERT INTO
      users(name, password)
    VALUES ('{$name}', '{$password}');
  ";
  //SQLを実行
  return execute_query($db, $sql);
}

