<?php
//configurationは日本語で「設定」
define('MODEL_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../model/');//そのファイルに行くまでの道標をセッション箱に入れてる
define('VIEW_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../view/');//そのファイルに行くまでの道標セッション箱に入れてる


define('IMAGE_PATH', '/assets/images/');//そのファイルに行くまでの道標
define('STYLESHEET_PATH', '/assets/css/');//そのファイルに行くまでの道標
define('IMAGE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/assets/images/' );//そのファイルに行くまでの道標セッション箱に入れてる

define('DB_HOST', 'mysql');//データベースのホスト名
define('DB_NAME', 'sample');//データベースのお名前
define('DB_USER', 'testuser');//ログインするユーザー名
define('DB_PASS', 'password');//ログインするパスワード
define('DB_CHARSET', 'utf8');//日本語

define('SIGNUP_URL', '/signup.php');//行きたいディレクトリにあだ名付けてる
define('LOGIN_URL', '/login.php');//行きたいディレクトリにあだ名付けてる
define('LOGOUT_URL', '/logout.php');//行きたいディレクトリにあだ名付けてる
define('HOME_URL', '/index.php');//行きたいディレクトリにあだ名付けてる
define('CART_URL', '/cart.php');//行きたいディレクトリにあだ名付けてる
define('FINISH_URL', '/finish.php');//行きたいディレクトリにあだ名付けてる
define('ADMIN_URL', '/admin.php');//行きたいディレクトリにあだ名付けてる

define('REGEXP_ALPHANUMERIC', '/\A[0-9a-zA-Z]+\z/');//バリデのもと
define('REGEXP_POSITIVE_INTEGER', '/\A([1-9][0-9]*|0)\z/');//バリデのもと


define('USER_NAME_LENGTH_MIN', 6);//文字数を制限するもと
define('USER_NAME_LENGTH_MAX', 100);//文字数を制限するもと
define('USER_PASSWORD_LENGTH_MIN', 6);//文字数を制限するもと
define('USER_PASSWORD_LENGTH_MAX', 100);//文字数を制限するもと

define('USER_TYPE_ADMIN', 1);//管理者番号
define('USER_TYPE_NORMAL', 2);//管理者番号

define('ITEM_NAME_LENGTH_MIN', 1);//文字数を制限するもと
define('ITEM_NAME_LENGTH_MAX', 100);//文字数を制限するもと

define('ITEM_STATUS_OPEN', 1);//ステータスの設定
define('ITEM_STATUS_CLOSE', 0);//ステータスの設定
//
define('PERMITTED_ITEM_STATUSES', array(
  'open' => 1,
  'close' => 0,
));//管理者のステータス設定

define('PERMITTED_IMAGE_TYPES', array(
  IMAGETYPE_JPEG => 'jpg',
  IMAGETYPE_PNG => 'png',
));//画像タイプ設定

header('X-FRAME-OPTIONS: DENY');