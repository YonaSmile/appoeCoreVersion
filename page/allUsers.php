<?php require('header.php');
echo getTitle(getAppPageName(), getAppPageSlug());
$defaultEmail = getOptionData('defaultEmail'); ?>
    <div id="admin-tab-search">
        <input type="search" class="form-control" id="admin-tab-search-input" placeholder="Rechercher...">
    </div>
    <div id="admin-tabs-menu" class="row d-none d-sm-flex">
        <div class="d-none d-sm-block col-sm-4 col-md-3 col-lg-3 col-xl-3">Login</div>
        <div class="d-none d-sm-block col-sm-4 col-md-3 col-lg-2 col-xl-2">Nom</div>
        <div class="d-none d-lg-block col-lg-2 col-xl-2">Prénom</div>
        <div class="d-none d-md-block col-md-3 col-lg-3 col-xl-3">Email</div>
        <div class="d-none d-sm-block col-sm-4 col-md-3 col-lg-2 col-xl-2">Rôle</div>
    </div>
<?php if (defined('ALLUSERS')): ?>
    <div class="row">
        <div id="admin-tabs" class="col-12">
            <?php foreach (getAllUsers() as $userId => $user):
                if (getRoleId($user->role) <= getUserRoleId()): ?>
                    <div class="admin-tab" data-user="<?= $user->id ?>"
                         data-filter="<?= $user->login ?> <?= $user->nom ?> <?= $user->prenom ?> <?= $user->email ?> <?= getRoleName($user->role) ?>">
                        <div class="admin-tab-header <?= $user->statut == 0 ? 'bg-secondary' : ''; ?>">
                            <div class="col-12 col-sm-4 col-md-3 col-lg-3 col-xl-3"><?= $user->login ?></div>
                            <div class="d-none d-sm-block col-sm-4 col-md-3 col-lg-2 col-xl-2"><?= $user->nom ?></div>
                            <div class="d-none d-lg-block col-lg-2 col-xl-2"><?= $user->prenom ?></div>
                            <div class="d-none d-md-block col-md-3 col-lg-3 col-xl-3"><?= $user->email ?></div>
                            <div class="d-none d-sm-block col-sm-4 col-md-3 col-lg-2 col-xl-2"><?= getRoleName($user->role) ?></div>
                        </div>
                        <div class="admin-tab-content">
                            <div class="admin-tab-content-header">
                                <div>
                                    <h2><?= $user->login ?></h2>
                                    <?php if ($user->statut > 0 && isEmail($user->email)): ?>
                                        <button type="button" class="btnLink defaultEmailUser"
                                                title="<?= trans('Définir comme adresse Email par défaut'); ?>"
                                            <?= $defaultEmail == $user->email ? 'disabled="true"' : ''; ?>
                                                data-iduser="<?= $user->id ?>" data-email="<?= $user->email; ?>">
                                                <span class="<?= $defaultEmail == $user->email ? 'text-success' : ''; ?>">
                                                    <i class="fas fa-envelope"></i></span>
                                        </button>
                                        |
                                    <?php endif;
                                    if (getUserIdSession() == $user->id || getUserRoleId() >= getRoleId($user->role)): ?>
                                        <a href="<?= getUrl('user/', $user->id) ?>"
                                           class="btnLink"><?= trans('Modifier'); ?></a>
                                    <?php endif;
                                    if ($user->id != getUserIdSession() && getUserRoleId() > getRoleId($user->role) && $user->statut > 0): ?>
                                        |
                                        <button type="button" class="btnLink bannishUser"
                                                data-iduser="<?= $user->id ?>">
                                            <?= trans('Bannir'); ?></button>

                                    <?php endif;
                                    if ($user->id != getUserIdSession() && getUserRoleId() > getRoleId($user->role) && $user->statut == 0 && isTechnicien(getUserRoleId())): ?>
                                        |
                                        <button type="button" class="btnLink valideUser"
                                                data-iduser="<?= $user->id ?>"><?= trans('Valider'); ?></button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="px-2">
                                <div class="my-3">
                                    <strong><?= trans('ID'); ?></strong><?= $user->id ?>
                                </div>
                                <div class="my-3">
                                    <strong><?= trans('Login'); ?></strong><?= $user->login ?>
                                </div>
                                <div class="my-3">
                                    <strong><?= trans('Nom'); ?></strong><?= $user->nom ?>
                                </div>
                                <div class="my-3">
                                    <strong><?= trans('Prénom'); ?></strong><?= $user->prenom ?>
                                </div>
                                <div class="my-3">
                                    <strong><?= trans('Email'); ?></strong><?= $user->email ?>
                                </div>
                                <div class="my-3">
                                    <strong><?= trans('Rôle'); ?></strong><?= getRoleName($user->role) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif;
            endforeach; ?>
        </div>
    </div>
<?php endif; ?>
    <script type="text/javascript" src="/app/lib/template/js/user.js"></script>
<?php require('footer.php'); ?>