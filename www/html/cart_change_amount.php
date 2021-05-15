<?php
// conf...configの略 configとは設定という意味
// constとは　定数という意味
// ここでは定数を定義しているconst.phpを読み込む
require_once '../conf/const.php';
// MODEL_PATHとはモデルを定義しているディレクトリへの道筋
// この定数はconst.phpに定義されている
// ここではモデルのfunctions.phpを読み込む
require_once MODEL_PATH . 'functions.php';
// ここではモデルのuserr.phpを読み込む
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
// postに送信したcart_idとamountを変数に代入
$cart_id = get_post('cart_id');
$amount = get_post('amount');
// カートの中身を更新すると購入数を更新しましたとメッセージを表示
// エラーの場合は購入数の更新に失敗しました。
if(update_cart_amount($db, $cart_id, $amount)){
  set_message('購入数を更新しました。');
} else {
  set_error('購入数の更新に失敗しました。');
}
// カートページに飛ぶ
redirect_to(CART_URL);