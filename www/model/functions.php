<?php
// ddという関数に$varという値を渡す
function dd($var){
// エラーの確認をする
  var_dump($var);
//確認を終えたら終了する
  exit();
}
// この関数は指定したurlに飛ぶ関数
// urlの先頭の場所にいく
function redirect_to($url){
  header('Location: ' . $url);
  exit;
}
// get_getという関数に$nameという値を渡す
function get_get($name){
//$_GETに$nameを送信していれば、trueを返し、なければfalseを返す。
  if(isset($_GET[$name]) === true){
    return $_GET[$name];
  };
  return '';
}
// get_postという関数に$nameという値を渡す
function get_post($name){
// $_POSTに$nameを送信していれば、trueを返し、なければfalseを返す。
  if(isset($_POST[$name]) === true){
    return $_POST[$name];
  };
  return '';
}
// get_fileという関数に$nameという値を渡す
function get_file($name){
// $_FILESに$nameを送信してれば,trueを返し、なければfalseを返す。
  if(isset($_FILES[$name]) === true){
    return $_FILES[$name];
  };
  return array();
}
// この関数はget_sessionという関数に$nameという値を渡す
function get_session($name){
// $SESSIONに$nameが送信しているか確認している。
// $nameがあれば、trueを返し、なければfalseを返す。
  if(isset($_SESSION[$name]) === true){
    return $_SESSION[$name];
  };
  return '';
}
// この関数はset_sessionという関数に$nameと$valueという値を渡す
function set_session($name, $value){
// $_SESSION[$name]に$valueを代入する
  $_SESSION[$name] = $value;
}
// この関数はset_errorという関数に$errorという値を渡す
function set_error($error){
// $_SESSION['__errors'][] に＄errorを代入している
  $_SESSION['__errors'][] = $error;
}
// この関数はエラーになっているかチェックしている
function get_errors(){
// $errors変数にget_session('__errors')を代入している
  $errors = get_session('__errors');
// エラーだった場合配列から$errors変数を返す
  if($errors === ''){
    return array();
  }
// set_sessionという関数に、エラーメッセージを渡す
// $errors変数に返す
  set_session('__errors',  array());
  return $errors;
}
// has_errorという関数の中に
//$_SESSIONに['__error']が送信している、かつ$_SESSION['__errors']が0ではない場合返す
function has_error(){
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
}
//set_messageという関数に$messageの変数を渡す
// $SESSION['__messages'][]に$message変数を代入する
function set_message($message){
  $_SESSION['__messages'][] = $message;
}
// get_messagesという関数の中に$messagesという変数にget_session('__messages')を代入する
function get_messages(){
  $messages = get_session('__messages');
//$messagesが空文字の場合array配列に返す
  if($messages === ''){
    return array();
  }
//set_sessionに'__messages'とarray配列を渡す
//$messagesの変数に返す
  set_session('__messages',  array());
  return $messages;
}
// この関数はログインしているかをチェックしている関数である
// get_session('user_id')で$_SESSION['user_id']を取得し、それを空文字と比較している
// すなわちセッションにユーザーIDが入っていればtrueを返し、入っていなければfalseを返す
function is_logined(){
  return get_session('user_id') !== '';
}
// get_upload_filenameの関数に$file変数を渡す
function get_upload_filename($file){
// is_valid_upload_image($file)がfalseだった場合、空文字に返す
  if(is_valid_upload_image($file) === false){
    return '';
  }
//$mimetype変数にexif_imagetype($file['tmp_name'])を代入する
  $mimetype = exif_imagetype($file['tmp_name']);
// $ext変数にPERMITTED＿IMAGE_TYPES[$mimetype];を代入する
  $ext = PERMITTED_IMAGE_TYPES[$mimetype];
// get_random_string()に$extを繋げる
  return get_random_string() . '.' . $ext;
}
// get_random_string関数に($length = 20)の変数を渡す
function get_random_string($length = 20){
// 指定した文字列の一部を取得できる関数substrに返す
  return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}
// save_image関数に$image,$filename変数を渡す
//move_uploaded_file関数に返す
function save_image($image, $filename){
  return move_uploaded_file($image['tmp_name'], IMAGE_DIR . $filename);
}
// delete_image関数に$filenameの変数を渡す
// file_existsにIMAGE_DIRと$filenameがあればtrueを返し、なければfalseを返す
function delete_image($filename){
  if(file_exists(IMAGE_DIR . $filename) === true){
// unlinkはファイルを削除する
    unlink(IMAGE_DIR . $filename);
    return true;
  }
  return false;
  
}


// この関数は文字列の文字の制限をしている関数
//$length変数に文字列のながさを得るmb_strlen($string)を代入する
// $lengthより$minimum_length方が小さい かつ $lengthより$maximum_lengthの方が大きいを返す
function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){
  $length = mb_strlen($string);
  return ($minimum_length <= $length) && ($length <= $maximum_length);
}
// この関数は有効な英数字を使っているかの確認の関数
function is_alphanumeric($string){
// is_vaild_formatを返す
  return is_valid_format($string, REGEXP_ALPHANUMERIC);
}
// この関数は文字列の整数の確認
function is_positive_integer($string){
//is_vaild_formatを返す
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}
// この関数は文字列の有効な文字を使っているかの確認
function is_valid_format($string, $format){
//文字列が半角英数字だった場合に返す
  return preg_match($format, $string) === 1;
}

// この関数は有効なファイルをアップロードされているか確認
function is_valid_upload_image($image){
//正しいファイル名が記入されていなかったらエラーを返す
  if(is_uploaded_file($image['tmp_name']) === false){
    set_error('ファイル形式が不正です。');
    return false;
  }
// この変数は画像ファイルを表す。
  $mimetype = exif_imagetype($image['tmp_name']);
// 指定したファイル名が違った場合、エラーを返し、合っている場合falseを返す
  if( isset(PERMITTED_IMAGE_TYPES[$mimetype]) === false ){
    set_error('ファイル形式は' . implode('、', PERMITTED_IMAGE_TYPES) . 'のみ利用可能です。');
    return false;
  }
  return true;
}
// この関数はhtmlspecialcharsをhと省略することができる
// クロスサイトスクリプティング対策
function h($str){
  return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
}
