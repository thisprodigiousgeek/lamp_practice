<?php
// 関数ファイルの読み込み
require_once 'functions.php';
// DBファイルの読み込み
require_once 'db.php';

// user_idからユーザー情報の取得
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

  // クエリ実行
  return fetch_query($db, $sql);
}

// nameからユーザー情報取得
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

  // クエリ実行処理
  return fetch_query($db, $sql);
}

// パスワードを照合して一致していればuser_idをセッションにセットする
function login_as($db, $name, $password){
  // nameからユーザー情報取得
  $user = get_user_by_name($db, $name);
  if($user === false || $user['password'] !== $password){
    return false;
  }
  // user_idをSSESION['user_id']にセット
  set_session('user_id', $user['user_id']);
  return $user;
}

// ログインユーザー情報の取得
function get_login_user($db){
  // セッションにuser_idがセットされていれば、変数に代入
  $login_user_id = get_session('user_id');
// ユーザー情報を返す
  return get_user($db, $login_user_id);
}


// ユーザー追加処理
function regist_user($db, $name, $password, $password_confirmation) {
  // ユーザー情報のバリデーション
  if( is_valid_user($name, $password, $password_confirmation) === false){
    return false;
  }
  // ユーザーの追加
  return insert_user($db, $name, $password);
}

// type=管理ユーザー(1)
function is_admin($user){
  return $user['type'] === USER_TYPE_ADMIN;
}

// ユーザー情報のバリデーション処理(エラーがなければtrue , エラーがあればfalse)
function is_valid_user($name, $password, $password_confirmation){
  // 短絡評価を避けるため一旦代入。

  // ユーザー名のバリデーション
  $is_valid_user_name = is_valid_user_name($name);
// パスワードのバリデーション
  $is_valid_password = is_valid_password($password, $password_confirmation);
  return $is_valid_user_name && $is_valid_password ;
}

// ユーザー名のバリデーション(trueかfalseを返す)
function is_valid_user_name($name) {
  $is_valid = true;
  // ユーザー名6文字以上100文字以下
  if(is_valid_length($name, USER_NAME_LENGTH_MIN, USER_NAME_LENGTH_MAX) === false){
    // 条件を満たしてない場合は、エラーメッセージをセッションにセット
    set_error('ユーザー名は'. USER_NAME_LENGTH_MIN . '文字以上、' . USER_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  // 条件を満たしてない場合は、エラーメッセージをセッションにセット
  if(is_alphanumeric($name) === false){
    set_error('ユーザー名は半角英数字で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

// パスワードのバリデーション(trueかfalseを返す)
function is_valid_password($password, $password_confirmation){
  $is_valid = true;
  // パスワード6文字以上100文字以下
  if(is_valid_length($password, USER_PASSWORD_LENGTH_MIN, USER_PASSWORD_LENGTH_MAX) === false){
    // 条件を満たしてない場合は、エラーメッセージをセッションにセット
    set_error('パスワードは'. USER_PASSWORD_LENGTH_MIN . '文字以上、' . USER_PASSWORD_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }

  // パスワードは半角英数字
  if(is_alphanumeric($password) === false){
    // 条件を満たしてない場合は、エラーメッセージをセッションにセット
    set_error('パスワードは半角英数字で入力してください。');
    $is_valid = false;
  }
  // パスワードとパスワード(確認用)一致確認
  if($password !== $password_confirmation){
    // 条件を満たしてない場合は、エラーメッセージをセッションにセット
    set_error('パスワードがパスワード(確認用)と一致しません。');
    $is_valid = false;
  }
  return $is_valid;
}

// ユーザーの追加処理
function insert_user($db, $name, $password){
  $sql = "
    INSERT INTO
      users(name, password)
    VALUES ('{$name}', '{$password}');
  ";

  // クエリの実行
  return execute_query($db, $sql);
}

