<?php
// conf...confingの略 confingは設定という意味
// constとは 定義という意味
// ここでは定数を定義しているconst.phpを読み込む
require_once '../conf/const.php';
// MODEL_PATHとはモデルを定義しているディレクトリへの道筋
// この定数はconst.phpに定義されている
// ここではモデルのfunction.phpを読み込む
require_once MODEL_PATH . 'functions.php';
// ここではモデルのuser.phpを読み込む
require_once MODEL_PATH . 'user.php';
// セッションを開始する
session_start();

// 関数を使わない場合の処理
// if(isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {}
  
if(is_valid_csrf_token($_POST['csrf_token']) === TRUE) {

  // postに送信したユーザー名とパスワードを変数に入れる
  $name = get_post('name');
  $password = get_post('password');
  // データベースに接続する値を変数に入れる
  $db = get_db_connect();

  // $user変数にログインするときの値を入れる
  // ユーザー名とパスワードが違う場合エラーメッセージを表示し、ログインページに飛ぶ
  // ログインできたら商品一覧ページに飛ぶ
  $user = login_as($db, $name, $password);
  if( $user === false){
    set_error('ログインに失敗しました。');
    redirect_to(LOGIN_URL);
  }
  // adminでログインした時、管理者用ページに飛ぶ
  set_message('ログインしました。');
  if ($user['type'] === USER_TYPE_ADMIN){
    redirect_to(ADMIN_URL);
  }
} else {
  set_error('不正なリクエストです');
  redirect_to(LOGIN_URL);
}



// be動詞＋過去分詞だと受動態の意味になる
// すなわちis_loginedはログインされているという意味になる
// つまり、is＿logined()がtrueということはログインされていればという意味になる
// このis_loginedの関数は９行目から11行目のmodelファイルに定義されている
if(is_logined() === true){
// すでにログイン済みあればユーザー登録ページを表示する必要がないので商品一覧ページに飛ぶ
  redirect_to(HOME_URL);
}



