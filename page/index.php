<?php require('header.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h1 class="display-4 bigTitle"><?= APP_TITLE; ?></h1>
            </div>
        </div>
        <div class="my-4"></div>
        <?php
        $themes = array('primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark');
        $c = 0;

        $dashboardDetails = includePluginsDashboard();

        if($User->getRole() >= 4) {
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
                    <div class="col-12 col-lg-4 mb-3">
                        <div class="card border-<?= $themes[$c]; ?>">
                            <div class="card-body">
                                <h2 class="card-title"><?= $dashboard['name']; ?></h2>
                                <span class="dashboardNum text-<?= $themes[$c]; ?>"><?= $dashboard['count']; ?></span>
                            </div>
                            <a href="<?= $dashboard['url']; ?>"
                               class="btn btn-<?= $themes[$c]; ?> d-block"><?= trans('Voir plus'); ?></a>
                        </div>
                    </div>
                    <?php $c == count($themes) ? $c = 0 : $c++; endforeach; ?>
            </div>
        <?php endif; ?>

        <hr class="hrStyle">
        <div id="visitorsStats"></div>
    </div>
<?php require('footer.php'); ?>