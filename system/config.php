<?php

use App\AppConfig;

$AppConfig = new AppConfig();
$Config = $AppConfig->get();

if ($Config) {

    $ConfigBack = $Config['back'];
    $ConfigFront = $Config['front'];

    if (array_key_exists('forceHTTPS', $ConfigFront) && true === $ConfigFront['forceHTTPS']) {

        if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {

            if (!headers_sent()) {

                header("Status: 301 Moved Permanently");
                header(sprintf('Location: https://%s%s', $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']));
                exit();
            }
        }
    }
}