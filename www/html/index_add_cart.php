<?php
// conf...configの略 configとは設定という意味
// constとは　定数という意味
// ここでは定数を定義しているconst.phpを読み込む
require_once '../conf/const.php';
// MODEL_PATHとはモデルを定義しているディレクトリへの道筋
// この定数はconst.phpに定義されている
// ここではモデルのfunctions.phpを読み込む
require_once MODEL_PATH . 'functions.php';
// ここではモデルのuser.phpを読みこむ
require_once MODEL_PATH . 'user.php';
// ここではモデルのitem.phpを読み込む
require_once MODEL_PATH . 'item.php';
// ここではモデルのcart.phpを読み込む
require_once MODEL_PATH . 'cart.php';
// セッション開始
session_start();
// be動詞＋過去分詞だと受動態の意味になる
// すなわちis_loginedはログインされているという意味になる
// つまりis_logined()がfalseということはログインされていなければという意味になる
// ログインページに飛ぶ
// このis_loginedの関数は９行目で読み込んだfunctions.phpに定義されている
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
//データベースに接続する値を$db変数に代入する
//ログインするユーザの値を$user変数に代入する
$db = get_db_connect();
$user = get_login_user($db);

// postに送信したitem_idを$item_di変数に代入
$item_id = get_post('item_id');
// カートに商品を追加したらカートに商品を追加しましたと表示し、でなければカートの更新に失敗しましたと表示する
if(add_cart($db,$user['user_id'], $item_id)){
  set_message('カートに商品を追加しました。');
} else {
  set_error('カートの更新に失敗しました。');
}
// 追加したら商品一覧ページに飛ぶ
redirect_to(HOME_URL);