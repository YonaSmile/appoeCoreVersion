<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/main.php');
includePluginsFiles();

$Cms = new App\Plugin\Cms\Cms();

//Get Page parameters
$Cms->setSlug(!empty($_GET['slug']) ? $_GET['slug'] : '');
$existPage = $Cms->showBySlug();

//Check if Page exist and accessible
if ((!$existPage && pageName() == 'Non définie') || $Cms->getStatut() != 1) {
    echo getContainerErrorMsg('Cette page n\'existe pas');
    exit();
}

$CmsContent = new App\Plugin\Cms\CmsContent($Cms->getId(), LANG);
$allContentArr = $CmsContent->getData();

$pageContent = getContainerErrorMsg('Cette page n\'existe pas');
$pageContent = showTemplateContent(TEMPLATES_PATH . $Cms->getSlug() . '.php', extractFromObjArr($allContentArr, 'metaKey'));

echo $pageContent;