<?php
require('header.php');

use App\AppConfig;

$AppConfig = new AppConfig();
$allConfig = $AppConfig->get();

echo getTitle(getAppPageName(), getAppPageSlug()); ?>
<button class="btn btn-sm btn-outline-info" id="restoreConfig">Réinitialiser</button>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-lg-3 my-5">
            <div class="row">
                <div class="col-12 mb-3"><h5>Options</h5></div>
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
        <div class="col-12 col-lg-6 my-5">
            <div class="col-12 mb-3"><h5>Données</h5></div>
            <?php foreach ($allConfig['data'] as $name => $val):
                if (!empty($val)): ?>
                    <div class="col-12 mb-2">
                        <strong><?= trans($AppConfig->configExplanation[$name]); ?></strong> :
                        <mark><?= $val; ?></mark>
                    </div>
                <?php endif;
            endforeach; ?>
        </div>
        <div class="col-12 col-lg-3 my-5">
            <div class="col-12 mb-3"><h5>Autorisations d'accès</h5></div>

            <?php if (defined('IP_ALLOWED') && !isArrayEmpty(IP_ALLOWED)): foreach (IP_ALLOWED as $ip): ?>
                <div class="col-12 mb-2 text-info">
                    <small class="text-secondary">
                        <em><?= (false !== strpos($ip, ':')) ? 'IPV6' : 'IPV4'; ?></em>
                    </small> <?= $ip; ?></div>
            <?php endforeach; ?>
                <hr class="mx-5">
            <?php endif; ?>

            <div id="allPersimissions">
                <?php foreach ($allConfig['accessPermissions'] as $val): ?>
                    <div class="col-12 mb-2 ipAccess" data-ip="<?= $val; ?>"><?= $val; ?></div>
                <?php endforeach; ?>
            </div>
            <div class="position-relative">
                <input type="text" name="addPermissionAccess" placeholder="Nouvelle autorisation" maxlength="45">
                <span id="submitAddPermissionAccess"><i class="fas fa-plus"></i></span>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/app/lib/template/js/preferences.js"></script>