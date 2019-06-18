<?php
require('header.php');

use App\AppConfig;

$AppConfig = new AppConfig();
$allConfig = array_merge($AppConfig->getDefaultConfig(), $AppConfig->get());

echo getTitle($Page->getName(), $Page->getSlug()); ?>
<button class="btn btn-sm btn-outline-info float-right" id="restoreConfig">Réinitialiser</button>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-lg-4">
            <div class="row">
                <?php foreach ($allConfig as $name => $val): ?>
                    <div class="col-12 mb-2">
                    <span class="switchBtnContenair mr-2">
                        <label class="switch">
                            <input type="checkbox" class="updatePreference" <?= $val === 'true' ? 'checked' : ''; ?>
                                   name="<?= $name; ?>">
                            <span class="slider"></span>
                        </label>
                    </span> <?= trans($AppConfig->configExplanation[$name]); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-12 col-lg-4"></div>
        <div class="col-12 col-lg-4"></div>
    </div>
</div>
<script>
    $(document).ready(function () {

        $('.updatePreference').on('change', function () {

            busyApp(false);
            var name = $(this).attr('name');
            var value = $(this).is(':checked');
            $.post('/app/ajax/config.php', {configName: name, configValue: value}).done(function (data) {
                if (data == 'true' || data === true) {
                    alert('Enregistré');
                } else {
                    alert('Problèmes');
                }
                availableApp();
            });
        });

        $('#restoreConfig').on('click', function () {

            if(confirm('Vous êtes sur le point de rétablir les préférences par défaut')){

                busyApp(false);
                $.post('/app/ajax/config.php', {restoreConfig: 'OK'}).done(function (data) {
                    if (data == 'true' || data === true) {
                        window.location.href = window.location.href;
                    } else {
                        alert('Problèmes');
                    }
                    availableApp();
                });
            }
        });
    });
</script>