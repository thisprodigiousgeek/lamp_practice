<?php

//変数の情報取得
function dd($var){
  var_dump($var);
  exit();
}

//urlへリダイレクト
function redirect_to($url){
  header('Location: ' . $url);
  exit;
}

//getの取得
function get_get($name){
  if(isset($_GET[$name]) === true){
    return $_GET[$name];
  };
  return '';
}

//postの取得
function get_post($name){
  if(isset($_POST[$name]) === true){
    return $_POST[$name];
  };
  return '';
}

//fileの取得
function get_file($name){
  if(isset($_FILES[$name]) === true){
    return $_FILES[$name];
  };
  return array();
}

//セッション変数からログイン済みかどうか確認
function get_session($name){
  if(isset($_SESSION[$name]) === true){
    return $_SESSION[$name];
  };
  return '';
}

//ログインユーザー取得
function set_session($name, $value){
  $_SESSION[$name] = $value;
}

//ログインエラー取得
function set_error($error){
  $_SESSION['__errors'][] = $error;
}

//ログインエラーチェック
function get_errors(){
  //$error定義
  $errors = get_session('__errors');
  //エラーでない場合
  if($errors === ''){
    return array();
  }
  //エラーの場合
  set_session('__errors',  array());
  return $errors;
}

//エラー処理
function has_error(){
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
}

//メッセージをセット
function set_message($message){
  $_SESSION['__messages'][] = $message;
}

//メッセージ取得
function get_messages(){
  $messages = get_session('__messages');
  if($messages === ''){
    return array();
  }
  set_session('__messages',  array());
  return $messages;
}

//ログインされたら
function is_logined(){
  return get_session('user_id') !== '';
}


function get_upload_filename($file){
  //有効な画像ファイルの取得
  if(is_valid_upload_image($file) === false){
    return '';
  }
  //画像の定義
  $mimetype = exif_imagetype($file['tmp_name']);
  //許可された拡張子の定義
  $ext = PERMITTED_IMAGE_TYPES[$mimetype];
  return get_random_string() . '.' . $ext;
}

//任意の長さを取得
function get_random_string($length = 20){
  return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}
//画像の保存
function save_image($image, $filename){
  return move_uploaded_file($image['tmp_name'], IMAGE_DIR . $filename);
}
//画像の削除
function delete_image($filename){
  if(file_exists(IMAGE_DIR . $filename) === true){
    unlink(IMAGE_DIR . $filename);
    return true;
  }
  return false;
  
}


//長さの設定
function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){
  //長さの定義
  $length = mb_strlen($string);
  //最小かつ最大値設定
  return ($minimum_length <= $length) && ($length <= $maximum_length);
}
//半角英数字を設定
function is_alphanumeric($string){
  return is_valid_format($string, REGEXP_ALPHANUMERIC);
}

//整数値を設定
function is_positive_integer($string){
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}

//有効な形式設定
function is_valid_format($string, $format){
  return preg_match($format, $string) === 1;
}

//有効な画像ファイルの取得
function is_valid_upload_image($image){
  //ファイルが存在するかどうか
  if(is_uploaded_file($image['tmp_name']) === false){
    //エラーコメント
    set_error('ファイル形式が不正です。');
    return false;
  }
  //画像の定義
  $mimetype = exif_imagetype($image['tmp_name']);
  //ファイル形式がfalseの場合
  if( isset(PERMITTED_IMAGE_TYPES[$mimetype]) === false ){
    //エラーコメント
    set_error('ファイル形式は' . implode('、', PERMITTED_IMAGE_TYPES) . 'のみ利用可能です。');
    return false;
  }
  return true;
}

function h($str){
  return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
}