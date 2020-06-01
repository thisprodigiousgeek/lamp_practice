<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
header('X-Frame-Options: DENY');
session_start();





include_once VIEW_PATH . 'login_view.php';