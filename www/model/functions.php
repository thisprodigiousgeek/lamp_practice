<?php

function dd($var){
  var_dump($var);
  exit();
}
//それぞれの条件にあったリダイレクトをする
function redirect_to($url){
  header('Location: ' . $url);
  exit;
}
//get変数で情報を受け取る
function get_get($name){
  if(isset($_GET[$name]) === true){
    return $_GET[$name];
  };
  return '';
}
//post変数で情報を受け取る
function get_post($name){
  if(isset($_POST[$name]) === true){
    return $_POST[$name];
  };
  return '';
}
//file変数で情報を受け取る
function get_file($name){
  if(isset($_FILES[$name]) === true){
    return $_FILES[$name];
  };
  return array();
}
//session変数で情報を受け取る
function get_session($name){
  if(isset($_SESSION[$name]) === true){
    return $_SESSION[$name];
  };
  return '';
}
//sessionに情報を保存する
function set_session($name, $value){
  $_SESSION[$name] = $value;
}
//sessionにエラーメッセージの情報を保存する
function set_error($error){
  $_SESSION['__errors'][] = $error;
}
//実際のエラーをsessionに保存する
function get_errors(){
  $errors = get_session('__errors');
  if($errors === ''){
    return array();
  }
  set_session('__errors',  array());
  return $errors;
}
//エラーがあるかないかを確認する
function has_error(){
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
}
//メッセージをsessionに保存する
function set_message($message){
  $_SESSION['__messages'][] = $message;
}
//実際のメッセージをsessionに保存する
function get_messages(){
  $messages = get_session('__messages');
  if($messages === ''){
    return array();
  }
  set_session('__messages',  array());
  return $messages;
}
//user_id がないかどうかを調べる
function is_logined(){
  return get_session('user_id') !== '';
}
//正しい画像が送られたらランダムで名前をつける
function get_upload_filename($file){
  if(is_valid_upload_image($file) === false){
    return '';
  }
  $mimetype = exif_imagetype($file['tmp_name']);
  $ext = PERMITTED_IMAGE_TYPES[$mimetype];
  return get_random_string() . '.' . $ext;
}
//48行までのランダムな文字列を作成する
function get_random_string($length = 20){
  return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}
//画像をアップデートする
function save_image($image, $filename){
  return move_uploaded_file($image['tmp_name'], IMAGE_DIR . $filename);
}
//画像をさくじょする
function delete_image($filename){
  if(file_exists(IMAGE_DIR . $filename) === true){
    unlink(IMAGE_DIR . $filename);
    return true;
  }
  return false;
  
}


//文字数の上限と下限を定義する
function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){
  $length = mb_strlen($string);
  return ($minimum_length <= $length) && ($length <= $maximum_length);
}
//正規表現と$stringがマッチしたら1を返す
function is_alphanumeric($string){
  return is_valid_format($string, REGEXP_ALPHANUMERIC);
}
//正規表現と$stringがマッチしたら1を返す
function is_positive_integer($string){
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}
//$formatと$stringがマッチしたら1を返す
function is_valid_format($string, $format){
  return preg_match($format, $string) === 1;
}

//アップロードされた画像が正しいか確認する
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
function h($str){
  return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
}
