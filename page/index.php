<?php require('header.php');

//Check for CMS
$lastPage = array();
if (class_exists('App\Plugin\Cms\Cms')) {
    $lastPage = getLastFromDb('plugin_cms_content', 'idCms');
    $Cms = new \App\Plugin\Cms\Cms();
}

//Check for ITEMGLUE
$lastArticle = array();
if (class_exists('App\Plugin\ItemGlue\Article')) {
    $lastArticle = getLastFromDb('plugin_itemGlue_articles_content', 'idArticle');
    $Article = new \App\Plugin\ItemGlue\Article();
}

//Check for SHOP
$lastProducts = array();
if (class_exists('App\Plugin\Shop\Product')) {
    $lastProducts = getLastFromDb('plugin_shop_products_content', 'product_id');
    $Product = new \App\Plugin\Shop\Product();
}
?>
<?= getTitle($Page->getName(), $Page->getSlug()); ?>
    <div class="container-fluid">
        <?= implode('', includePersoPluginsDashboard()); ?>
        <?php if (is_array($lastPage) && !isArrayEmpty($lastPage)): ?>
            <div class="row mb-3">
                <div class="d-flex col-12 col-lg-8">
                    <div class="card border-0 w-100">
                        <div class="card-header bg-white pb-0 border-0 boardBlock1Title">
                            <h5 class="m-0 pl-4 colorPrimary"><?= trans('Modifié récemment'); ?></h5>
                            <hr class="mx-4">
                        </div>

                        <div class="card-body pt-0" id="recentUpdates">
                            <strong><?= trans('Pages'); ?></strong>
                            <div class="my-4">
                                <?php foreach ($lastPage as $id => $idPage):
                                    $Cms->setId($idPage);
                                    if ($Cms->show()): ?>
                                        <div class="my-2 ml-0 ml-lg-4" style="position: relative;">
                                            <span class="mr-2"><?= $Cms->getName(); ?></span>
                                            <span class="visitsStatsBadge bgColorPrimary">
                                        <a href="<?= getPluginUrl('cms/page/pageContent/', $Cms->getId()) ?>"
                                           class="btn btn-sm p-0 align-top" title="<?= trans('Consulter'); ?>">
                                                <span class="text-white"><i class="fas fa-cog"></i></span>
                                            </a>
                                                <?php if (isTechnicien(getUserRoleId())): ?>
                                                    <a href="<?= getPluginUrl('cms/page/update/', $Cms->getId()) ?>"
                                                       class="btn btn-sm p-0 align-top"
                                                       title="<?= trans('Modifier'); ?>">
                                                <span class="text-white"><i class="fas fa-wrench"></i></span>
                                            </a>
                                                <?php endif; ?>
                                        </span>
                                        </div>
                                    <?php endif;
                                endforeach; ?>
                            </div>

                            <?php if (is_array($lastArticle) && !isArrayEmpty($lastArticle)): ?>
                                <strong><?= trans('Articles'); ?></strong>
                                <div class="my-4">
                                    <?php foreach ($lastArticle as $id => $idArticle):
                                        $Article->setId($idArticle);
                                        if ($Article->show()): ?>
                                            <div class="my-2 ml-0 ml-lg-4" style="position: relative;">
                                                <span class="mr-2"><?= $Article->getName(); ?></span>
                                                <span class="visitsStatsBadge bgColorPrimary">
                                                <a href="<?= getPluginUrl('itemGlue/page/articleContent/', $Article->getId()) ?>"
                                                   class="btn btn-sm p-0 align-top" title="<?= trans('Consulter'); ?>">
                                                    <span class="text-white"><i class="fas fa-cog"></i></span>
                                                </a>
                                                    <?php if (isTechnicien(getUserRoleId())): ?>
                                                        <a href="<?= getPluginUrl('itemGlue/page/update/', $Article->getId()) ?>"
                                                           class="btn btn-sm p-0 align-top"
                                                           title="<?= trans('Modifier'); ?>">
                                                <span class="text-white"><i class="fas fa-wrench"></i></span>
                                            </a>
                                                    <?php endif; ?>
                                        </span>
                                            </div>
                                        <?php endif;
                                    endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <?php if (is_array($lastProducts) && !isArrayEmpty($lastProducts)): ?>
                                <strong><?= trans('Produits'); ?></strong>
                                <div class="my-4">
                                    <?php foreach ($lastProducts as $id => $idProduct):
                                        $Product->setId($idProduct);
                                        if ($Product->show()): ?>
                                            <div class="my-2 ml-0 ml-lg-4" style="position: relative;">
                                                <span class="mr-2"><?= $Product->getName(); ?></span>
                                                <span class="visitsStatsBadge bgColorPrimary">
                                                <a href="<?= getPluginUrl('shop/page/updateProductData/', $Product->getId()) ?>"
                                                   class="btn btn-sm p-0 align-top" title="<?= trans('Consulter'); ?>">
                                                    <span class="text-white"><i class="fas fa-cog"></i></span>
                                                </a>
                                                <a href="<?= getPluginUrl('shop/page/updateProductData/', $Product->getId()) ?>"
                                                   class="btn btn-sm p-0 align-top"
                                                   title="<?= trans('Modifier'); ?>">
                                                <span class="text-white"><i class="fas fa-wrench"></i></span>
                                            </a>
                                        </span>
                                            </div>
                                        <?php endif;
                                    endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="d-flex col-12 col-lg-4">
                    <div class="card border-0 w-100">
                        <div class="card-header bg-white pb-0 border-0 boardBlock2Title">
                            <h5 class="m-0 pl-4 colorSecondary"><?= trans('Visiteurs'); ?></h5>
                            <hr class="mx-4">
                        </div>
                        <div class="card-body pt-0" id="visitorsStats"></div>
                    </div>
                </div>
            </div>
        <?php endif;

        $dashboardDetails = includePluginsDashboard();

        if (isTechnicien(getUserRoleId())) {
            $Category = new \App\Category();
            $dashboardDetails[] = array(
                'name' => trans('Catégories'),
                'count' => $Category->showAll(true),
                'url' => WEB_ADMIN_URL . 'updateCategories/'
            );
        }

        $Menu = new \App\Menu();
        if ($Menu->checkUserPermission(getUserRoleId(), 'updateMedia')) {
            $File = new \App\File();
            $dashboardDetails[] = array(
                'name' => trans('Média'),
                'count' => $File->countFile(true),
                'url' => WEB_ADMIN_URL . 'updateMedia/'
            );
        }
        if ($dashboardDetails): ?>
            <div class="row">
                <?php
                $dashboardDetails = transformMultipleArraysTo1($dashboardDetails);
                foreach ($dashboardDetails as $dashboard):
                    if (!isArrayEmpty($dashboard)):
                        $posUrl = strrpos($dashboard['url'], '/', -2);
                        $icon = '';
                        if (false !== $posUrl) {
                            $icon = substr($dashboard['url'], $posUrl + 1, -1);
                        }
                        ?>
                        <div class="col-12 col-lg-4 mb-3">
                            <div class="card d-flex justify-content-start py-4 border-0 dashboardCard">
                                <div class="card-body">
                                    <h2 class="card-title m-0 icon-<?= $icon; ?>"><a
                                                href="<?= $dashboard['url']; ?>"><?= $dashboard['name']; ?></a></h2>
                                    <span class="dashboardNum bgColorPrimary"><?= $dashboard['count']; ?></span>
                                </div>
                                <?php if (!empty($dashboard['html'])): ?>
                                    <div class="d-none d-lg-flex justify-content-around htmlDashboard">
                                        <?= $dashboard['html']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif;
                endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
<?php require('footer.php'); ?>