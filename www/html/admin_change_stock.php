<?php
// conf...configの略 configとは設定という意味
// constとは　定数という意味
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
// be動詞＋過去分詞だと受動態の意味になる
// すなわちis_loginedはログインされているという意味になる
// つまりis_logined()がfalseということはログインされていなければという意味になる
// このis_loginedの関数は9行目から13行目までのmodelのファイルに定義されている
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
// データベースに接続する値を$db変数に代入する
// ユーザーがログインする時の値を$user変数に代入
$db = get_db_connect();

$user = get_login_user($db);
// ユーザーが管理者ではない場合ログインページに飛ぶ
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
// postに送信した値を変数に代入
$item_id = get_post('item_id');
$stock = get_post('stock');
//　在庫数の更新をした時、在庫数を変更しましたと表示
// でなければ在庫数の変更に失敗とエラーメッセージを表示
if(update_item_stock($db, $item_id, $stock)){
  set_message('在庫数を変更しました。');
} else {
  set_error('在庫数の変更に失敗しました。');
}
// 管理者用のページに飛ぶ
redirect_to(ADMIN_URL);