<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/main.php');
require_once(WEB_APP_PATH . 'middleware.php');
includePluginsFiles(true);
?>
<!doctype html>
<html lang="<?= LANG; ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" type="image/jpg" href="<?= WEB_APP_URL; ?>images/appoe-favicon.png">
    <meta name="apple-mobile-web-app-title" content="APPOE">
    <meta name="application-name" content="APPOE">
    <title><?= trans($Page->getName()); ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css"
          integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B"
          crossorigin="anonymous">
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css"/>
    <link rel="stylesheet" href="<?= APP_ROOT; ?>css/font.css" type="text/css">
    <link rel="stylesheet" href="<?= WEB_LIB_URL; ?>js/datetimepicker/jquery.datetimepicker.css"
          type="text/css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
    <link href="https://use.fontawesome.com/releases/v5.0.3/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_ROOT; ?>css/appoe.css?v=<?= time(); ?>" type="text/css">
    <link rel="stylesheet"
          href="<?= APP_ROOT; ?>css/<?= file_exists(ROOT_PATH . 'app/css/perso.css') ? 'perso.css' : 'perso_default.css'; ?>?v=<?= time(); ?>"
          type="text/css">
    <?php includePluginsStyles(); ?>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
            integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
            integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
            crossorigin="anonymous"></script>
    <script src="https://cdn.ckeditor.com/4.11.2/full/ckeditor.js"></script>
    <script src="<?= WEB_LIB_URL; ?>js/datetimepicker/jquery.datetimepicker.full.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css"
          integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ"
          crossorigin="anonymous">
    <script type="text/javascript" src="<?= APP_ROOT; ?>js/all.js"></script>
    <script type="text/javascript" src="<?= APP_ROOT; ?>js/functions.js"></script>
    <?php includePluginsJs(true); ?>
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