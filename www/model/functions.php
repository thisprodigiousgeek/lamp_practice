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
//セッション変数に$valueを代入
function set_session($name, $value){
  $_SESSION[$name] = $value;
}
//セッション変数に$errorを代入
function set_error($error){
  $_SESSION['__errors'][] = $error;
}
//セッションのerrorsを$errorsに代入
function get_errors(){
  $errors = get_session('__errors');
  //$errorsが空だったら配列に格納
  if($errors === ''){
    return array();
  }
  //セッションerrorsにarray()を代入
  set_session('__errors',  array());
  return $errors;
}
//セッション変数に値が入っている時
function has_error(){
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
}
//セッション変数に$messageを代入
function set_message($message){
  $_SESSION['__messages'][] = $message;
}
//セッションのmessageを$messagesに代入
function get_messages(){
  $messages = get_session('__messages');
  if($messages === ''){
    return array();
  }
  set_session('__messages',  array());
  return $messages;
}
//セッション変数のuser_idに値が空の時
function is_logined(){
  return get_session('user_id') !== '';
}
//
function get_upload_filename($file){
  if(is_valid_upload_image($file) === false){
    return '';
  }
  $mimetype = exif_imagetype($file['tmp_name']);
  $ext = PERMITTED_IMAGE_TYPES[$mimetype];
  return get_random_string() . '.' . $ext;
}

function get_random_string($length = 20){
  return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}

function save_image($image, $filename){
  return move_uploaded_file($image['tmp_name'], IMAGE_DIR . $filename);
}

function delete_image($filename){
  if(file_exists(IMAGE_DIR . $filename) === true){
    unlink(IMAGE_DIR . $filename);
    return true;
  }
  return false;
  
}



function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){
  $length = mb_strlen($string);
  return ($minimum_length <= $length) && ($length <= $maximum_length);
}

function is_alphanumeric($string){
  return is_valid_format($string, REGEXP_ALPHANUMERIC);
}

function is_positive_integer($string){
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}

function is_valid_format($string, $format){
  return preg_match($format, $string) === 1;
}


function is_valid_upload_image($image){
  if(is_uploaded_file($image['tmp_name']) === false){
    set_error('ファイル形式が不正です。');
    return false;
  }
  $mimetype = exif_imagetype($image['tmp_name']);
  if( array_key_exists($mimetype, PERMITTED_IMAGE_TYPES) === false ){
    set_error('ファイル形式は' . implode('、', PERMITTED_IMAGE_TYPES) . 'のみ利用可能です。');
    return false;
  }
  return true;
}

function h($str) {
 
  return htmlspecialchars($str, ENT_QUOTES, 'utf-8');
}