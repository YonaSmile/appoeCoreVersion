<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/main.php');
includePluginsFiles();
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/auth_user.php');

use App\Plugin\Cms\Cms;
use App\Plugin\Cms\CmsMenu;
use App\Plugin\Shop\Product;
use App\Plugin\Shop\ProductContent;

//Check maintenance mode
if (checkMaintenance()) {
    if (!headers_sent()) {
        header('HTTP/1.1 503 Service Unavailable', true, 503);
        header('Status: 503 Service Temporarily Unavailable');
        header('Retry-After: 3600');
    }
    echo file_exists(ROOT_PATH . 'maintenance.php') ? getFileContent(ROOT_PATH . 'maintenance.php') : getAsset('maintenance', true);
    exit();
}

//Backup database
appBackup();
if (class_exists('App\Plugin\Cms\Cms')) {

    //Get Page
    $Cms = new Cms();
    $existPage = false;

    //Get Page parameters
    if (!empty($_GET['slug'])) {

        $existPage = $Cms->showBySlug($_GET['slug'], LANG);

        if (!$existPage) {

            //Check for similar page slug
            if (defined('SIMILAR_PAGES_SLUG') && !isArrayEmpty(SIMILAR_PAGES_SLUG)) {
                if (array_key_exists($_GET['slug'], SIMILAR_PAGES_SLUG)) {
                    $existPage = $Cms->showBySlug(SIMILAR_PAGES_SLUG[$_GET['slug']], LANG);
                    $Cms->setSlug($_GET['slug']);
                }
            }

            if (!$existPage) {

                //Check for other languages
                $testedLang = array(LANG);
                foreach (getLangs() as $minLang => $largeLang) {
                    if (!in_array($minLang, $testedLang)) {
                        $testedLang[] = $minLang;
                        if ($Cms->showBySlug($_GET['slug'], $minLang)) {
                            $existPage = true;
                            break;
                        }
                    }
                }
            }
        }
    } else {
        $existPage = $Cms->showDefaultSlug(LANG);
    }

    //Check if Page exist and accessible
    if (!$existPage || $Cms->getStatut() != 1) {
        if (!headers_sent()) {
            header('HTTP/1.1 404 Not Found', true, 404);
        }
        echo file_exists(ROOT_PATH . '404.php') ? getFileContent(ROOT_PATH . '404.php') : getAsset('404', true);
        exit();
    }

    //Get default page informations
    setPageId($Cms->getId());
    setPageType('PAGE');
    setPageName($Cms->getName());
    setPageMenuName($Cms->getMenuName());
    setPageDescription($Cms->getDescription());
    setPageSlug($Cms->getSlug());
    setPageFilename($Cms->getFilename());

    //Check if is Page or plugin page
    if (!empty($_GET['type'])) {

        if (!empty($_GET['typeSlug'])) {

            $pluginType = getPageTypes($_GET['type']);
            if (false !== $pluginType) {

                $pluginSlug = $_GET['typeSlug'];

                //TYPE ITEMGLUE
                if ($pluginType == 'ITEMGLUE') {

                    if (class_exists('App\Plugin\ItemGlue\Article')) {

                        //Get Article infos
                        $Article = getArticlesBySlug($pluginSlug);

                        //Check if Article exist
                        if ($Article) {

                            setPageId($Article->getId());
                            setPageType('ARTICLE');
                            setPageName($Article->getName());
                            setPageSlug($Article->getSlug());
                            setPageDescription($Article->getDescription());
                            setPageImage(getFirstImage(getFileTemplatePosition($Article->medias, 1, true), '', false, true));
                            setArticle($Article);
                        }
                    }

                    //TYPE SHOP
                } elseif ($pluginType == 'SHOP') {

                    if (class_exists('App\Plugin\Shop\Product')) {

                        //Get Product infos
                        $ProductPage = new Product();
                        $ProductPage->setSlug($pluginSlug);

                        //Check if Product exist
                        if ($ProductPage->showBySlug()) {

                            $ProductPageContent = new ProductContent($ProductPage->getId(), LANG);

                            setPageId($ProductPage->getId());
                            setPageType('SHOP');
                            setPageName($ProductPage->getName());
                            setPageSlug($ProductPage->getSlug());
                            setPageDescription($ProductPageContent->getResume());
                        }
                    }
                }
            }
        }

        //shortcut for articles
    } elseif (!empty($_GET['id'])) {

        if (class_exists('App\Plugin\ItemGlue\Article')) {

            //Get Article infos
            $Article = getArticlesBySlug($_GET['id']);

            //Check if Article exist
            if ($Article) {

                setPageId($Article->getId());
                setPageType('ARTICLE');
                setPageName($Article->getName());
                setPageSlug($Article->getSlug());
                setPageDescription($Article->getDescription());
                setPageImage(getFirstImage(getFileTemplatePosition($Article->medias, 1, true), '', false, true));
                setArticle($Article);
            }
        }

    }

    //Create menu
    if (empty($_SESSION['MENU']) || getSessionLang() !== LANG) {
        setSessionLang();
        $CmsMenu = new CmsMenu();
        $_SESSION['MENU'] = constructMenu($CmsMenu->showAll());
        unset($CmsMenu);
    }

    //Delete vars
    unset($Article, $ProductPage, $existPage, $testedLang, $pluginType, $pluginSlug);
}