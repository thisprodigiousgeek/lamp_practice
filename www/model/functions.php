<?php

//var_dump実行?
function dd($var){
  var_dump($var);
  exit();
}

//リダイレクト処理
function redirect_to($url){
  header('Location: ' . $url);
  exit;
}

//GETメソッドの値セット確認
function get_get($name){
  if(isset($_GET[$name]) === true){

    //GETメソッドを返す
    return $_GET[$name];
  };

  //わからない
  return '';
}

//POSTメソッドの値セット確認
function get_post($name){
  if(isset($_POST[$name]) === true){

    //POSTメソッドを返す
    return $_POST[$name];
  };
  
  //わからない
  return '';
}

//FILESメソッドの値セット確認
function get_file($name){
  if(isset($_FILES[$name]) === true){
    return $_FILES[$name];
  };

  //わからない
  return array();
}

//session変数の値セット確認
function get_session($name){
  if(isset($_SESSION[$name]) === true){

    //セッション変数を返す
    return $_SESSION[$name];
  };
  return '';
}

//セッションにキーと値をセットする
function set_session($name, $value){
  $_SESSION[$name] = $value;
}

//セッションにエラー文を代入?
function set_error($error){
  $_SESSION['__errors'][] = $error;
}

//わからない
function get_errors(){
  $errors = get_session('__errors');
  if($errors === ''){
    return array();
  }
  set_session('__errors',  array());
  return $errors;
}

//セッション変数'errors'のセット確認と値があるかどうか?
function has_error(){
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
}

//$messagesをセッション変数$_SESSION['maeesages']として格納する
function set_message($message){
  $_SESSION['__messages'][] = $message;
}

//セッション変数$_SESSION['maeesages']の取得
function get_messages(){

  //セッション変数$_SESSION['maeesages']を$messagesに代入
  $messages = get_session('__messages');

  //$messagesが空の場合
  if($messages === ''){
    return array();
  }
  
  //わからない
  set_session('__messages',  array());
  return $messages;
}

//ユーザーがログイン中かどうか確認
function is_logined(){
  return get_session('user_id') !== '';
}

//商品の画像アップロード
function get_upload_filename($file){

  //拡張子の確認
  if(is_valid_upload_image($file) === false){
    return '';
  }

  //仮にファイルネームを$minetypeに代入
  $mimetype = exif_imagetype($file['tmp_name']);

  //PERMITTED_IMAGE_TYPESがわからない
  $ext = PERMITTED_IMAGE_TYPES[$mimetype];
  return get_random_string() . '.' . $ext;
}

//文字列をランダムに生成し取得する?
function get_random_string($length = 20){
  return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}

//商品画像を指定のフォルダに移動・保存?
function save_image($image, $filename){
  return move_uploaded_file($image['tmp_name'], IMAGE_DIR . $filename);
}

//商品画像削除
function delete_image($filename){
  if(file_exists(IMAGE_DIR . $filename) === true){
    unlink(IMAGE_DIR . $filename);
    return true;
  }
  return false;
  
}


//わからない
function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){
  $length = mb_strlen($string);
  return ($minimum_length <= $length) && ($length <= $maximum_length);
}

//わからない
function is_alphanumeric($string){

  //REGEXP_ALPHANUMERIC?
  return is_valid_format($string, REGEXP_ALPHANUMERIC);
}

//わからない
function is_positive_integer($string){

  //REGEXP_POSITIVE_INTEGER?
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}

//わからない
function is_valid_format($string, $format){
  return preg_match($format, $string) === 1;
}

//ファイル拡張子の確認?
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

//特殊文字をhtmlエンティティに変換
function h ($str) {
  return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
}

