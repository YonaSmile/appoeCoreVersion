<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/main.php');
includePluginsFiles();
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/auth_user.php');

//Check maintenance mode
if (!checkMaintenance() && pageSlug() != 'hibour') {
    header('HTTP/1.1 503 Service Unavailable');
    header('Status: 503 Service Temporarily Unavailable');
    header('Retry-After: 3600');
    echo getFileContent($_SERVER['DOCUMENT_ROOT'] . '/maintenance.php');
    exit();
}

//Backup database
appBackup();

//Get needed Models
$Cms = new \App\Plugin\Cms\Cms();
$CmsMenu = new \App\Plugin\Cms\CmsMenu();
$Traduction = new \App\Plugin\Traduction\Traduction(defined('LANG') ? LANG : 'fr');

//Get Page parameters
$Cms->setSlug(!empty($_GET['slug']) ? $_GET['slug'] : (pageSlug() != 'index' && pageSlug() != '' ? pageSlug() : 'home'));
$existPage = $Cms->showBySlug();

//Check if Page exist and accessible
if ((!$existPage && pageName() == 'Non définie') || $Cms->getStatut() != 1) {
    header('location:' . WEB_DIR_URL);
    exit();
}

//default page infos
$currentPageID = 0;
$currentPageName = WEB_TITLE;
$currentPageDescription = WEB_TITLE;

//Check if is Page or plugin page
if (!empty($_GET['type'])) {

    if (!empty($_GET['id'])) {

        $pluginType = getPageTypes($_GET['type']);
        if (false !== $pluginType) {

            $pluginSlug = $_GET['id'];

            if ($pluginType == 'ITEMGLUE') {

                //Get Article infos
                $ArticlePage = new \App\Plugin\ItemGlue\Article();
                $ArticlePage->setSlug($pluginSlug);


                //Check if Article exist
                if ($ArticlePage->showBySlug()) {

                    $currentPageID = $ArticlePage->getId();
                    $currentPageName = shortenText($Traduction->trans($ArticlePage->getName()), 70);
                    $currentPageDescription = shortenText($ArticlePage->getDescription(), 170);
                }

            } elseif ($pluginType == 'SHOP') {

                //Get Product infos
                $ProductPage = new \App\Plugin\Shop\Product();
                $ProductPage->setSlug($pluginSlug);

                //Check if Product exist
                if ($ProductPage->showBySlug()) {

                    $ProductPageContent = new \App\Plugin\Shop\ProductContent($ProductPage->getId(), LANG);

                    $currentPageID = $ProductPage->getId();
                    $currentPageName = shortenText($Traduction->trans($ProductPage->getName()), 70);
                    $currentPageDescription = shortenText($ProductPageContent->getResume(), 170);
                }
            }
        }
    }

    //shortcut for articles
} elseif (!empty($_GET['id'])) {

    //Get Article infos
    $ArticlePage = new \App\Plugin\ItemGlue\Article();
    $ArticlePage->setSlug($_GET['id']);


    //Check if Article exist
    if ($ArticlePage->showBySlug()) {

        $currentPageID = $ArticlePage->getId();
        $currentPageName = shortenText($Traduction->trans($ArticlePage->getName()), 70);
        $currentPageDescription = shortenText($ArticlePage->getDescription(), 170);
    }

} else {

    //Get Page infos
    $currentPageID = $Cms->getId();
    $currentPageName = shortenText($Traduction->trans($Cms->getName()), 70);
    $currentPageDescription = shortenText($Traduction->trans($Cms->getDescription()), 170);
}

//Set page infos
$_SESSION['currentPageID'] = $existPage ? $currentPageID : 0;
$_SESSION['currentPageName'] = $existPage ? $currentPageName : trans(pageName());
$_SESSION['currentPageDescription'] = $existPage ? $currentPageDescription : trans(pageDescription());

//Create menu
$_SESSION['MENU'] = constructMenu($CmsMenu->showAll());