<?php
// conf...configの略 configとは設定という意味
// constとは　定数という意味
// ここでは定数を定義しているconst.phpを読み込む
require_once '../conf/const.php';
// MODEL_PATHとはモデルを定義しているディレクトリへの道筋
// この定数はconst.phpに定義されている
// ここではモデルのfanctions.phpを読み込む
require_once MODEL_PATH . 'functions.php';
// セッションを開始する
session_start();
// be動詞＋過去分詞だと受動態の意味になる
// すなわちis_loginedはログインされているという意味になる
// つまりis_logined()がtrueということはログインされていればという意味になる
// このis_loginedの関数は９行目で読み込んだfunctions.phpに定義されている
if(is_logined() === true){
  // すでにログイン済みであればユーザー登録ページを表示する必要がないので商品一覧ページに飛ばす
  redirect_to(HOME_URL);
}
// includeは含めるという意味
// ここではsignup_view.phpをここに取り込む
include_once VIEW_PATH . 'signup_view.php';



