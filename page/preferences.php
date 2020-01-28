<?php
require('header.php');

use App\AppConfig;

$AppConfig = new AppConfig();
$allConfig = $AppConfig->get();

echo getTitle(getAppPageName(), getAppPageSlug()); ?>
<button class="btn btn-sm btn-outline-info float-right" id="restoreConfig">Réinitialiser</button>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-lg-4">
            <div class="row">
                <div class="col-12 my-3"><h5>Options</h5></div>
                <?php foreach ($allConfig['options'] as $name => $val): ?>
                    <div class="col-12 mb-2">
                    <span class="switchBtnContenair mr-2">
                        <label class="switch">
                            <input type="checkbox" data-config-type="options"
                                   class="updatePreference" <?= $val === 'true' ? 'checked' : ''; ?>
                                   name="<?= $name; ?>">
                            <span class="slider"></span>
                        </label>
                    </span> <?= trans($AppConfig->configExplanation[$name]); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-12 col-lg-8">
            <div class="col-12 my-3"><h5>Données</h5></div>
            <?php foreach ($allConfig['data'] as $name => $val):
                if (!empty($val)): ?>
                    <div class="col-12 mb-2">
                        <strong><?= trans($AppConfig->configExplanation[$name]); ?></strong> : <mark><?= $val; ?></mark>
                    </div>
                <?php endif;
            endforeach; ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {

        $('.updatePreference').on('change', function () {

            busyApp(false);
            let name = $(this).attr('name');
            let value = $(this).is(':checked');
            let type = $(this).attr('data-config-type');
            $.post('/app/ajax/config.php', {
                configName: name,
                configType: type,
                configValue: value
            }).done(function (data) {
                if (data == 'true' || data === true) {
                    alert('Enregistré');
                } else {
                    alert('Problèmes');
                }
                availableApp();
            });
        });

        $('#restoreConfig').on('click', function () {

            if (confirm('Vous êtes sur le point de rétablir les préférences par défaut')) {

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