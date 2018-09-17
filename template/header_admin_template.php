<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/main.php');
require_once(WEB_APP_PATH . 'middleware.php');
includePluginsFiles(true);
$Traduction = new \App\Plugin\Traduction\Traduction(LANG);
?>
<!doctype html>
<html lang="<?= LANG; ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" type="image/png" href="<?= WEB_APP_URL; ?>images/logo_app.png">
    <meta name="apple-mobile-web-app-title" content="APPOE">
    <meta name="application-name" content="APPOE">
    <title><?= trans($Page->getName()); ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
          crossorigin="anonymous">
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css"/>
    <link rel="styleSheet" href="<?= APP_ROOT; ?>css/font.css" type="text/css">
    <link rel="stylesheet" href="<?= WEB_DIR; ?>ressources/jquery-datepicker-effects/jquery-ui.theme.css"
          type="text/css">
    <link rel="stylesheet" href="<?= WEB_DIR; ?>ressources/jquery-datepicker-effects/jquery-ui.structure.css"
          type="text/css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
    <link href="https://use.fontawesome.com/releases/v5.0.3/css/all.css" rel="stylesheet">
    <link rel="styleSheet" href="<?= APP_ROOT; ?>css/appoe.css?v=<?= time(); ?>" type="text/css">
    <link rel="styleSheet"
          href="<?= APP_ROOT; ?>css/<?= file_exists(ROOT_PATH . 'app/css/perso.css') ? 'perso.css' : 'perso_default.css'; ?>?v=<?= time(); ?>"
          type="text/css">
    <?php includePluginsStyles(); ?>
    <script src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
    <script src="https://cdn.ckeditor.com/4.8.0/full/ckeditor.js"></script>
    <script src="<?= WEB_DIR; ?>ressources/jquery-datepicker-effects/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css"
          integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ"
          crossorigin="anonymous">
    <script type="text/javascript" src="<?= APP_ROOT; ?>js/push/bin/push.js"></script>
    <script type="text/javascript" src="<?= APP_ROOT; ?>js/all.js"></script>
    <?php includePluginsJs(); ?>
</head>
<body>
<div id="loader">
    <div class="loaderContent">
        <div class="spinner">
            <div class="rect1"></div>
            <div class="rect2"></div>
            <div class="rect3"></div>
            <div class="rect4"></div>
            <div class="rect5"></div>
        </div>
        <div class="inline"><?= trans('Chargement'); ?></div>
        <div id="loaderInfos"></div>
    </div>
</div>
<div id="site">
    <div id="main">
        <?php include('menuUser.php'); ?>
        <nav id="sidebar" class="bgColorPrimary">
            <?php include('menu.php'); ?>
        </nav>
        <div id="mainContent">
            <div id="base">