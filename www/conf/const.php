<?php

define('MODEL_PATH', $_SERVER['DOCUMENT_ROOT'] . '/ecshop/www/model/');
define('VIEW_PATH', $_SERVER['DOCUMENT_ROOT'] . '/ecshop/www/view/');


define('IMAGE_PATH', '/ecshop/www/html/assets/images/');
define('STYLESHEET_PATH', '/ecshop/www/html/assets/css/');
define('IMAGE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/ecshop/www/html/assets/images/' );

define('DB_HOST', 'localhost');
define('DB_NAME', 'codecamp41670');
define('DB_USER', 'codecamp41670');
define('DB_PASS', 'codecamp41670');
define('DB_CHARSET', 'utf8');

define('SIGNUP_URL', '/ecshop/www/html/signup.php');
define('LOGIN_URL', '/ecshop/www/html/login.php');
define('LOGOUT_URL', '/ecshop/www/html/logout.php');
define('HOME_URL', '/ecshop/www/html/index.php');
define('CART_URL', '/ecshop/www/html/cart.php');
define('FINISH_URL', '/ecshop/www/html/finish.php');
define('ADMIN_URL', '/ecshop/www/html/admin.php');

define('REGEXP_ALPHANUMERIC', '/\A[0-9a-zA-Z]+\z/');
define('REGEXP_POSITIVE_INTEGER', '/\A([1-9][0-9]*|0)\z/');


define('USER_NAME_LENGTH_MIN', 6);
define('USER_NAME_LENGTH_MAX', 100);
define('USER_PASSWORD_LENGTH_MIN', 6);
define('USER_PASSWORD_LENGTH_MAX', 100);

define('USER_TYPE_ADMIN', 1);
define('USER_TYPE_NORMAL', 2);

define('ITEM_NAME_LENGTH_MIN', 1);
define('ITEM_NAME_LENGTH_MAX', 100);

define('ITEM_STATUS_OPEN', 1);
define('ITEM_STATUS_CLOSE', 0);

const PERMITTED_ITEM_STATUSES = array(
  'open' => 1,
  'close' => 0,
);

const PERMITTED_IMAGE_TYPES = array(
  IMAGETYPE_JPEG => 'jpg',
  IMAGETYPE_PNG => 'png',
);