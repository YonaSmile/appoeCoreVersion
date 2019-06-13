<?php

use App\Plugin\Cms\Cms;
use App\Plugin\Cms\CmsMenu;
use App\Plugin\ItemGlue\Article;
use App\Plugin\Shop\ProductContent;

require_once($_SERVER['DOCUMENT_ROOT'] . '/app/main.php');
includePluginsFiles();
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/auth_user.php');

//Check maintenance mode
if (checkMaintenance()) {
    header('HTTP/1.1 503 Service Unavailable', true, 503);
    header('Status: 503 Service Temporarily Unavailable');
    header('Retry-After: 3600');
    echo file_exists(ROOT_PATH . 'maintenance.php') ? getFileContent(ROOT_PATH . 'maintenance.php') : getAsset('maintenance', true);
    exit();
}

//Backup database
appBackup();

if (class_exists('App\Plugin\Cms\Cms')) {

    //Get needed Models
    $Cms = new Cms();
    $CmsMenu = new CmsMenu();

    //Get Page parameters
    if (!empty($_GET['slug'])) {
        $existPage = $Cms->showBySlug($_GET['slug'], LANG);
    } else {
        $existPage = $Cms->showDefaultSlug(LANG);
    }

    //Check if Page exist and accessible
    if (!$existPage || $Cms->getStatut() != 1) {
        header("HTTP/1.0 404 Not Found", true, 404);
        echo (file_exists(ROOT_PATH . '404.php')) ? getFileContent(ROOT_PATH . '404.php') : getAsset('404', true);
        exit();
    }

    //Get default page informations
    $currentPageID = $Cms->getId();
    $currentPageSlug = $Cms->getSlug();
    $currentPageName = shortenText(trad($Cms->getName()), 70);
    $currentPageDescription = shortenText(trad($Cms->getDescription()), 170);

    //Check if is Page or plugin page
    if (!empty($_GET['type'])) {

        if (!empty($_GET['typeSlug'])) {

            $pluginType = getPageTypes($_GET['type']);
            if (false !== $pluginType) {

                $pluginSlug = $_GET['typeSlug'];

                //TYPE ITEMGLUE
                if ($pluginType == 'ITEMGLUE') {

                    //Get Article infos
                    $ArticlePage = new Article();
                    $ArticlePage->setSlug($pluginSlug);


                    //Check if Article exist
                    if ($ArticlePage->showBySlug()) {

                        $currentPageID = $ArticlePage->getId();
                        $currentPageName = shortenText(trad($ArticlePage->getName()), 70);
                        $currentPageDescription = shortenText(trad($ArticlePage->getDescription()), 170);
                    }

                    //TYPE SHOP
                } elseif ($pluginType == 'SHOP') {

                    //Get Product infos
                    $ProductPage = new \App\Plugin\Shop\Product();
                    $ProductPage->setSlug($pluginSlug);

                    //Check if Product exist
                    if ($ProductPage->showBySlug()) {

                        $ProductPageContent = new ProductContent($ProductPage->getId(), LANG);

                        $currentPageID = $ProductPage->getId();
                        $currentPageName = shortenText(trad($ProductPage->getName()), 70);
                        $currentPageDescription = shortenText(trad($ProductPageContent->getResume()), 170);
                    }
                }
            }
        }

        //shortcut for articles
    } elseif (!empty($_GET['id'])) {

        //Get Article infos
        $ArticlePage = new Article();
        $ArticlePage->setSlug($_GET['id']);


        //Check if Article exist
        if ($ArticlePage->showBySlug()) {

            $currentPageID = $ArticlePage->getId();
            $currentPageName = shortenText(trad($ArticlePage->getName()), 70);
            $currentPageDescription = shortenText(trad($ArticlePage->getDescription()), 170);
        }

    }

    //Set page infos
    $_SESSION['currentPageID'] = $currentPageID;
    $_SESSION['currentPageSlug'] = $currentPageSlug;
    $_SESSION['currentPageName'] = $currentPageName;
    $_SESSION['currentPageDescription'] = $currentPageDescription;

    //Create menu
    $_SESSION['MENU'] = constructMenu($CmsMenu->showAll());
}