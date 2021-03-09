<?php
// 関数ファイルの読み込み
require_once MODEL_PATH . 'functions.php';
// データ取得ファイルの読み込み
require_once MODEL_PATH . 'db.php';

//ユーザーID取得
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
  ";//LIMIT:取得するデータの行数の上限設定
//var_dump($sql);
  //SQL文の準備、実行、レコード取得
  return fetch_query($db, $sql);
}

//ユーザー名の取得
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

  //SQL文の準備、実行、レコード取得
  return fetch_query($db, $sql);
}

//ログイン処理
function login_as($db, $name, $password){
  //ユーザー名の取得
  $user = get_user_by_name($db, $name);
  //ユーザー名に誤りがある又はパスワードが異なる場合、false
  if($user === false || $user['password'] !== $password){
    return false;
  }
  //falseでなかったらユーザーID取得
  set_session('user_id', $user['user_id']);
  return $user;
}

//ログイン済み処理
function get_login_user($db){
  //ログイン済みID定義
  $login_user_id = get_session('user_id');

  //ユーザーID取得
  return get_user($db, $login_user_id);
}

//登録ユーザー確認
function regist_user($db, $name, $password, $password_confirmation) {
  //有効なユーザーでなければ、false
  if( is_valid_user($name, $password, $password_confirmation) === false){
    return false;
  }
  //有効であればユーザー追加
  return insert_user($db, $name, $password);
}

//
function is_admin($user){
  return $user['type'] === USER_TYPE_ADMIN;
}

//有効なユーザー情報取得
function is_valid_user($name, $password, $password_confirmation){
  // 短絡評価を避けるため一旦代入。
  $is_valid_user_name = is_valid_user_name($name);
  $is_valid_password = is_valid_password($password, $password_confirmation);
  return $is_valid_user_name && $is_valid_password ;
}

//有効なユーザー名のためのエラーチェック
function is_valid_user_name($name) {
  //有効
  $is_valid = true;

  //ユーザー名が有効な長さでない場合
  if(is_valid_length($name, USER_NAME_LENGTH_MIN, USER_NAME_LENGTH_MAX) === false){
    set_error('ユーザー名は'. USER_NAME_LENGTH_MIN . '文字以上、' . USER_NAME_LENGTH_MAX . '文字以内にしてください。');
    //falseを返す
    $is_valid = false;
  }
  //ユーザー名が半角英数字でない場合
  if(is_alphanumeric($name) === false){
    set_error('ユーザー名は半角英数字で入力してください。');
    //falseを返す
    $is_valid = false;
  }
  return $is_valid;
}

//有効なパスワードのためのエラーチェック
function is_valid_password($password, $password_confirmation){
  //有効
  $is_valid = true;

  //パスワードが有効な長さでない場合
  if(is_valid_length($password, USER_PASSWORD_LENGTH_MIN, USER_PASSWORD_LENGTH_MAX) === false){
    set_error('パスワードは'. USER_PASSWORD_LENGTH_MIN . '文字以上、' . USER_PASSWORD_LENGTH_MAX . '文字以内にしてください。');
    //falseを返す
    $is_valid = false;
  }
  //パスワードが半角英数字でない場合
  if(is_alphanumeric($password) === false){
    set_error('パスワードは半角英数字で入力してください。');
    //falseを返す
    $is_valid = false;
  }
  //パスワードと確認パスワードが一致しない場合
  if($password !== $password_confirmation){
    set_error('パスワードがパスワード(確認用)と一致しません。');
    //falseを返す
    $is_valid = false;
  }
  return $is_valid;
}

//ユーザー名、パスワードの追加
function insert_user($db, $name, $password){
  $sql = "
    INSERT INTO
      users(name, password)
    VALUES ('{$name}', '{$password}');
  ";

  //SQL文の準備から実行
  return execute_query($db, $sql);
}

