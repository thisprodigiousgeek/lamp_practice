<?php
// conf...confingの略 confingは設定という意味
// constとは 定義という意味
// ここでは定数を定義しているconst.phpを読み込む
require_once '../conf/const.php';
// MODEL_PATHとはモデルを定義しているディレクトリへの道筋
// この定数はconst.phpに定義されている
// ここではモデルのfunctions.phpを読み込む
require_once MODEL_PATH . 'functions.php';
// ここではモデルのuser.phpを読み込む
require_once MODEL_PATH . 'user.php';
// セッションを開始する
session_start();

if(is_valid_csrf_token($_POST['csrf_token']) === TRUE) {
  // postに送信したユーザー名を$name変数に代入
  // postに送信したパスワードを$password変数に代入
  // postに送信したパスワード確認の値を$password_confirmation変数に代入
  // データベース接続の値を$db変数に代入
  $name = get_post('name');
  $password = get_post('password');
  $password_confirmation = get_post('password_confirmation');
  
  $db = get_db_connect();
  // try~catch（例外処理）
  // エラーが発生した場合、現在の処理を中断して別の処理を行うことができる
  try{
  // ユーザーを登録の結果の値を$result変数に代入
    $result = regist_user($db, $name, $password, $password_confirmation);
  // $result変数の値がfalseだった場合エラーメッセージを表示し、新規登録ページに飛ぶ
    if( $result=== false){
      set_error('ユーザー登録に失敗しました。');
      redirect_to(SIGNUP_URL);
    }
  // 例外として、PDOExceptionを投げる。
  // try文の実行途中で中断し、catch文に移り、エラーメッセージを表示する。 
  }catch(PDOException $e){
    set_error('ユーザー登録に失敗しました。');
    redirect_to(SIGNUP_URL);
  }
  //ユーザー登録ができたら、商品一覧ページに飛ぶ
  set_message('ユーザー登録が完了しました。');
  login_as($db, $name, $password);
  redirect_to(HOME_URL);
  } else {
    set_error('不正なリクエストです');
    redirect_to(SIGNUP_URL);
  }

// be動詞＋過去分詞だと受動態の意味になる
// すなわちis_loginedはログインされているという意味になる
// つまりis_logined()がtrueということはログインされていればという意味になる
// このis_loginedの関数は９行目から11行目のmodelファイルに定義されている
if(is_logined() === true){
// 新規登録をしてログインしたので、商品一覧ページに飛ぶ
  redirect_to(HOME_URL);
}


