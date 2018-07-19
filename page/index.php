<?php require('header.php'); ?>
    <div class="container-fluid">

        <div class="row mb-3">
            <div class="d-flex col-12 col-lg-8">
                <div class="card border-0 w-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="m-0"><?= trans('Modifié récement'); ?></h5>
                    </div>
                    <div class="card-body"></div>
                </div>
            </div>
            <div class="d-flex col-12 col-lg-4">
                <div class="card border-0 w-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="m-0"><?= trans('Visiteurs'); ?></h5>
                    </div>
                    <div class="card-body" id="visitorsStats"></div>
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
                                <span class="dashboardNum"><?= $dashboard['count']; ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
<?php require('footer.php'); ?>