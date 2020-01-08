<?php
//var_dumpする
function dd($var){
  var_dump($var);
  exit();
}
//リダイレクトする
function redirect_to($url){
  header('Location: ' . $url);
  exit;
}
//フォームの情報がちゃんと送られていたら、その内容を返り値として返す(method=get)
function get_get($name){
  if(isset($_GET[$name]) === true){
    return $_GET[$name];
  };
  return '';
}
//フォームの情報がちゃんと送られていたら、その内容を返り値として返す (method=post)
function get_post($name){
  if(isset($_POST[$name]) === true){
    return $_POST[$name];
  };
  return '';
}
//ファイル情報が送られてきたときに、そのファイルの情報を返り値として返す
function get_file($name){
  if(isset($_FILES[$name]) === true){
    return $_FILES[$name];
  };
  return array();
}
//セッションに名前が登録されていたら、その名前を返す
function get_session($name){
  if(isset($_SESSION[$name]) === true){
    return $_SESSION[$name];
  };
  return '';
}
//セッションをセットする(array(1){[$name]=>[$value]})
function set_session($name, $value){
  $_SESSION[$name] = $value;
}
//セッションにエラーをセットする（array(数字){['__erros']=>array(数字){[0]=>...}})
function set_error($error){
  $_SESSION['__errors'][] = $error;
}
//$errosに、＄_SESSION['＿erros']の内容を格納。もし$_SESSION['_errors']が空なら、空の配列を返り値として返し、
function get_errors(){
  $errors = get_session('__errors');
  if($errors === ''){
    return array();
  }
  set_session('__errors',  array());
  return $errors;
}
//セッションにエラーがあり、かつその数が０ではないとき返り値としてtrueを返す（それ以外はfalse)
function has_error(){
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
}
//セッションにメッセージをセットする
function set_message($message){
  $_SESSION['__messages'][] = $message;
}
//$_SESSIONに格納されているメッセージを返り値として取得
function get_messages(){
  $messages = get_session('__messages');
  if($messages === ''){
    return array();
  }
  set_session('__messages',  array());
  return $messages;
}
//返り値として＄_SESSION['user_id']が空ではないということを返す
function is_logined(){
  return get_session('user_id') !== '';
}
//正しくアップロードされたファイルに名前をつける
function get_upload_filename($file){
  if(is_valid_upload_image($file) === false){
    return '';
  }
  $mimetype = exif_imagetype($file['tmp_name']);
  $ext = PERMITTED_IMAGE_TYPES[$mimetype];
  return get_random_string() . '.' . $ext;
}
//ランダムな２０文字からなる文字列を返り値として返す
function get_random_string($length = 20){
  return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}
//アップロードされたファイルをIMAGE_DIRの$filenameへ移動
function save_image($image, $filename){
  return move_uploaded_file($image['tmp_name'], IMAGE_DIR . $filename);
}
//引数に指定したファイルが存在すれば、ファイルを削除し、返り値としてtrueを返す
function delete_image($filename){
  if(file_exists(IMAGE_DIR . $filename) === true){
    unlink(IMAGE_DIR . $filename);
    return true;
  }
  return false;
  
}


//引数に指定した文字列が最小値より大きく、最大値より小さいことを返り値として返す
function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){
  $length = mb_strlen($string);
  return ($minimum_length <= $length) && ($length <= $maximum_length);
}
//正規表現で表されているとき１を返す
function is_alphanumeric($string){
  return is_valid_format($string, REGEXP_ALPHANUMERIC);
}
//正規表現で表されているとき１返す
function is_positive_integer($string){
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}
//$stringが、$formatで指定する正規表現である場合、１を返す
function is_valid_format($string, $format){
  return preg_match($format, $string) === 1;
}

//ファイルが正しくアップロードされていて、かつ拡張子が正しい場合に返り値としてtrueを返す
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

//エスケープ関数

function h ($key) {
  return htmlspecialchars($key, ENT_QUOTES, 'UTF-8');
}
