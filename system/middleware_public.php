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

    //Get Page parameters
    if (!empty($_GET['slug'])) {
        $existPage = $Cms->showBySlug($_GET['slug'], LANG);

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
    } else {
        $existPage = $Cms->showDefaultSlug(LANG);
    }

    //Check if Page exist and accessible
    if (!$existPage || $Cms->getStatut() != 1) {
        header("location: " . WEB_DIR_URL, true, 301);
        exit();
    }

    //Get default page informations
    setPageId($Cms->getId());
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
                        $Article = getArticlesBySlug($_GET['id']);

                        //Check if Article exist
                        if ($Article) {

                            setPageId($Article->getId());
                            setPageName($Article->getName());
                            setPageDescription($Article->getDescription());
                            setPageImage(getFirstImage(getFileTemplatePosition($Article->medias, 1, true), '', false, true));
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
                            setPageName($ProductPage->getName());
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
                setPageName($Article->getName());
                setPageDescription($Article->getDescription());
                setPageImage(getFirstImage(getFileTemplatePosition($Article->medias, 1, true), '', false, true));
            }
        }

    }

    //Create menu
    $CmsMenu = new CmsMenu();
    $_SESSION['MENU'] = constructMenu($CmsMenu->showAll());
}