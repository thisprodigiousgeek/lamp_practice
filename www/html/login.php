<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
header('X-Frame-Options: DENY');
session_start();

get_csrf_token();


include_once VIEW_PATH . 'login_view.php';