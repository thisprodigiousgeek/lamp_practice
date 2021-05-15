<?php
/*
var_dump変数の型や配列情報を表示する
*/
function dd($var){
  var_dump($var);
  exit();
}

/*
指定のURLに飛ばす
exitを書いてこの関数が持つ処理は終了
*/
function redirect_to($url){
  header('Location: ' . $url);
  exit;
}

/*
$GET($name)が存在していればその値ををreturn
中身が入っていることを確認している
中身が入っていない もしくはNULLの時は空文字を返す
*/
function get_get($name){
  if(isset($_GET[$name]) === true){
    return $_GET[$name];
  };
  return '';
}

/*
$POSTに$nameが入っているかを確認
入っていればその値をreturn 入っていない or NULLなら空文字を返す
*/
function get_post($name){
  if(isset($_POST[$name]) === true){
    return $_POST[$name];
  };
  return '';
}

/*
$_FILES配列に$nameが存在しいるかを確認
入っていればその値をreturn 入っていなければ配列をreturn
*/
function get_file($name){
  if(isset($_FILES[$name]) === true){
    return $_FILES[$name];
  };
  return array();
}

/*
$_SESSIONのキーに$nameが入っているかを確認
入っていることが確認できたらその値をreturn
$_SESSIONのキーに$nameがなければ空文字を返す
returnはそこで処理を終了する
*/
function get_session($name){
  if(isset($_SESSION[$name]) === true){
    return $_SESSION[$name];
  };
  return '';
}

/*
引数$nameをセッション名、$valueを値として代入
セッション配列に$nameを記録する
*/
function set_session($name, $value){
  $_SESSION[$name] = $value;
}

/*
引数$errorをセッション配列に記録する
*/
function set_error($error){
  $_SESSION['__errors'][] = $error;
}

/*
$_SESSIONのキーに__errorsが入っているかを確認
空文字の時は配列をreturn(エラーがない)
入っていた場合は$_SESSION['__errors']にarray()を代入して$errorsをreturn
*/
function get_errors(){
  $errors = get_session('__errors');
  if($errors === ''){
    return array();
  }
  set_session('__errors',  array());
  return $errors;
}

/*
$SESSIONに記録されている__errorsがあるのかを確認　AND $SESSIONに記録されている__errorsの数をカウントし0ではない
何かしらのエラーがある状態
*/
function has_error(){
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
}

/*
引数$messageをセッション配列__messagesに記録する
*/
function set_message($message){
  $_SESSION['__messages'][] = $message;
}

/*
$_SESSION配列に__messagesが入っているかを確認　入っていればその値を$messagesに代入
$messagesが空文字なら配列をreturn
引数 array()をセッション配列__messagesに記録する $messagesをreturn
*/
function get_messages(){
  $messages = get_session('__messages');
  if($messages === ''){
    return array();
  }
  set_session('__messages',  array());
  return $messages;
}

/*
$_SESSIONにuser_idが入っているかを確認
入っていればその値をreturn 
sessionに登録されているということはログイン済となるのかな
*/
function is_logined(){
  return get_session('user_id') !== '';
}

/*
想定していない方法でファイルがアップロードされていないかを確認　falseなら空文字を返す
画像ファイルかを確認し$mimetypeにIMAGETYPE_PNGを代入
$extに'png'を代入
*/
function get_upload_filename($file){
  if(is_valid_upload_image($file) === false){
    return '';
  }
  $mimetype = exif_imagetype($file['tmp_name']);
  $ext = PERMITTED_IMAGE_TYPES[$mimetype];
  return get_random_string() . '.' . $ext;
}

/*
substrは対象文字列の開始位置から文字数文の文字列を抽出
substr(対象文字列、開始位置、[、開始位置から何文字取得したいのか指定もできる]);
$str = “abcde”;
echo substr($str,1);
出力結果　bcde

base_convertは文字を置換してくれる
base_convert(置換する数値、変換前のnumberの基数、変換後のnumber基数)
基数とは1つの桁に何個の数字が出て来るか

// 出力結果
// d8b076148c939d9d2d6eb60458969c486794a4c0fcf0632be58fa5bf6d15aafa
$original_string = "パスワード";
$hased_string = hash('sha256', $original_string);
print_r($hased_string.PHP_EOL);

uniqid()　第一引数はプレフィックス　変数名の前に必ず「konita_a」「piyo_b」これがプレフィックス
第二引数は生成される文字数を増やすかどうかをtrue/falseで指定する。第二引数はデフォルトではfalse状態で、生成される文字数は13文字。

唯一の13文字を生成し、hash関数を使って入力値を別の値に変換する

*/
function get_random_string($length = 20){
  return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}

/*
アップロードされたファイルの保存場所を変更する関数
第一引数には移動前のパスを指定 第二引数では移動先のパス
*/
function save_image($image, $filename){
  return move_uploaded_file($image['tmp_name'], IMAGE_DIR . $filename);
}

/*
file_existsはファイルまたはディレクトリが存在するか調べる　存在すればtrue しなければfalseで返す
trueならunlinkを使用してファイルを削除
*/
function delete_image($filename){
  if(file_exists(IMAGE_DIR . $filename) === true){
    unlink(IMAGE_DIR . $filename);
    return true;
  }
  return false;
  
}

/*
$stringの文字数を確認し、$lengthに代入
$minimum_lengthと$lengthを比較する
$lengthがminimum以上かつmaximum以下
おそらくreturnは ture or falseかな？
*/
function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){
  $length = mb_strlen($string);
  return ($minimum_length <= $length) && ($length <= $maximum_length);
}

/*
正規表現を使用
正規表現はマッチすれば1を返す
マッチしなければ0を返す
エラーが起きるとfalseを返す
preg_match ( 正規表現のパターン ,  検索対象の文字列 [, array 検索結果 [, int $flags = 0 [, int $offset = 0 ]]] )
*/
function is_alphanumeric($string){
  return is_valid_format($string, REGEXP_ALPHANUMERIC);
}

/*
正規表現違うパターン
正の整数かを確認？
*/
function is_positive_integer($string){
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}

/*
正規表現preg_matchを使いたい時に使用する
マッチしていればtrue していなければfalse
*/
function is_valid_format($string, $format){
  return preg_match($format, $string) === 1;
}

/*
is_uploaded_fileはtrue or falseで返す
フォームなどでアップロードされたファイルがPOST通信で送信されてきたものかを確認することができる
想定していない方法でファイルがアップロードされていないかを確認できる
*/
function is_valid_upload_image($image){
  if(is_uploaded_file($image['tmp_name']) === false){
    set_error('ファイル形式が不正です。');
    return false;
  }
  /*
  exif_imagetype　　画像ファイルかどうかを確認
  IMAGETYPE_JPEGやIMAGETYPE_PNGといった定数を返す
  画像形式でなければfalseを返す

  implode　連結したい文字列
  $pieces = ["2018", "01", "01"];
  // 連結文字を指定して連結
  echo implode("-", $pieces).PHP_EOL; // 2018-01-01
  */
  $mimetype = exif_imagetype($image['tmp_name']);
  if( isset(PERMITTED_IMAGE_TYPES[$mimetype]) === false ){
    set_error('ファイル形式は' . implode('、', PERMITTED_IMAGE_TYPES) . 'のみ利用可能です。');
    return false;
  }
  return true;
}

/*
HTMLエンティティは < や > を画面に出力するように用意された文字列
htmlspecialchars( 変換対象, 変換パターン, 文字コード ) 
*/
function h($str){
  return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
}