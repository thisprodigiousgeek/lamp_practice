<?php

define('MODEL_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../model/');
define('VIEW_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../view/');

// imagesフォルダの相対パス
define('IMAGE_PATH', '/assets/images/');
// cssフォルダの相対パス
define('STYLESHEET_PATH', '/assets/css/');

define('IMAGE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/assets/images/' );

// DB関係の定数
define('DB_HOST', 'mysql');
define('DB_NAME', 'sample');
define('DB_USER', 'testuser');
define('DB_PASS', 'password');
define('DB_CHARSET', 'utf8');

// 各ファイルのパス
define('SIGNUP_URL', '/signup.php');
define('LOGIN_URL', '/login.php');
define('LOGOUT_URL', '/logout.php');
define('HOME_URL', '/index.php');
define('CART_URL', '/cart.php');
define('FINISH_URL', '/finish.php');
define('ADMIN_URL', '/admin.php');

// 正規表現

// 半角英数字
define('REGEXP_ALPHANUMERIC', '/\A[0-9a-zA-Z]+\z/');
// 0以上の整数
define('REGEXP_POSITIVE_INTEGER', '/\A([1-9][0-9]*|0)\z/');

// ユーザー名最小文字数=6
define('USER_NAME_LENGTH_MIN', 6);
// ユーザー名最大文字数=100
define('USER_NAME_LENGTH_MAX', 100);
// ユーザーパスワード最小文字数=6
define('USER_PASSWORD_LENGTH_MIN', 6);
// ユーザーパスワード最大文字数=100
define('USER_PASSWORD_LENGTH_MAX', 100);

// 管理ユーザー=1
define('USER_TYPE_ADMIN', 1);
// 一般ユーザー=2
define('USER_TYPE_NORMAL', 2);

// 商品名最小文字数=1
define('ITEM_NAME_LENGTH_MIN', 1);
// 商品名最大文字数=100
define('ITEM_NAME_LENGTH_MAX', 100);

// 公開=1
define('ITEM_STATUS_OPEN', 1);
// 非公開=0
define('ITEM_STATUS_CLOSE', 0);

// ステータスの連想配列
define('PERMITTED_ITEM_STATUSES', array(
  'open' => 1,
  'close' => 0,
));

// 画像ファイルのタイプ指定
define('PERMITTED_IMAGE_TYPES', array(
  IMAGETYPE_JPEG => 'jpg',
  IMAGETYPE_PNG => 'png',
));