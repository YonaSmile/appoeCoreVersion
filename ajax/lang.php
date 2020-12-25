<?php
require_once('header.php');
if (checkAjaxRequest()) {

    if (!empty($_POST['lang']) && !empty($_POST['interfaceLang'])) {

        if ($_POST['interfaceLang'] == 'interface') {

            //set lang for app content interface
            $_SESSION['APP_LANG'] = $_POST['lang'];

        } else {

            //TODO if ($_POST['interfaceLang'] == 'content') {}

            //default set lang for app content and website
            $options = array ('expires' => time() + ( 12 * 3600 ), 'path' => WEB_DIR, 'secure' => false, 'httponly' => true, 'samesite' => 'Strict');
            setcookie('LANG', $_POST['lang'], $options);
        }

        echo 'true';
        exit();
    }
}