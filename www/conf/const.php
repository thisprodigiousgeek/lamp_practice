<?php
// modelディレクトリ
define('MODEL_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../model/');
// viewディレクトリ
define('VIEW_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../view/');

// imgディレクトリ
define('IMAGE_PATH', '/assets/images/');
// cssディレクトリ
define('STYLESHEET_PATH', '/assets/css/');
// imgディレクトリ
define('IMAGE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/assets/images/' );

// DBログイン情報
define('DB_HOST', 'mysql');
define('DB_NAME', 'sample');
define('DB_USER', 'testuser');
define('DB_PASS', 'password');
define('DB_CHARSET', 'utf8');

// コード
define('SIGNUP_URL', '/signup.php');
define('LOGIN_URL', '/login.php');
define('LOGOUT_URL', '/logout.php');
define('HOME_URL', '/index.php');
define('CART_URL', '/cart.php');
define('FINISH_URL', '/finish.php');
define('ADMIN_URL', '/admin.php');

// 半角英数字の正規表現
define('REGEXP_ALPHANUMERIC', '/\A[0-9a-zA-Z]+\z/');
// 自然数の正規表現
define('REGEXP_POSITIVE_INTEGER', '/\A([1-9][0-9]*|0)\z/');

// ユーザーネーム、パスワードの下限、上限
define('USER_NAME_LENGTH_MIN', 6);
define('USER_NAME_LENGTH_MAX', 100);
define('USER_PASSWORD_LENGTH_MIN', 6);
define('USER_PASSWORD_LENGTH_MAX', 100);

//管理者と一般ユーザーの区別
define('USER_TYPE_ADMIN', 1);
define('USER_TYPE_NORMAL', 2);

// アイテム名の下限、上限
define('ITEM_NAME_LENGTH_MIN', 1);
define('ITEM_NAME_LENGTH_MAX', 100);

//公開、非公開の区別
define('ITEM_STATUS_OPEN', 1);
define('ITEM_STATUS_CLOSE', 0);

// post時の公開、非公開の区別
define('PERMITTED_ITEM_STATUSES', array(
  'open' => 1,
  'close' => 0,
));

// jpgとpngの区別
define('PERMITTED_IMAGE_TYPES', array(
  IMAGETYPE_JPEG => 'jpg',
  IMAGETYPE_PNG => 'png',
));