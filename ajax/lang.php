<?php
require_once('header.php');
if (checkAjaxRequest()) {

    if (!empty($_POST['lang']) && !empty($_POST['interfaceLang'])) {

        if ($_POST['interfaceLang'] == 'content') {

            //set lang for app content and website
            $_COOKIE['LANG'] = $_POST['lang'];

        } elseif ($_POST['interfaceLang'] == 'interface') {

            //set lang for app interface
            $_COOKIE['APP_LANG'] = $_POST['lang'];

        } else {

            //default set lang for app content and website
            $_COOKIE['LANG'] = $_POST['lang'];
        }

        echo 'true';
    }
}