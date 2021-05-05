<?php
//渡した変数の中身を表示する関数
function dd($var){
  var_dump($var);
  exit();
}
//渡したurlにリダイレクトする関数
function redirect_to($url){
  header('Location: ' . $url);
  exit;
}
//フォームからgetされた情報を取得する関数
function get_get($name){
  if(isset($_GET[$name]) === true){
    return $_GET[$name];
  };
  return '';
}
// フォームからpostされた情報を取得する関数
function get_post($name){
  if(isset($_POST[$name]) === true){
    return $_POST[$name];
  };
  return '';
}
// アップロードされたファイルを取得する関数
function get_file($name){
  if(isset($_FILES[$name]) === true){
    return $_FILES[$name];
  };
  return array();
}
// セッションに保存された情報の取得
function get_session($name){
  if(isset($_SESSION[$name]) === true){
    return $_SESSION[$name];
  };
  return '';
}
// セッションに情報を保存する関数
function set_session($name, $value){
  $_SESSION[$name] = $value;
}
// セッションに保存された__errorキーにをエラー文をセットする
function set_error($error){
  $_SESSION['__errors'][] = $error;
}
// セッションにエラー文をセットして取得する関数
function get_errors(){
  $errors = get_session('__errors');//セッションからエラー文を取得
  // エラー文が空だったら空の配列返す
  if($errors === ''){
    return array();
  }
  set_session('__errors',  array());
  return $errors;
}
// セッションにエラー文が入っているか確認
function has_error(){
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
}
// セッションの__messageキーにメッセージをセットする関数
function set_message($message){
  $_SESSION['__messages'][] = $message;
}
// セットされたメッセージを取得する関数
function get_messages(){
  $messages = get_session('__messages');//セッションからメッセージを取得
  // メッセージがセットされていない場合空の配列を返す
  if($messages === ''){
    return array();
  }
  set_session('__messages',  array());
  return $messages;
}
// ログインしているかをチェックする関数
function is_logined(){
  return get_session('user_id') !== '';
}
// アップロードされたファイルを新しいファイル名を生成して保存する関数
function get_upload_filename($file){
  if(is_valid_upload_image($file) === false){
    return '';
  }
  $mimetype = exif_imagetype($file['tmp_name']);//画像の形式の確認
  $ext = PERMITTED_IMAGE_TYPES[$mimetype];
  return get_random_string() . '.' . $ext;
}
// 新しいファイル名を生成する関数
function get_random_string($length = 20){
  return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}
//アップロードされたファイルを指定ディレクトリに移動して保存
function save_image($image, $filename){
  return move_uploaded_file($image['tmp_name'], IMAGE_DIR . $filename);
}
// 画像ファイルを削除する関数
function delete_image($filename){
  if(file_exists(IMAGE_DIR . $filename) === true){
    unlink(IMAGE_DIR . $filename);
    return true;
  }
  return false;
  
}


// 文字列の文字数をチェックする関数
function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){
  // 文字数の取得
  $length = mb_strlen($string);
  return ($minimum_length <= $length) && ($length <= $maximum_length);
}
// 文字列が半角英数字かチェックする関数
function is_alphanumeric($string){
  // 渡した文字列と$formatが一致するか確認する関数を利用
  return is_valid_format($string, REGEXP_ALPHANUMERIC);
}

function is_positive_integer($string){
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}
 // 渡した文字列と$formatが一致するか確認する関数を利用
function is_valid_format($string, $format){
  return preg_match($format, $string) === 1;
}

// アップされた画像をチェックする関数
function is_valid_upload_image($image){
  // ポストでアップロードされたファイルかチェック
  if(is_uploaded_file($image['tmp_name']) === false){
    set_error('ファイル形式が不正です。');
    return false;
  }
  $mimetype = exif_imagetype($image['tmp_name']);//画像の形式の取得
  // 定数で指定した拡張子以外のファイルであればfalseを返す
  if( isset(PERMITTED_IMAGE_TYPES[$mimetype]) === false ){
    set_error('ファイル形式は' . implode('、', PERMITTED_IMAGE_TYPES) . 'のみ利用可能です。');
    return false;
  }
  return true;
}
// わたした文字列をhtmlエスケープ処理して返す関数
function h($str){
  return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
}
