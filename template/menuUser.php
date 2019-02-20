<nav class="navbar navbar-expand-lg navbar-light bg-white" id="navbarUser">
    <button class="mr-auto d-sm-inline-block d-md-none sidebarCollapse" type="button">
        <i class="fas fa-align-left"></i>
    </button>
    <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbarUserDetails"
            aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarUserDetails">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item d-none d-md-inline-block">
                <button type="button" class="nav-link btn sidebarCollapse">
                    <i class="fas fa-align-left"></i>
                </button>
            </li>
            <?php if (class_exists('App\Plugin\Cms\Cms')): ?>
                <li class="nav-item">
                    <a class="nav-link" target="_blank" href="<?= WEB_DIR_URL; ?>">
                        <span class="colorPrimary"><i class="fas fa-eye"></i></span> Visualiser le site
                    </a>
                </li>
                <?php if (class_exists('App\Plugin\Traduction\Traduction')): ?>
                    <li class="nav-item" id="languageSelectorContainer">
                        <div class="dropdown">
                            <button class="btn btn-white" type="button" id="languageSelectorBtn"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="<?= getAppImg('flag-' . LANG . '.svg'); ?>">
                                <?= LANGUAGES[LANG]; ?>
                            </button>
                            <div class="dropdown-menu" id="languageSelectorContent"
                                 aria-labelledby="languageSelectorBtn">
                                <?php foreach (getLangs() as $lang => $language): if ($lang != LANG): ?>
                                    <button class="dropdown-item langSelector" id="<?= $lang; ?>" type="button">
                                        <img src="<?= getAppImg('flag-' . $lang . '.svg'); ?>">
                                        <?= LANGUAGES[$lang]; ?>
                                    </button>
                                <?php endif; endforeach; ?>
                            </div>
                        </div>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <?php if (isTechnicien(getUserRoleId())): ?>
                    <a class="nav-link" href="<?= getUrl('setting/'); ?>">
                        <i class="fas fa-cog"></i>
                    </a>
                <?php endif; ?>
            </li>
            <?php includePluginsSecondaryMenu(); ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle sidebarLink" href="#" id="navbarDropdownUserMenu" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2">
                        <?= getUserFirstName() . ucfirst(substr(getUserName(), 0, 1)); ?>
                    </span> <i class="fas fa-user"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownUserMenu">
                    <?php if (isUserAuthorized('updateUser')): ?>
                        <a class="dropdown-item" href="<?= getUrl('user/', getUserIdSession()); ?>">
                            <small>
                                <span class="mr-2"><i class="fas fa-user"></i></span> <?= trans('Mon profil'); ?>
                            </small>
                        </a>
                    <?php endif; ?>
                    <a class="dropdown-item" href="<?= WEB_APP_URL . 'logout.php'; ?>">
                        <small><span class="mr-2"><i class="fas fa-power-off"></i></span> <?= trans('Déconnexion'); ?>
                        </small>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>