<?php
require('header.php');

use App\AppConfig;

$AppConfig = new AppConfig();
$allConfig = array_merge($AppConfig->getDefaultConfig(), $AppConfig->get());

echo getTitle($Page->getName(), $Page->getSlug()); ?>
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

            var name = $(this).attr('name');
            var value = $(this).is(':checked');
            $.post('/app/ajax/config.php', {configName: name, configValue: value}).done(function (data) {
                if (data == 'true' || data === true) {
                    alert('Enregistré');
                } else {
                    alert('Problèmes');
                }
            });
        });
    });
</script>