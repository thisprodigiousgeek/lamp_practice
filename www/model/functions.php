<?php

// var_dump実行
function dd($var){
  var_dump($var);
  exit();
}

// 指定URLへリダイレクト
function redirect_to($url){
  header('Location: ' . $url);
  exit;
}

// GET実行で送られてきた変数が存在するか確認
function get_get($name){
  if(isset($_GET[$name]) === true){
    return $_GET[$name];
  };
  return '';
}

// POST実行で送られてきた変数が存在するか確認
function get_post($name){
  if(isset($_POST[$name]) === true){
    return $_POST[$name];
  };
  return '';
}

// 画像ファイルが存在するか確認
function get_file($name){
  if(isset($_FILES[$name]) === true){
    return $_FILES[$name];
  };
  return array();
}

// sessionに$nameが入っているか確認
function get_session($name){
  if(isset($_SESSION[$name]) === true){
    return $_SESSION[$name];
  };
  return '';
}

// $valueをセッション$nameとしてセットする
function set_session($name, $value){
  $_SESSION[$name] = $value;
}

// $errorをセッションの配列に入れてセットする
function set_error($error){
  $_SESSION['__errors'][] = $error;
}

// エラーがセッションに入っているか確認をして、配列形式で空にする
function get_errors(){
  // get_session = issetで_errorsの存在確認
  $errors = get_session('__errors');
  // $errorsを配列とする
  if($errors === ''){
    return array();
  }
  
  // 空でなければ、SESSION['__errors']を配列形式で空にする
  set_session('__errors',  array());
  return $errors;
}

// errorがセッションにセットされているか&空ではないか確認
function has_error(){
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
}


// messageを配列としてセッションにセット
function set_message($message){
  $_SESSION['__messages'][] = $message;
}

// メッセージがセッションに入っているか確認をして、配列形式で中身を空にする
function get_messages(){
  // messagesがセッションに入っているか(存在するか)確認
  $messages = get_session('__messages');
  // 空であれば配列とする
  if($messages === ''){
    return array();
  }
  // 空でなければ、SESSION['__messages']を配列形式で空にする
  set_session('__messages',  array());
  return $messages;
}

// user_idがセッションに存在した上で、空でなければtrueを返す
// (ログイン状態であればtrue、そうでなければfalse)
function is_logined(){
  return get_session('user_id') !== '';
}


// 画像関連の関数
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


// $stringの文字数が指定範囲内であればtrue、範囲外であればfalseを返す
function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){
  // $stringの文字数を取得
  $length = mb_strlen($string);
  // 文字数が指定最少文字数以上、指定最大文字数以下であればtrue
  return ($minimum_length <= $length) && ($length <= $maximum_length);
}

// 正規表現(一致すればtrue)
// 半角英数字
function is_alphanumeric($string){
  return is_valid_format($string, REGEXP_ALPHANUMERIC);
}

// 正規表現(一致すればtrue)
// 0以上の整数
function is_positive_integer($string){
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}

// 正規表現(一致すればtrue)
function is_valid_format($string, $format){
  return preg_match($format, $string) === 1;
}

// 画像ファイルのエラーチェック
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

// エスケープ関数
function h($str) {
  return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}