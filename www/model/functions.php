<?php

function dd($var){//
  var_dump($var);//$varnの型とかまで細かく調べる
  exit();//出ていく
}

function redirect_to($url){//なにかしらのurlにリダイレクトする関数
  header('Location: ' . $url);//urlに飛ぶ
  exit;//今いるページから出ていく
}

function get_get($name){//何かしらゲットされた名前を取得する関数
  if(isset($_GET[$name]) === true){//nameは存在してるんか？===してる！
    return $_GET[$name];//してる！なら$_GET[$name]使っていいで
  };//
  return '';//空っぽ返す
}

function get_post($name){//何かしらポストされた名前を取得する関数
  if(isset($_POST[$name]) === true){//nameは存在してるんか？===してる！
    return $_POST[$name];//してる！なら$_POST[$name]使っていいで
  };
  return '';//空っぽ返す
}

function get_file($name){//何かしらゲットされたファイルを取得する関数
  if(isset($_FILES[$name]) === true){//nameはファイルとして存在してるんか？===してる！
    return $_FILES[$name];//してる！なら$_FILES[$name]使っていいで
  };
  return array();//してへんかったら空っぽ配列返す
}

function get_session($name){//ログインしてる人が誰なん？セッションで名前取得する関数
  if(isset($_SESSION[$name]) === true){//nameはセッション箱に存在してるんか？===してる！
    return $_SESSION[$name];//してる！なら$_SESSION[$name]使っていいで
  };
  return '';//してへんかったら空っぽ配列返す
}

function set_session($name, $value){//さていよいよセッション箱に入れましょか関数
  $_SESSION[$name] = $value;//セッション箱に入れる何かしらの名前取得したら、$valueっていう何かしらのあだ名つける
}

function set_error($error){//エラーかましてきたらどうすんの？関数
  $_SESSION['__errors'][] = $error;//セッション箱の__errorsのなかに入れて、$errorっていうあだ名つける
}

function get_errors(){//エラーたちをもらってもうたときの関数
  $errors = get_session('__errors');//$errorsはすぐ上の「セッションがエラーかましてきたらどうすんの？関数」でもらったエラーたち
  if($errors === ''){//でもよう見たらエラー無いやん！ってなったら
    return array();//空にしてなかったことにする
  }
  set_session('__errors',  array());//「さていよいよセッション箱に入れましょか関数」再び
  return $errors;//__errorsがおったら、やっぱりエラーあるやんってことで$errorsにあげちゃう
}

function has_error(){//エラーあるぞ！関数
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;//__errorsは存在してて、かつ、__errorsが1個でも持ってるやん！な状態
}

function set_message($message){//セッションにメッセージ入れたいときの関数
  $_SESSION['__messages'][] = $message;//セッション箱の'__messagesの中に、$messageを入れる
}

function get_messages(){//メッセージたちを手に入れたときの関数
  $messages = get_session('__messages');//セッション箱にいれたメッセージたちには$messagesっていうあだ名つける
  if($messages === ''){//でもそれが空っぽやったら
    return array();//なかったことにする
  }
  set_session('__messages',  array());//「セッションにメッセージ入れたいときの関数」再び
  return $messages;//$messagesっていうあだ名つける
}

function is_logined(){//ログインしてるで関数
  return get_session('user_id') !== '';//「ログインしてる人が誰なん？セッションで名前取得する関数」再び。user_idが空ちゃう状態
}

function get_upload_filename($file){//ファイルをアップロードする関数
  if(is_valid_upload_image($file) === false){//バリデ失敗したら
    return '';//空欄にして返す
  }
  $mimetype = exif_imagetype($file['tmp_name']);//
  $ext = PERMITTED_IMAGE_TYPES[$mimetype];//指定した拡張子にあだ名つける
  return get_random_string() . '.' . $ext;//ランダムな名前に指定されてる拡張子くっつけるやつ返す
}

function get_random_string($length = 20){//ランダムナファイル名つける関数（最大２０文字）
  return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);//
}//substrは$length分の文字を返す
//base_convertハッシュドポテトしたやつは１６文字から３６文字以内にする
//hashはsha256っていう概念でハッシュドポテト
//sha（Secure Hash Algorithm）とは、ハッシュ関数の入力値を別の値に変換する仕組み。ハッシュ関数はもとに戻したり元の数値わかったりできひん
//uniqidは唯一のidを返す

function save_image($image, $filename){//ファイルを保存する関数
  return move_uploaded_file($image['tmp_name'], IMAGE_DIR . $filename);//$image['tmp_name']からIMAGE_DIR . $filenameにファイルを移動さす
}

function delete_image($filename){//イメージを消す関数
  if(file_exists(IMAGE_DIR . $filename) === true){//ファイルが存在してたら
    unlink(IMAGE_DIR . $filename);//削除する
    return true;//よしできた
  }
  return false;//処理やめぴ
  
}



function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){//文字数をバリデ
  $length = mb_strlen($string);//何かしら（$string）の文字数数えて、あだ名つける
  return ($minimum_length <= $length) && ($length <= $maximum_length);//文字数は$length以上でも以下でもあかんで
}

function is_alphanumeric($string){//何かしらをバリデする関数
  return is_valid_format($string, REGEXP_ALPHANUMERIC);//バリデ完了
}

function is_positive_integer($string){//何かしらをバリデする関数
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);//バリデ完了
}

function is_valid_format($string, $format){//バリデのルール決める関数
  return preg_match($format, $string) === 1;//$formatを見本にして、マッチしてるか確認してマッチ状態を返す
}

//mimeタイプとは、メールやホームページのファイルにくっつけて送られる「このファイルは、こんな種類のファイルですよ」な情報
function is_valid_upload_image($image){//アップロードするファイルをバリデする関数
  if(is_uploaded_file($image['tmp_name']) === false){//もし入れた画像の拡張子が指定したやつとちゃうかったら
    set_error('ファイル形式が不正です。');//エラーメッセージをセッション箱に入れる
    return false;//処理やめぴ
  }
  $mimetype = exif_imagetype($image['tmp_name']);//画像の型を定義する関数にあだ名つける
  if( isset(PERMITTED_IMAGE_TYPES[$mimetype]) === false ){//拡張子が指定してるやつとして存在してたら
    set_error('ファイル形式は' . implode('、', PERMITTED_IMAGE_TYPES) . 'のみ利用可能です。');//エラーメッセージをセッション箱に入れる
    return false;//処理やめぴ
  }
  return true;//バリデできたで
}

function h($str){
  return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
}

// トークンの生成
function get_csrf_token(){
  // get_random_string()はユーザー定義関数。
  $token = get_random_string(30);
  // set_session()はユーザー定義関数。
  set_session('csrf_token', $token);
  return $token;
}

// トークンのチェック
function is_valid_csrf_token($token){
  if($token === '') {
    return false;
  }
  return $token === get_session('csrf_token');
}


