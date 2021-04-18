<?php

function dd($var){
  var_dump($var);
  exit();
}

function redirect_to($url){
  header('Location: ' . $url);
  exit;
}
/**
 * HTTPメソッドがgetであるか判定
 * @param str $name name属性の値
 * @return array|null $_GET[$name]|null value属性の値
 */
function get_get($name){
  if(isset($_GET[$name]) === true){
    return $_GET[$name];
  };
  return '';
}
/**
 * HTTPメソッドがpostであるか判定
 * @param str $name のname属性の値
 * @return array|null $_POST[$name]|null value属性の値
 */
function get_post($name){
  if(isset($_POST[$name]) === true){
    return $_POST[$name];
  };
  return '';
}

function get_file($name){
  if(isset($_FILES[$name]) === true){
    return $_FILES[$name];
  };
  return array();
}

function get_session($name){
  if(isset($_SESSION[$name]) === true){
    return $_SESSION[$name];
  };
  return '';
}

function set_session($name, $value){
  $_SESSION[$name] = $value;
}

function set_error($error){
  $_SESSION['__errors'][] = $error;
}
/**
 * sessionからメッセージを取得後、初期化
 * @param void
 * @return array array()|$messages 空配列|メッセージ配列
 */
function get_errors(){
  $errors = get_session('__errors');
  if($errors === ''){
    return array();
  }
  set_session('__errors',  array());
  return $errors;
}
/**
 * エラーメッセージが存在するか、チェック
 * @param void
 * @return bool 存在すればtrueを返す
 */
function has_error(){
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
}

function set_message($message){
  $_SESSION['__messages'][] = $message;
}
/**
 * sessionからメッセージを取得後、初期化
 * @param void
 * @return array array()|$messages 空配列|メッセージ配列
 */
function get_messages(){
  $messages = get_session('__messages');
  if($messages === ''){
    return array();
  }
  set_session('__messages',  array());
  return $messages;
}
/**
 * sesstion['user_id']がセットされているか、チェック
 * @param void
 * @return bool
 */
function is_logined(){
  return get_session('user_id') !== '';
}
/**
 * 画像ファイルのバリデーション
 * @param str $file ファイルデータ
 * @return str ランダムな文字列名のファイルデータ
 */
function get_upload_filename($file){
  //関数の呼び出し　（HTTP POSTでアップロードされたか、画像のファイル形式が正しいか判定）
  if(is_valid_upload_image($file) === false){
    return '';
  }
  // 画像のファイル形式を読み込み、IMAGEYYPE＿〇〇で返す
  $mimetype = exif_imagetype($file['tmp_name']);
  // IMAGEYYPE＿〇〇で一致した拡張子部分を抜き出し
  $ext = PERMITTED_IMAGE_TYPES[$mimetype];
  // ランダムな文字数と組み合わせる
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


/**
 * 文字数のバリデーション
 * @param str $string 調べたい文字列
 * @param str $minimum_length 指定の最小文字数
 * @param str $maximum_length 指定の最大文字数
 * @return bool
 */
function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){
  $length = mb_strlen($string);
  return ($minimum_length <= $length) && ($length <= $maximum_length);
}
/**
 * 半角英数字(アルファニューメリック)かどうか判定
 * @param str $string 調べたい文字列
 * @return bool
 */
function is_alphanumeric($string){
  return is_valid_format($string, REGEXP_ALPHANUMERIC);
}
/**
 * 半角数字かどうか判定
 * @param str $string 調べたい文字列
 * @return bool
 */
function is_positive_integer($string){
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}
/**
 * 正規表現による比較を行う
 * @param str $string 調べたい文字列
 * @param str $format 正規表現の構文
 * @return bool
 */
function is_valid_format($string, $format){
  return preg_match($format, $string) === 1;
}


function is_valid_upload_image($image){
  // HTTP POSTでファイルがアップロードされたかチェック
  if(is_uploaded_file($image['tmp_name']) === false){
    // セッションにエラーメッセージを追加する関数の呼び出し
    set_error('ファイル形式が不正です。');
    // 処理終了
    return false;
  }
  // 画像のファイル形式を読み込み、IMAGEYYPE＿〇〇で返す
  $mimetype = exif_imagetype($image['tmp_name']);
  // 指定の画像形式であるか判定
  if( isset(PERMITTED_IMAGE_TYPES[$mimetype]) === false ){
    // 異なる場合は、セッションにエラーメッセージをセットする
    set_error('ファイル形式は' . implode('、', PERMITTED_IMAGE_TYPES) . 'のみ利用可能です。');
    return false;
  }
  return true;
}
function h($str){
  return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
}

