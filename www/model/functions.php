<?php
//$varを表示
function dd($var){
  var_dump($var);
  exit();
}

//$urlを表示
function redirect_to($url){
  header('Location: ' . $url);
  exit;
}

//getデータのnameを取得
function get_get($name){
  if(isset($_GET[$name]) === true){
    return $_GET[$name];
  };
  return '';
}

//postデータのnameを取得
function get_post($name){
  if(isset($_POST[$name]) === true){
    return $_POST[$name];
  };
  return '';
}

//ファイル名の取得
function get_file($name){
  if(isset($_FILES[$name]) === true){
    return $_FILES[$name];
  };
  return array();
}

//セッション変数から$nameの取得
function get_session($name){
  if(isset($_SESSION[$name]) === true){
    return $_SESSION[$name];
  };
  return '';
}

//セッション変数の$nameに代入
function set_session($name, $value){
  $_SESSION[$name] = $value;
}

//セッション変数の__errorsに代入
function set_error($error){
  $_SESSION['__errors'][] = $error;
}

//セッション変数にエラーを格納
function get_errors(){
  //セッション変数の__errorsを取得
  $errors = get_session('__errors');
  //$errorsが空だったら配列を空にする
  if($errors === ''){
    return array();
  }
  //セッション変数__errorsに配列で格納
  set_session('__errors',  array());
  return $errors;
}

//セッション変数のエラーに値が入っている
function has_error(){
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
}

//セッション変数の__messagesに代入
function set_message($message){
  $_SESSION['__messages'][] = $message;
}

//セッションのmessageを$messagesに代入
function get_messages(){
  //セッション変数の__messagesを取得
  $messages = get_session('__messages');
  //$messagesが空だったら配列を空にする
  if($messages === ''){
    return array();
  }
  //セッション変数__messagesに配列で格納
  set_session('__messages',  array());
  return $messages;
}

//セッション変数のuser_idに値が入っている
function is_logined(){
  return get_session('user_id') !== '';
}

//画像ファイルであった場合
function get_upload_filename($file){
  //画像ファイルでなっかた場合
  if(is_valid_upload_image($file) === false){
    return '';
  }
  //ファイル種別を取得
  $mimetype = exif_imagetype($file['tmp_name']);
  //jegとpngのみ$extに代入
  $ext = PERMITTED_IMAGE_TYPES[$mimetype];
  //ランダムな文字列を生成
  return get_random_string() . '.' . $ext;
}

//ランダムな文字列を生成
function get_random_string($length = 20){
  //ハッシュを使用しランダムな文字列を生成
  return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}

//ファイルを一時フォルダから指定したディレクトリに移動
function save_image($image, $filename){
  return move_uploaded_file($image['tmp_name'], IMAGE_DIR . $filename);
}

//画像削除
function delete_image($filename){
  //ファイルが存在するかどうかチェック
  if(file_exists(IMAGE_DIR . $filename) === true){
    //trueだったらファイル削除する
    unlink(IMAGE_DIR . $filename);
    return true;
  }
  return false;
  
}

//文字列の長さを取得
function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){
  //文字の長さを取得
  $length = mb_strlen($string);
  //$minimum_lengthは&lengthより小さいかつ、$lengthは$maxmum_lengthより小さい
  return ($minimum_length <= $length) && ($length <= $maximum_length);
}

//文字列が半角英数字であるかチェック
function is_alphanumeric($string){
  return is_valid_format($string, REGEXP_ALPHANUMERIC);
}

//文字列が０以上の整数であるかチェック
function is_positive_integer($string){
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}

function is_valid_format($string, $format){
  return preg_match($format, $string) === 1;
}

//画像ファイルかどうかチェック
function is_valid_upload_image($image){
  //postでアップロードされたファイルかどうかチェック
  if(is_uploaded_file($image['tmp_name']) === false){
    //falseだったらセッション変数のエラーに入れる
    set_error('ファイル形式が不正です。');
    return false;
  }
  //ファイルの種別を取得
  $mimetype = exif_imagetype($image['tmp_name']);
  //$mimetypeが配列キーにあるかどうかチェック
  if( array_key_exists($mimetype, PERMITTED_IMAGE_TYPES) === false ){
    //falseだったらセッション変数のエラーに入れる
    set_error('ファイル形式は' . implode('、', PERMITTED_IMAGE_TYPES) . 'のみ利用可能です。');
    return false;
  }
  return true;
}

//$strにhtmlエスケープを施す
function h($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'utf-8');
}

// トークンの生成
function get_csrf_token(){
  // get_random_string()はユーザー定義関数。
  $token = get_random_string(30);
  // set_session()はユーザー定義関数。
  set_session('csrf_token', $token);
  return $token;
}

// トークンのチェック
function is_valid_csrf_token($token){
  if($token === '') {
    return false;
  }
  // get_session()はユーザー定義関数
  return $token === get_session('csrf_token');
}