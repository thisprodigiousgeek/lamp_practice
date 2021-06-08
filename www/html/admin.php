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

// ランダムな文字列を生成して変数に代入
$token = get_csrf_token();

//トークンが取得できたか確認
if(isset($token)) {
// セッションにトークンを保存
  $_SESSION['csrf_token'] = $token;
}

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
// ユーザーが管理者ではない場合、ログインページに飛ぶ
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
// データベースの全ての商品を$items変数に代入
$items = get_all_items($db);
// includeは含めるという意味
// ここではadmin_view.phpをここに取り込む
include_once VIEW_PATH . '/admin_view.php';
