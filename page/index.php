<?php require('header.php'); ?>
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="d-flex col-12 col-lg-8">
                <div class="card border-0 w-100">
                    <div class="card-header bg-white pb-0 border-0 boardBlock1Title">
                        <h5 class="m-0 pl-4 colorPrimary"><?= trans('Modifié récemment'); ?></h5>
                        <hr class="mx-4">
                    </div>
                    <?php
                    $lastPage = getLastFromDb('plugin_cms');
                    $lastArticle = getLastFromDb('plugin_itemGlue_articles');
                    ?>
                    <div class="card-body pt-0" id="recentUpdates">
                        <?php if (is_array($lastPage) && !isArrayEmpty($lastPage)): ?>
                            <strong><?= trans('Pages'); ?></strong>
                            <div class="my-4">
                                <?php foreach ($lastPage as $page): ?>
                                    <div class="my-2 ml-0 ml-lg-4" style="position: relative;">
                                        <span class="mr-2"><?= $page->name; ?></span>
                                        <span class="visitsStatsBadge bgColorPrimary">
                                        <a href="<?= getPluginUrl('cms/page/pageContent/', $page->id) ?>"
                                           class="btn btn-sm p-0 align-top" title="<?= trans('Consulter'); ?>">
                                                <span class="text-white"><i class="fas fa-cog"></i></span>
                                            </a>
                                            <?php if ($USER->getRole() > 3): ?>
                                                <a href="<?= getPluginUrl('cms/page/update/', $page->id) ?>"
                                                   class="btn btn-sm p-0 align-top" title="<?= trans('Modifier'); ?>">
                                                <span class="text-white"><i class="fas fa-wrench"></i></span>
                                            </a>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (is_array($lastArticle) && !isArrayEmpty($lastArticle)): ?>
                            <strong><?= trans('Articles'); ?></strong>
                            <div class="my-4">
                                <?php foreach ($lastArticle as $article): ?>
                                    <div class="my-2 ml-0 ml-lg-4" style="position: relative;">
                                        <span class="mr-2"><?= $article->name; ?></span>
                                        <span class="visitsStatsBadge bgColorPrimary">
                                        <a href="<?= getPluginUrl('itemGlue/page/articleContent/', $article->id) ?>"
                                           class="btn btn-sm p-0 align-top" title="<?= trans('Consulter'); ?>">
                                            <span class="text-white"><i class="fas fa-cog"></i></span>
                                        </a>
                                            <?php if ($USER->getRole() > 3): ?>
                                                <a href="<?= getPluginUrl('itemGlue/page/update/', $article->id) ?>"
                                                   class="btn btn-sm p-0 align-top" title="<?= trans('Modifier'); ?>">
                                                <span class="text-white"><i class="fas fa-wrench"></i></span>
                                            </a>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
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

        <?php
        $dashboardDetails = includePluginsDashboard();

        if ($USER->getRole() >= 4) {
            $Category = new App\Category();
            $dashboardDetails[] = array(
                'name' => trans('Catégories'),
                'count' => $Category->showAll(true),
                'url' => WEB_ADMIN_URL . 'updateCategories/'
            );
        }

        $File = new App\File();
        $dashboardDetails[] = array(
            'name' => trans('Média'),
            'count' => $File->countFile(true),
            'url' => WEB_ADMIN_URL . 'updateMedia/'
        );
        ?>

        <?php if ($dashboardDetails): ?>
            <div class="row">
                <?php foreach ($dashboardDetails as $dashboard): ?>
                    <?php
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
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
<?php require('footer.php'); ?>