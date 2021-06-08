<?php
// conf...configの略 configとは設定という意味
// constとは 定数という意味
// ここでは定数を定義しているconst.phpを読み込む
require_once '../conf/const.php';
// MODEL_PATHとはモデルを定義しているディレクトリへの道筋
// この定数はconst.phpに定義されている
// ここではモデルのfunctions.phpを読み込む
require_once MODEL_PATH . 'functions.php';
// ここではモデルのuser.php を読み込む
require_once MODEL_PATH . 'user.php';
// ここではモデルのitem.phpを読み込む
require_once MODEL_PATH . 'item.php';
// ここではモデルのcart.phpを読み込む
require_once MODEL_PATH . 'cart.php';
// セッションを開始する
session_start();

// ランダムな文字を生成して変数に代入
$token = get_csrf_token();

// トークンが取得できたかを確認
if(isset($token)) {
// セッションにトークンを代入
  $_SESSION['csrf_token'] = $token;
}



// be動詞＋過去分詞だと受動態の意味になる
// すなわちis_loginedはログインされているという意味になる
// つまりis_logined()がfalseということはログインされていなければという意味になる
// このis_loginedの関数は9行目から15行目までのmodelのファイルに定義されている
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
// データベースに接続する値を$db変数に代入する
// ユーザーがログインする時の値を$user変数に代入
$db = get_db_connect();
$user = get_login_user($db);
// ユーザーがカートに入れる値を$carts変数に代入
$carts = get_user_carts($db, $user['user_id']);
//$cartsで選んだ商品の合計の金額を合計金額の変数入れる
$total_price = sum_carts($carts);
// includeは含めるという意味
// ここではcart_view.phpをここに取り込む
include_once VIEW_PATH . 'cart_view.php';

