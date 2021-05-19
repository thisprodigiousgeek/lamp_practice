<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

function get_user($db, $user_id){//ユーザーのパスワードと名前をゲットする関数
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
    ";//$user_idには何が入るかお楽しみ。
  $params = array($user_id);
  return fetch_query($db, $sql, $params);//「select文でデータベースから選んでくる関数（function.php）」が実行される。
}//エラーちゃうかったら、該当するデータを1行だけ取得した配列が返ってくる

function get_user_by_name($db, $name){//ユーザーのidとパスワード取得する関数
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
    ";//$nameはおたのしみ。取得するのは1行だけやで
  $params = array($name);
  return fetch_query($db, $sql, $params);//「select文でデータベースから選んでくる関数（function.php）」が実行される。
}//エラーちゃうかったら、該当するデータを1行だけ取得した配列が返ってくる

function login_as($db, $name, $password){//userとしてログインできてるで関数
  $user = get_user_by_name($db, $name);//$userは「ユーザーのidとパスワード取得する関数」からの「select文でデータベースから選んでくる関数（function.php）」でわかる
  if($user === false || $user['password'] !== $password){//もし$userが「fetch_query($db, $sql)」でfalse返し、あるいは入力されたパスワードがデータベースえお一致せんかったら
    return false;//処理やめぴ
  }
  set_session('user_id', $user['user_id']);//でも一致したらuser_idをセッションに入れてあげて
  return $user;//$userっていうあだ名つけて使っていいで
}

function get_login_user($db){//ログインしてるユーザーのidwoを手に入れる関数
  $login_user_id = get_session('user_id');//セッション箱に入ってるuser_idを出してきて、$login_user_idっていうあだ名つける

  return get_user($db, $login_user_id);
  //さっき作ったget_user関数からのfetch_queryが実行されるから、エラーちゃうかったら、該当するデータを1行だけ取得した配列が返ってくる
}

//registは日本語で「登録」、confirmationは「確認」、validは「根拠の確かな」
function regist_user($db, $name, $password, $password_confirmation) {//名前とパスワードと確認用のパスワードを登録するときの関数
  if( is_valid_user($name, $password, $password_confirmation) === false){//is_valid_userでエラーが出てあだ名つけられへんかったら
    return false;//処理やめぴ
  }
  
  return insert_user($db, $name, $password);//でも！エラーなかったら、データベースに入れてあげる
}

function is_admin($user){//$userのアクセス権限を決める関数
  return $user['type'] === USER_TYPE_ADMIN;//$userの連想配列typeにUSER_TYPE_ADMIN、つまり１って入れる
}

function is_valid_user($name, $password, $password_confirmation){//ユーザーの名前とパスワードと確認用パスワードをチェックする
  // 短絡評価を避けるため一旦代入。
  //短絡評価とは（AかつB）のとき、Aがあかんかったらその時点で処理やめぴすること
  $is_valid_user_name = is_valid_user_name($name);//エラーなく$is_validが帰ってきたら
  $is_valid_password = is_valid_password($password, $password_confirmation);////エラーなく$is_validが帰ってきたら
  return $is_valid_user_name && $is_valid_password ;//$is_validはそれぞれあだ名つける
}

function is_valid_user_name($name) {//ユーザー名の新規登録する場合にチェックする関数
  $is_valid = true;//入力されたら
  if(is_valid_length($name, USER_NAME_LENGTH_MIN, USER_NAME_LENGTH_MAX) === false){//決められた文字数ちゃうかったら
    set_error('ユーザー名は'. USER_NAME_LENGTH_MIN . '文字以上、' . USER_NAME_LENGTH_MAX . '文字以内にしてください。');//エラーメッセージをセッション箱に入れる
    $is_valid = false;//$is_validは処理やめぴ
  }
  if(is_alphanumeric($name) === false){//alphanumericは日本語で「英数字」。名前が英数字ちゃうかったら
    set_error('ユーザー名は半角英数字で入力してください。');//エラーメッセージをセッション箱に入れる
    $is_valid = false;//$is_validは処理やめぴ
  }
  return $is_valid;//入力されたやつそのまま置いとく
}

function is_valid_password($password, $password_confirmation){//パスワードの新規登録する場合にチェックする関数
  $is_valid = true;//入力されたら
  if(is_valid_length($password, USER_PASSWORD_LENGTH_MIN, USER_PASSWORD_LENGTH_MAX) === false){//決められた文字数ちゃうかったら
    set_error('パスワードは'. USER_PASSWORD_LENGTH_MIN . '文字以上、' . USER_PASSWORD_LENGTH_MAX . '文字以内にしてください。');//エラーメッセージをセッション箱に入れる
    $is_valid = false;//入力消す
  }
  if(is_alphanumeric($password) === false){//alphanumericは日本語で「英数字」。名前が英数字ちゃうかったら
    set_error('パスワードは半角英数字で入力してください。');//エラーメッセージをセッション箱に入れる
    $is_valid = false;//入力消す
  }
  if($password !== $password_confirmation){//入力したパスワードと確認用のパスワードがちゃうかったら
    set_error('パスワードがパスワード(確認用)と一致しません。');//エラーメッセージをセッション箱に入れる
    $is_valid = false;//入力消す
  }
  return $is_valid;//入力されたやつそのまま置いとく
}

function insert_user($db, $name, $password){//ユーザーテーブルに名前とパスワードを入れる関数
    $sql = "
    INSERT INTO
      users(name, password)
    VALUES (?, ?);
  ";//$nameと$passwordは何が入るかお楽しみ
  $params = array($name, $password);
  return execute_query($db, $sql, $params);//「insert文とupdate文でデータベースを書き込みするで関数（functiuom.php)」
}//エラーちゃうかったら、insertをを実行してくれる。$paramsに連想配列で一応準備もしてくれてる。

