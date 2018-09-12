<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/ini.php');
require(WEB_APP_PATH . 'Autoloader.php');
\App\Autoloader::register();
require_once(WEB_APP_PATH . 'functions.php');
