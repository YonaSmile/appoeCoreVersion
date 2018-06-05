<?php require('header.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1 class="display-4 bigTitle"><?= trans('Réglages'); ?></h1>
            </div>
        </div>
        <div class="my-4"></div>
        <div class="row" id="pluginsContainer">
            <div class="col-12 col-lg-6">
                <?php
                $plugins = getPlugins();
                if (is_array($plugins) && !empty($plugins)) : ?>
                    <h2 class="subTitle text-uppercase"><?= trans('Plugins'); ?></h2>
                    <div class="row">
                        <?php foreach ($plugins as $plugin) : ?>
                            <div class="col-md-12 col-lg-6">
                                <div class="plugin" data-name="<?= $plugin['name']; ?>">
                                    <div class="p-3 bg-info text-white">
                                        <?= strtoupper(implode(' ', splitAtUpperCase($plugin['name']))); ?>
                                        <?php if (!empty($plugin['setupPath'])): ?>
                                            <button type="button" class="btn btn-light btn-sm activePlugin float-right"
                                                    data-pluginpath="<?= $plugin['setupPath']; ?>"><?= trans('Activer'); ?>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                    <div class="pt-2 px-2 pb-1 mb-1 bg-light returnContainer"></div>
                                    <?php
                                    if (!empty($plugin['versionPath'])):
                                        App\Version::setFile($plugin['versionPath']);
                                        if (App\Version::show()):
                                            ?>
                                            <div class="py-1 px-3 mb-2 bg-light">
                                                <small class="pluginVersion"
                                                       data-pluginname="<?= $plugin['name']; ?>">
                                                    <?= App\Version::getVersion(); ?>
                                                </small>
                                                <small class="responseVersion float-right"></small>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <div class="my-4"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-12 col-lg-6">
                <h2 class="subTitle text-uppercase">
                    <?= trans('Système'); ?>
                </h2>
                <div class="row">
                    <div class="col-12" id="updateSystemContainer">
                        <div class="p-3 bg-info text-white">
                            <?= mb_strtoupper(trans('Application')); ?>
                        </div>
                        <div class="p-2 mb-2 bg-light" id="updateSystemBtnContainer" style="display: none;">
                            <button type="button" id="updateSystem" class="btn btn-danger btn-sm operationBtn">
                                <?= trans('Mettre à jour'); ?>
                            </button>
                        </div>
                        <?php
                        App\Version::setFile(WEB_APP_PATH . 'version.json');
                        if (App\Version::show()):
                            ?>
                            <div class="py-1 px-3 mb-2 bg-light">
                                <small id="systemVersion" data-systemversion="<?= App\Version::getVersion(); ?>">
                                    <?= trans('Version'); ?> <?= App\Version::getVersion(); ?>
                                </small>
                                <small class="responseVersion float-right"></small>
                            </div>
                        <?php endif; ?>
                        <div class="my-4"></div>
                    </div>
                    <div class="col-12" id="pluginSystemContenair" style="display: none">
                        <div class="p-3 bg-info text-white">
                            <?= mb_strtoupper(trans('Plugins')); ?>
                        </div>
                        <div class="p-2 mb-2 bg-light">
                            <button type="button" id="updatePlugins" class="btn btn-danger btn-sm operationBtn">
                                <?= trans('Mettre à jour'); ?>
                            </button>
                        </div>
                        <div class="my-4"></div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 bg-info text-white">
                            <?= mb_strtoupper(trans('Base de données')); ?>
                        </div>
                        <div class="p-2 mb-2 bg-light">
                            <button type="button" id="cleanDataBase" class="btn btn-warning btn-sm operationBtn">
                                <?= trans('Enregistrement & Nettoyage'); ?>
                            </button>
                        </div>
                        <div class="my-4"></div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 bg-info text-white">
                            <?= mb_strtoupper(trans('Visuel')); ?>
                        </div>
                        <div class="p-2 mb-2 bg-light">
                            <button type="button" id="updateSitemap" class="btn btn-warning btn-sm operationBtn">
                                <?= trans('Actualiser le Sitemap'); ?>
                            </button>
                        </div>
                        <div class="my-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>

        function disableBtns() {
            $('.operationBtn').attr('disabled', 'disabled').addClass('disabled');
        }

        function enableBtns() {
            $('.operationBtn').attr('disabled', false).removeClass('disabled');
        }

        $(document).ready(function () {

            $.each($('.plugin'), function (index, val) {

                var pluginName = $(this).data('name');
                var $returnContenaire = $(this).find('div.returnContainer');

                if ($(this).find('button.activePlugin').length) {

                    var $btn = $(this).find('button.activePlugin');

                    $btn.attr('disabled', 'disabled').addClass('disabled').html('<i class="fas fa-circle-notch fa-spin"></i>');
                    $.post(
                        '<?= WEB_DIR; ?>app/ajax/plugin.php',
                        {
                            checkTable: pluginName
                        },
                        function (response) {
                            response = parseInt(response);
                            if (response > 0) {
                                $btn.remove();
                                $returnContenaire.html('<p><strong><?= trans('Plugin Activé'); ?></strong></p><p><?= trans('Tables activés'); ?> : ' + response + '</p>');
                            } else {
                                $btn.attr('disabled', false).removeClass('disabled').html('Activer');
                            }
                        });
                } else {
                    $returnContenaire.html('<p><strong><?= trans('Plugin Activé'); ?></strong></p>');
                }
            });

            setTimeout(function () {
                busyApp();
                $.each($('.pluginVersion'), function (index, val) {
                    var $versionContenair = $(this);
                    var pluginName = $versionContenair.data('pluginname');
                    var responseVersion = $versionContenair.next('small.responseVersion');
                    responseVersion.html('<i class="fas fa-circle-notch fa-spin"></i>');
                    $.post(
                        '<?= WEB_DIR; ?>app/ajax/plugin.php',
                        {
                            checkVersion: pluginName
                        },
                        function (response) {
                            if (response) {
                                response = $.parseJSON(response);
                                if (response.version != $.trim($versionContenair.text())) {
                                    $('#pluginSystemContenair').slideDown('fast');
                                    responseVersion.html('<em class="text-danger">' + response.version + '</em>');
                                } else {
                                    responseVersion.html('<em class="text-info">' + response.version + '</em>');
                                }
                            }
                        }
                    );
                });
                availableApp();
            }, 2000);

            setTimeout(function () {
                var $versionContenair = $('#systemVersion');
                var systemVersion = $.trim($versionContenair.data('systemversion'));
                var responseVersion = $versionContenair.next('small.responseVersion');
                responseVersion.html('<i class="fas fa-circle-notch fa-spin"></i>');
                $.post(
                    '<?= WEB_DIR; ?>app/ajax/plugin.php',
                    {
                        checkSystemVersion: 'ok'
                    },
                    function (response) {
                        if (response) {
                            response = $.parseJSON(response);
                            if (response.version != systemVersion) {
                                $('#updateSystemBtnContainer').slideDown('fast');
                                responseVersion.html('<em class="text-danger">' + response.version + '</em>');
                            } else {
                                responseVersion.html('<em class="text-info">' + response.version + '</em>');
                            }
                        }
                    }
                );
            }, 2000);

            $('#updatePlugins').on('click', function () {
                $('#loader').fadeIn('fast');
                $.post(
                    '<?= WEB_DIR; ?>app/ajax/plugin.php',
                    {
                        downloadPlugins: 'OK'
                    },
                    function (data) {
                        if (data) {
                            window.location = window.location.href;
                            window.location.reload(true);
                        } else {
                            $('#loader').fadeOut();
                        }
                    }
                );
            });

            $('#updateSystem').on('click', function () {
                $('#loader').fadeIn('fast');
                $('#loaderInfos').html('Veuillez <strong>ne pas quitter</strong> votre navigateur');
                $.post(
                    '<?= WEB_DIR; ?>app/ajax/plugin.php',
                    {
                        downloadSystemCore: 'OK'
                    },
                    function (data) {
                        if (data) {
                            window.location = window.location.href;
                            window.location.reload(true);
                        } else {
                            $('#loader').fadeOut();
                        }
                    }
                );
            });

            $('#updateSitemap').on('click', function () {
                busyApp();
                $.post(
                    '<?= WEB_DIR; ?>app/ajax/plugin.php',
                    {
                        updateSitemap: 'OK'
                    },
                    function (data) {
                        if (data === true || data == 'true') {
                            $('#updateSitemap').removeClass('operationBtn').html('<?= trans('Sitemap actualisé') ?>');
                            enableBtns();
                        }
                        availableApp();
                    }
                );
            });

            $('.activePlugin').on('click', function () {
                busyApp();
                var $btn = $(this);
                var pluginPath = $btn.data('pluginpath');
                $btn.attr('disabled', 'disabled').addClass('disabled').html('<i class="fas fa-circle-notch fa-spin"></i>');
                var $returnContenaire = $btn.parent('div').next('div.returnContainer');
                $returnContenaire.load('<?= WEB_DIR; ?>app/ajax/plugin.php', {setupPath: pluginPath}, function () {
                    $returnContenaire.append('<p><strong><?= trans('Vous devez recharger la page pour voir les nouvelles fonctionnalités'); ?>.</strong></p>');
                    $btn.html('<?= trans('Activé'); ?>');
                });
                availableApp();
            });

            $('#cleanDataBase').on('click', function () {
                busyApp();
                var $btn = $(this);
                var $parent = $btn.parent();
                $.post(
                    '<?= WEB_DIR; ?>app/ajax/plugin.php',
                    {
                        optimizeDb: true
                    },
                    function (data) {
                        if (data) {
                            $btn.remove();
                            $parent.html('<p>' + data + '</p>');
                        }
                        enableBtns();
                        availableApp();
                    });
            });

            $('.operationBtn').on('click', function (e) {
                e.preventDefault();
                $(this).html('<i class="fas fa-circle-notch fa-spin"></i>');
                disableBtns();
            });

        });
    </script>
<?php require('footer.php'); ?>