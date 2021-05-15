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
// ユーザーが管理者ではない場合、ログインページに飛ぶ
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
// postで送信した値を変数に入れる
$name = get_post('name');
$price = get_post('price');
$status = get_post('status');
$stock = get_post('stock');

$image = get_file('image');
// 商品を登録するとメッセージに商品登録しましたと表示
// されなかった場合、商品の登録に失敗しましたと表示
if(regist_item($db, $name, $price, $stock, $status, $image)){
  set_message('商品を登録しました。');
}else {
  set_error('商品の登録に失敗しました。');
}

// 管理者用のページに飛ぶ
redirect_to(ADMIN_URL);