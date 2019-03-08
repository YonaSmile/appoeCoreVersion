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
            setcookie('LANG', $_POST['lang'], strtotime('+30 days'), WEB_DIR, '', false, true);
        }

        echo 'true';
        exit();
    }
}