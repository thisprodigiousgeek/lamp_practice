<?php
// conf...confingの略　confingは設定という意味
// constとは　定義という意味
// ここでは定数を定義しているconst.phpを読み込む
require_once '../conf/const.php';
// MODEL_PATHとはモデルを定義しているディレクトリへの道筋
// この定数はconst.phpに定義されている
// ここではモデルのfunction.phpを読み込む
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
// このis_loginedの関数は９行目から13行目のmodelファイルに定義されている
if(is_logined() === false){
// ログインされていなければ商品一覧ページに飛ぶことができないため、ログインページに飛ぶ
  redirect_to(LOGIN_URL);
}
// $db変数にデータベースに接続の値を入れる
// $user変数にユーザーがログインした時の値を入れる
// items変数に商品の値を入れる
$db = get_db_connect();
$user = get_login_user($db);

$items = get_open_items($db);
// includeは含めるという意味
// ここではindex_view.phpをここに取り込む
include_once VIEW_PATH . 'index_view.php';