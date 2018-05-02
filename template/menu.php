<?php
$Menu = new App\Menu();
$menu_data = $Menu->displayMenu($User->getRole());
$menuAll = array();

if (false !== $menu_data) {
    foreach ($menu_data as $menuPage) {
        $menuAll[$menuPage['parent_id']][] = $menuPage;
    }
    sort($menuAll[10]);
}
?>
<div class="sidebar-header">
    <img class="img-responsive logoNavbar"
         src="<?= file_exists(WEB_PUBLIC_PATH . 'images/logo_app.png') ? WEB_PUBLIC_URL : WEB_APP_URL; ?>images/logo_app.png">
</div>

<ul class="list-unstyled components">
    <?php if (!empty($menuAll[10])) : ?>
        <?php foreach ($menuAll[10] as $menu): ?>
            <?php if (!empty($menuAll[$menu['id']])): sort($menuAll[$menu['id']]); ?>
                <li class="<?= activePage($menu['slug']); ?>" id="menu-<?= $menu['slug']; ?>">
                    <a href="#<?= 'menu-admin' . $menu['id']; ?>" data-toggle="collapse"
                       aria-expanded="false" class="sidebarLink"><?= trans($menu['name']); ?></a>
                    <ul class="collapse list-unstyled" id="<?= 'menu-admin' . $menu['id']; ?>">
                        <?php foreach ($menuAll[$menu['id']] as $sous_menu): ?>
                            <li class="<?= activePage($sous_menu['slug']); ?>"
                                id="sousmenu-<?= $sous_menu['slug']; ?>">
                                <a href="<?= (!empty($menu['pluginName'])) ? getPluginUrl($menu['pluginName'] . '/page/' . $sous_menu['slug']) : getUrl($sous_menu['slug']); ?>/">
                                    <?= trans($sous_menu['name']); ?>
                                </a>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </li>
            <?php else: ?>
                <li class="<?= activePage($menu['slug']); ?>" id="menu-<?= $menu['slug']; ?>">
                    <a href="<?= (!empty($menu['pluginName'])) ? getPluginUrl($menu['pluginName'] . '/page/' . $menu['slug'] . '/') : getUrl(($menu['slug'] == 'index') ? 'home' : $menu['slug'] . '/'); ?>">
                        <?= trans($menu['name']); ?>
                    </a>
                </li>
            <?php endif ?>
        <?php endforeach ?>
    <?php endif ?>
    <?php includePluginsPrimaryMenu(); ?>
    <div class="progress mt-2" style="height: 1px;">
        <div id="appStatus" class="progress-bar progress-bar-striped bg-light" role="progressbar"
             aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
    </div>
</ul>

<div id="sidebarInfos" class="mb-2 text-center">
    <ul class="list-inline text-center mb-0">
        <div class="md-select">
            <label for="ul-id">
                <button type="button" class="ng-binding"><?= LANG; ?></button>
            </label>
            <ul role="listbox" id="ul-id" class="md-whiteframe-z1" aria-activedescendant="<?= LANG; ?>"
                name="ul-id">
                <?php foreach (getLangs() as $lang): ?>
                    <li role="option" id="<?= $lang; ?>"
                        class="ng-binding ng-scope <?= $lang == LANG ? 'active' : ''; ?>" tabindex="-1"
                        aria-selected="true"><?= $lang; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <li class="list-inline-item">
            <a class="nav-link" target="_blank" href="<?= WEB_DIR_URL; ?>">
                <i class="fas fa-external-link-alt"></i>
            </a>
        </li>
        <?php if ($User->getRole() > 3): ?>
            <li class="list-inline-item">
                <a class="nav-link" href="<?= getUrl('setting/'); ?>">
                    <i class="fas fa-cog"></i>
                </a>
            </li>
        <?php endif; ?>
        <li class="list-inline-item">
            <a class="nav-link" href="<?= WEB_APP_URL . 'logout.php'; ?>">
                <i class="fas fa-power-off"></i>
            </a>
        </li>
    </ul>
    <small>Art Of Event - <strong>APPOE</strong></small>
</div>