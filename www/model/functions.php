<?php

function dd($var){
  var_dump($var);
  exit();
}

function redirect_to($url){
  header('Location: ' . $url);
  exit;
}

function get_get($name){
  if(isset($_GET[$name]) === true){
    return $_GET[$name];
  };
  return '';
}

function get_post($name){
  if(isset($_POST[$name]) === true){
    return $_POST[$name]; //送信されてきた$nameの値を取得
  };
  return '';
}

function get_file($name){
  if(isset($_FILES[$name]) === true){
    return $_FILES[$name];
  };
  return array();
}

//????????????
function get_session($name){
  //セッション変数に、キーと値があるときに、そのままその値を返す
  if(isset($_SESSION[$name]) === true){
    return $_SESSION[$name];
  };
  return ''; //該当キーが無ければ空文字を返す??????
}

//????????????
function set_session($name, $value){
  $_SESSION[$name] = $value;
}          //キー    //値

function set_error($error){
  $_SESSION['__errors'][] = $error;
}

function get_errors(){
  $errors = get_session('__errors');
  if($errors === ''){
    return array();
  }
  set_session('__errors',  array());
  return $errors;
}

//エラーメッセージはセッションに配列の形で保存される。
//エラーメッセージが存在する場合にtrueを返す。存在しなければfalseを返す。
function has_error(){
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
}

function set_message($message){
  $_SESSION['__messages'][] = $message;
}

function get_messages(){
  $messages = get_session('__messages');
  if($messages === ''){
    return array();
  }
  set_session('__messages',  array());
  return $messages;
}

function is_logined(){
  return get_session('user_id') !== '';
}

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
  if( isset(PERMITTED_IMAGE_TYPES[$mimetype]) === false ){
    set_error('ファイル形式は' . implode('、', PERMITTED_IMAGE_TYPES) . 'のみ利用可能です。');
    return false;
  }
  return true;
}

//XSS対策のh関数を定義
function h ($str){ 
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

//トークンを生成する関数の定義。
function get_csrf_token(){
  // get_random_string()はユーザー定義関数。
  $token = get_random_string(30);  //ランダム30文字
  // set_session()はユーザー定義関数。
  // $tokenをsessionに保存し
  set_session('csrf_token', $token); //(キー, 値)
               //=$name?   =$value? トークンをセッションに保存そのきー名で
  //$tokenを取得  
  return $token;
}

// トークンのチェックを行う関数の定義
function is_valid_csrf_token($token){
  //$tokenの中身が空ならfalseを返す
  if($token === '') { 
    return false; 
  }
  // get_session()はユーザー定義関数
  // ===成立でtrue返す　比較してる
  return $token === get_session('csrf_token');
}        //フォームから送られてきたトークン //セッションに保存されたトークン