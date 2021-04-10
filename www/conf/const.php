<?php

//定数ファイル(コンスタント)

define('MODEL_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../model/');//ドキュメントルート
define('VIEW_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../view/');


define('IMAGE_PATH', '/assets/images/');//画像ファイル
define('STYLESHEET_PATH', '/assets/css/');//CSSファイル
define('IMAGE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/assets/images/' );//

//DB接続情報
define('DB_HOST', 'mysql');
define('DB_NAME', 'sample');
define('DB_USER', 'testuser');
define('DB_PASS', 'password');
define('DB_CHARSET', 'utf8');

//
define('SIGNUP_URL', '/signup.php');
define('LOGIN_URL', '/login.php');
define('LOGOUT_URL', '/logout.php');
define('HOME_URL', '/index.php');
define('CART_URL', '/cart.php');
define('FINISH_URL', '/finish.php');
define('ADMIN_URL', '/admin.php');

//バリデーション
define('REGEXP_ALPHANUMERIC', '/\A[0-9a-zA-Z]+\z/');//英数字かどうか
define('REGEXP_POSITIVE_INTEGER', '/\A([1-9][0-9]*|0)\z/');//整数かどうか

//文字数　下限　上限
define('USER_NAME_LENGTH_MIN', 6);
define('USER_NAME_LENGTH_MAX', 100);
define('USER_PASSWORD_LENGTH_MIN', 6);
define('USER_PASSWORD_LENGTH_MAX', 100);

//管理者　ユーザー
define('USER_TYPE_ADMIN', 1);
define('USER_TYPE_NORMAL', 2);

//商品名の長さ
define('ITEM_NAME_LENGTH_MIN', 1);//商品名最小値
define('ITEM_NAME_LENGTH_MAX', 100);//商品名最大値

//商品ステータス
define('ITEM_STATUS_OPEN', 1);//公開
define('ITEM_STATUS_CLOSE', 0);//非公開

//
define('PERMITTED_ITEM_STATUSES', array(
  'open' => 1,
  'close' => 0,
));

//商品画像のタイプ
define('PERMITTED_IMAGE_TYPES', array(
  IMAGETYPE_JPEG => 'jpg',
  IMAGETYPE_PNG => 'png',
));