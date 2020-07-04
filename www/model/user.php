<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';
// ユーザ情報を取得
function get_user($db, $user_id){
  // SQL文作成
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
  // クエリを実行し、成功すればレコード1行（１次元）を返し、失敗すればfalseを返す
  return fetch_query($db, $sql);
}
// ユーザ名照会：ユーザ情報取得（1行のみ）
function get_user_by_name($db, $name){
  // SQL文作成
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
  // クエリを実行し、成功すればレコード1行（１次元）を返し、失敗すればfalseを返す
  return fetch_query($db, $sql);
}
// ログイン処理
function login_as($db, $name, $password){
  // ユーザ名からユーザ情報取得
  $user = get_user_by_name($db, $name);
  // ユーザ情報が存在しない、又はパスワードが違う場合falseを返す
  if($user === false || $user['password'] !== $password){
    return false;
  }
  // セッションに指定したキーと対応する値を入れる
  set_session('user_id', $user['user_id']);
  return $user;
}
// ログインユーザの情報取得
function get_login_user($db){
  // セッションからユーザID取得
  $login_user_id = get_session('user_id');
  // ユーザIDからユーザ情報取得
  return get_user($db, $login_user_id);
}
// ユーザ登録
function regist_user($db, $name, $password, $password_confirmation) {
  // ユーザ登録情報のバリデーション
  if( is_valid_user($name, $password, $password_confirmation) === false){
    return false;
  }
  // ユーザ情報を新規登録
  return insert_user($db, $name, $password);
}

function is_admin($user){
  return $user['type'] === USER_TYPE_ADMIN;
}
// ユーザ登録情報のバリデーション
function is_valid_user($name, $password, $password_confirmation){
  // 短絡評価を避けるため一旦代入。
  $is_valid_user_name = is_valid_user_name($name);
  $is_valid_password = is_valid_password($password, $password_confirmation);
  return $is_valid_user_name && $is_valid_password ;
}
// user_nameのバリデーション
function is_valid_user_name($name) {
  // 初期値：true
  $is_valid = true;
  // 文字数確認
  if(is_valid_length($name, USER_NAME_LENGTH_MIN, USER_NAME_LENGTH_MAX) === false){
    // 異常メッセージ
    set_error('ユーザー名は'. USER_NAME_LENGTH_MIN . '文字以上、' . USER_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  // ユーザ名が半角英数字かどうかチェック
  if(is_alphanumeric($name) === false){
    // 異常メッセージ
    set_error('ユーザー名は半角英数字で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}
// passwordのバリデーション
function is_valid_password($password, $password_confirmation){
  // 初期値：true
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
// ユーザ情報を新規登録
function insert_user($db, $name, $password){
  // SQL文作成
  $sql = "
    INSERT INTO
      users(name, password)
    VALUES ('{$name}', '{$password}');
  ";
  // クエリを実行し、成功すればtrue、失敗すればfalseを返す
  return execute_query($db, $sql);
}

