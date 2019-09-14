<?php

define('MODEL_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../model/');
define('VIEW_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../view/');


define('IMAGE_PATH', '/assets/images/');
define('STYLESHEET_PATH', '/assets/css/');
define('IMAGE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/assets/images/' );
//mysql接続データ
define('DB_HOST', 'mysql');
define('DB_NAME', 'sample');
define('DB_USER', 'testuser');
define('DB_PASS', 'password');
define('DB_CHARSET', 'utf8');

//phpファイルを定数に代入

//signup.phpを定数SIGNUP_URLに代入
define('SIGNUP_URL', '/signup.php');
//login.phpを定数LOGIN_URLに代入
define('LOGIN_URL', '/login.php');
//logout.phpを定数LOGOUT_URLに代入
define('LOGOUT_URL', '/logout.php');
//index.phpを定数HOME_URLに代入
define('HOME_URL', '/index.php');
//cart.phpを定数CART_URLに代入
define('CART_URL', '/cart.php');
//finish.phpを定数FINISH_URLに代入
define('FINISH_URL', '/finish.php');
//admin.phpを定数ADMIN_URLに代入
define('ADMIN_URL', '/admin.php');
define('BUY_URL', '/buy.php');
define('BUYS_URL', '/buy_details.php');
define('BUY_ADMIN_URL', '/buy_admin.php');

//バリデーションを定数REGEXP_ALPHANUMERICに代入
define('REGEXP_ALPHANUMERIC', '/\A[0-9a-zA-Z]+\z/');
//バリデーションを定数REGEXP_POSITIVE_INTEGERに代入
define('REGEXP_POSITIVE_INTEGER', '/\A([1-9][0-9]*|0)\z/');

/*ユーザー名に入力制限をかける処理を定数USER_NAME_LENGTH_MINに代入。
最小入力数を６文字とする*/
define('USER_NAME_LENGTH_MIN', 6);

/*ユーザー名に入力制限をかける処理を定数USER_NAME_LENGTH_MAXに代入。
最大入力数を100文字とする*/
define('USER_NAME_LENGTH_MAX', 100);

/*パスワードに入力制限をかける処理を定数USER_PASSWORD_LENGTH_MINに代入
最小入力数を6文字とする*/
define('USER_PASSWORD_LENGTH_MIN', 6);
/*パスワードに入力制限をかける処理を定数USER_PASSWORD_LENGTH_MAXに代入
最大入力数を100文字とする*/
define('USER_PASSWORD_LENGTH_MAX', 100);

/*管理者とユーザーをわける処理をUSER_TYPE_ADMINに代入
1の場合は管理者ページに移動*/ 
define('USER_TYPE_ADMIN', 1);

/*管理者とユーザーをわける処理をUSER_TYPE_ADMINに代入
2の場合は一般ページに移動*/  
define('USER_TYPE_NORMAL', 2);

/*商品名に入力制限をかける処理をITEM_NAME_LENGTH_MINに代入
最小入力値を1とする*/
define('ITEM_NAME_LENGTH_MIN', 1);
/*商品名に入力制限をかける処理をITEM_NAME_LENGTH_MINに代入
最大入力値を100とする*/
define('ITEM_NAME_LENGTH_MAX', 100);

//ステータスが1なら公開
define('ITEM_STATUS_OPEN', 1);

//ステータスが2だったら非公開
define('ITEM_STATUS_CLOSE', 0);

define('PERMITTED_ITEM_STATUSES', array(
  'open' => 1,
  'close' => 0,
));
//イメージ写真はjpg,png拡張子のみ
define('PERMITTED_IMAGE_TYPES', array(
  IMAGETYPE_JPEG => 'jpg',
  IMAGETYPE_PNG => 'png',
));