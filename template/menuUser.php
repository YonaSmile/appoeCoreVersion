<nav class="navbar navbar-expand-lg navbar-light bg-white" id="navbarUser">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarUserDetails"
            aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarUserDetails">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <button type="button" class="nav-link btn" id="sidebarCollapse">
                    <i class="fas fa-align-left"></i>
                </button>
            </li>
            <li class="nav-item">
                <a class="nav-link" target="_blank" href="<?= WEB_DIR_URL; ?>">
                    <span class="colorPrimary"><i class="fas fa-eye"></i></span> Visualiser le site
                </a>
            </li>
            <li class="nav-item" style="position: relative;">
                <div class="md-select">
                    <label for="ul-id">
                        <button type="button" class="ng-binding"><img src="<?= getAppImg('flag-' . LANG . '.svg'); ?>">
                        </button>
                    </label>
                    <ul role="listbox" id="ul-id" class="md-whiteframe-z1" aria-activedescendant="<?= LANG; ?>"
                        name="ul-id">
                        <?php foreach (getLangs() as $lang => $language): ?>
                            <li role="option" id="<?= $lang; ?>"
                                class="ng-binding ng-scope <?= $lang == LANG ? 'active' : ''; ?>" tabindex="-1"
                                aria-selected="true"><img src="<?= getAppImg('flag-' . $lang . '.svg'); ?>"></li>
                        <?php endforeach; ?>
                        <li id="closeLangs"><i class="fas fa-times"></i></li>
                    </ul>
                </div>
            </li>
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <?php if ($USER->getRole() > 3): ?>
                    <a class="nav-link" href="<?= getUrl('setting/'); ?>">
                        <i class="fas fa-cog"></i>
                    </a>
                <?php endif; ?>
            </li>
            <?php includePluginsSecondaryMenu(); ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownUserMenu" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2">
                        <?= $USER->getPrenom() . ucfirst(substr($USER->getNom(), 0, 1)); ?>
                    </span> <i class="fas fa-user"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownUserMenu">
                    <a class="dropdown-item" href="<?= getUrl('user/', $USER->getId()); ?>">
                        <small><i class="fas fa-user"></i> <?= trans('Mon profil'); ?></small>
                    </a>
                    <a class="dropdown-item" href="<?= WEB_APP_URL . 'logout.php'; ?>">
                        <small><i class="fas fa-power-off"></i> <?= trans('Déconnexion'); ?></small>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>