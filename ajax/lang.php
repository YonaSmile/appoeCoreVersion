<?php
require_once('header.php');
if (checkAjaxRequest()) {
    if (!empty($_POST['lang'])) {
        $_COOKIE['LANG'] = $_POST['lang'];
        echo 'true';
    }
}