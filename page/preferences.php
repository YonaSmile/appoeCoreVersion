<?php
require('header.php');

use App\Option;

$Option = new Option();
$Option->setType('PREFERENCE');
$preferences = $Option->showByType();
$Option->setType('DATA');
$datas = $Option->showByType();
$Option->setType('IPACCESS');
$ipAccess = $Option->showByType();

echo getTitle(getAppPageName(), getAppPageSlug()); ?>
    <button class="btn btn-sm btn-outline-warning" id="clearFilesCache">Vider le cache des fichiers</button>
    <button class="btn btn-sm btn-outline-danger" id="clearServerCache">Purger le cache du serveur</button>
    <div class="container-fluid">
        <div class="row">
            <?php if ($preferences): ?>
                <div class="col-12 col-lg-4 my-5">
                    <div class="row">
                        <div class="col-12 mb-3"><h5>Options</h5></div>
                        <?php foreach ($preferences as $preference): ?>
                            <div class="col-12 mb-2">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" data-config-type="PREFERENCE"
                                           class="custom-control-input updatePreference"
                                           name="<?= $preference->key; ?>" id="<?= $preference->key; ?>"
                                        <?= $preference->val === 'true' ? 'checked' : ''; ?>>
                                    <label class="custom-control-label"
                                           for="<?= $preference->key; ?>"><?= $preference->description; ?></label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif;
            if ($datas): ?>
                <div class="col-12 col-lg-8 my-5">
                    <div class="row">
                        <div class="col-12 mb-3"><h5>Données</h5></div>
                        <?php foreach ($datas as $data):
                            if (!empty($data->val)): ?>
                                <div class="col-12 mb-2">
                                    <strong><?= $data->description; ?></strong> :
                                    <mark data-src="<?= $data->val; ?>" class="copyContentOnClick"
                                          style="cursor: pointer"><?= $data->val; ?></mark>
                                </div>
                            <?php endif;
                        endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col-12 col-lg-3 my-5">
                <div class="row">
                    <div class="col-12 mb-3"><h5 class="m-0">Autorisations d'accès</h5>
                        <small><strong class="text-secondary">Mon IP :</strong>
                            <span id="addMyIp" style="cursor:pointer;"><?= getIP(); ?></span></small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <strong class="text-secondary"><?= trans('Préconfiguré dans'); ?> ini.main</strong>
                    </div>
                    <?php if (defined('IP_ALLOWED') && !isArrayEmpty(IP_ALLOWED)):
                        foreach (IP_ALLOWED as $ip): ?>
                            <div class="col-12 text-info">
                                <small class="text-secondary">
                                    <em><?= (false !== strpos($ip, ':')) ? 'IPV6' : 'IPV4'; ?></em>
                                </small> <?= $ip; ?></div>
                        <?php endforeach;
                    endif; ?>
                </div>
                <div class="row">
                    <div class="col-12 mt-3">
                        <strong class="text-secondary"><?= trans('Ajouté manuellement'); ?></strong>
                    </div>
                    <div id="allPersimissions" class="col-12 mb-2">
                        <div class="row">
                            <?php
                            if ($ipAccess):
                                foreach ($ipAccess as $ip): ?>
                                    <div class="col-12 ipAccess" data-ipaccess-id="<?= $ip->id; ?>"
                                         data-ip="<?= $ip->key; ?>">
                                        <?= $ip->key; ?></div>
                                <?php endforeach;
                            endif; ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 position-relative">
                        <input type="text" name="addPermissionAccess" placeholder="Nouvelle autorisation"
                               maxlength="45">
                        <span id="submitAddPermissionAccess"><i class="fas fa-plus"></i></span>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script type="text/javascript" src="/app/lib/template/js/preferences.js"></script>
<?php require('footer.php'); ?>