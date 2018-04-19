<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/main.php');
includePluginsFiles();
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/auth_user.php');

//check maintenance mode
if (!checkMaintenance() && pageSlug() != 'hibour') {
    header('HTTP/1.1 503 Service Unavailable');
    header('Status: 503 Service Temporarily Unavailable');
    header('Retry-After: 3600');
    echo getFileContent($_SERVER['DOCUMENT_ROOT'] . '/maintenance.php');
    exit();
}

$Cms = new App\Plugin\Cms\Cms();
$CmsMenu = new App\Plugin\Cms\CmsMenu();
$Traduction = new App\Plugin\Traduction\Traduction(defined(LANG) ? LANG : 'fr');

//Get Page parameters
$Cms->setSlug(!empty($_GET['slug']) ? $_GET['slug'] : (pageSlug() != 'index' ? pageSlug() : 'home'));
$existPage = $Cms->showBySlug();

//Check if Page exist and accessible
if ((!$existPage && pageName() == 'Non définie') || $Cms->getStatut() != 1) {
    header('location:' . WEB_DIR_URL);
    exit();
}

if (!empty($_GET['id'])) {

    //get Article infos
    $articleDetails = getSpecificArticlesDetailsBySlug($_GET['id']);
    $Article = $articleDetails['article'];
    $ArticleContent = $articleDetails['content'];
    $currentPageName = shortenText($Traduction->trans($Article->getName()), 70);
    $currentPageDescription = shortenText($ArticleContent->getContent(), 170);

} else {

    //get Page infos
    $currentPageName = shortenText($Traduction->trans($Cms->getName()), 70);
    $currentPageDescription = shortenText($Traduction->trans($Cms->getDescription()), 170);
}


//set page infos
$_SESSION['currentPageName'] = $existPage ? $currentPageName : trans(pageName());
$_SESSION['currentPageDescription'] = $existPage ? $currentPageDescription : trans(pageDescription());

//Create menu
$menu = constructMenu($CmsMenu->showAll());
