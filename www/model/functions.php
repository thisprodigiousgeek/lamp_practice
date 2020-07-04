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
// POST値取得
function get_post($name){
  // 指定したキーに値が存在するか確認
  if(isset($_POST[$name]) === true){
    return $_POST[$name];
  };
  // 値が無ければ空で返す
  return '';
}
// FILES値取得
function get_file($name){
  // 指定したキーに値が存在するか確認
  if(isset($_FILES[$name]) === true){
    return $_FILES[$name];
  };
  // 値が無ければ空で返す
  return array();
}
// セッションから値を取得
function get_session($name){
  // 指定したキーに値が存在するか確認
  if(isset($_SESSION[$name]) === true){
    return $_SESSION[$name];
  };
  // 無ければ空で返す
  return '';
}
// セッションに指定したキーと対応する値を入れる
function set_session($name, $value){
  $_SESSION[$name] = $value;
}

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
// エラー確認
function has_error(){
  // セッションにエラーが1個以上登録されていればtrueを返す
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
}
// 正常メッセージ
function set_message($message){
  // セッションにメッセージ保存
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
// ログイン済かどうか確認
function is_logined(){
  return get_session('user_id') !== '';
}
// ファイルをアップロード
function get_upload_filename($file){
  // アップロードファイルのバリデーション
  if(is_valid_upload_image($file) === false){
    // アップロード失敗時は空で返す
    return '';
  }
  // 画像ファイルの拡張子取得
  $mimetype = exif_imagetype($file['tmp_name']);
  $ext = PERMITTED_IMAGE_TYPES[$mimetype];
  // 乱数 + 拡張子名 で返す
  return get_random_string() . '.' . $ext;
}
// 20文字の乱数文字列
function get_random_string($length = 20){
  // 乱数文字列を返す
  return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}
// 画像保存
function save_image($image, $filename){
  // 指定の画像フォルダへファイルを移動し、成功すればtrue、失敗すればfalseを返す
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
// 半角英数字かどうかチェック
function is_alphanumeric($string){
  // フォーマットのバリデーション
  return is_valid_format($string, REGEXP_ALPHANUMERIC);
}
// 0以上の整数かチェック
function is_positive_integer($string){
  // フォーマットのバリデーション
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}
// フォーマットのバリデーション
function is_valid_format($string, $format){
  // 指定したフォーマットかどうか確認
  return preg_match($format, $string) === 1;
}

// アップロードファイルのバリデーション
function is_valid_upload_image($image){
  // 一時ファイルの取得確認
  if(is_uploaded_file($image['tmp_name']) === false){
    // 異常メッセージ
    set_error('ファイル形式が不正です。');
    return false;
  }
  // 一時ファイルの拡張子取得
  $mimetype = exif_imagetype($image['tmp_name']);
  // 取得した拡張子が指定のもの（JPEG or PNG）か確認
  if( isset(PERMITTED_IMAGE_TYPES[$mimetype]) === false ){
    // 異常メッセージ
    set_error('ファイル形式は' . implode('、', PERMITTED_IMAGE_TYPES) . 'のみ利用可能です。');
    return false;
  }
  return true;
}
// HTMLエンティティ化
function h($str){
  return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
}
// HTMLエンティティ化（１次元配列）
function entity_array($array){
  foreach($array as $key => $value){
    if(is_int($value) === false){
      $array[$key] = h($value);
    }
  }
  return $array;
}
// HTMLエンティティ化（２次元配列）
function entity_arrays($arrays){
  foreach($arrays as $keys => $values){
    foreach($values as $key => $value){
      if(is_int($value) === false){
        $arrays[$keys][$key] = h($value);
      }
    }
  }
  return $arrays;
}

