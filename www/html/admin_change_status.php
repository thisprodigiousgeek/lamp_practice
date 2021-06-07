<?php
// conf...configの略 configとは設定という意味
// constとは 定数という意味
// ここでは定数を定義しているconst.phpを読み込む
require_once '../conf/const.php';
// MODEL_PATHとはモデルを定義しているディレクトリへの道筋
// この定数はconst.phpに定義されている
// ここではモデルのfunctions.phpを読み込む
require_once MODEL_PATH . 'functions.php';
// ここではモデルのuser.phpを読み込む
require_once MODEL_PATH . 'user.php';
// ここではモデルのitem.phpを読み込む
require_once MODEL_PATH . 'item.php';
// セッションを開始する
session_start();

// 関数を使わない場合の処理
// if(isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {}
if(is_valid_csrf_token($_POST['csrf_token']) === TRUE) {

// データベースに接続する値を$db変数に代入する
// ユーザーがログインする時の値を$user変数に代入
$db = get_db_connect();

$user = get_login_user($db);
// ユーザーが管理者ではない場合、ログインページに飛ぶ
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
// postで送信した値が変数に代入
$item_id = get_post('item_id');
$changes_to = get_post('changes_to');
// 商品が公開した時はステータスを変更しましたとメッセージを表示
// 商品が非公開した時、ステータスを変更しましたと表示
// どちらでもない場合は不正なリクエストですとエラーメッセージを表示
if($changes_to === 'open'){
  update_item_status($db, $item_id, ITEM_STATUS_OPEN);
  set_message('ステータスを変更しました。');
}else if($changes_to === 'close'){
  update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
  set_message('ステータスを変更しました。');
}else {
  set_error('不正なリクエストです。');
}

// 管理者用のページに飛ぶ
redirect_to(ADMIN_URL);
    
      
} else {
  set_error('不正なリクエストです');
  redirect_to(ADMIN_URL);
}

// be動詞＋過去分詞だと受動態の意味になる
// すなわちis_loginedはログインされているという意味になる
// つまりis_logined()がfalseということはログインされていなければという意味になる
// このis_loginedの関数は9行目から13行目までのmodelのファイルに定義されている
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
