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
// ここではモデルのcart.phpを読み込む
require_once MODEL_PATH . 'cart.php';
// セッションを開始する
session_start();
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
// カートの中の商品が購入できない場合
//商品が購入できませんでしたとエラーメッセージを表示
// カートページに飛ぶ
if(purchase_carts($db, $carts) === false){
  set_error('商品が購入できませんでした。');
  redirect_to(CART_URL);
} 
//$cartsで選んだ商品の合計の金額を合計金額の変数入れる
$total_price = sum_carts($carts);
// includeは含めるという意味
// ここではview/finish_view.phpをここに取り込む
include_once '../view/finish_view.php';