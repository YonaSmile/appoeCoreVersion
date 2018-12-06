<?php
$Menu = new \App\Menu();
$menu_data = array_sort($Menu->displayMenu(getUserRoleId()), 'order_menu');
$menuAll = array();

if (false !== $menu_data) {
    foreach ($menu_data as $menuPage) {
        $menuAll[$menuPage['parent_id']][] = $menuPage;
    }
}
?>
<div class="sidebar-header">
    <?= getLogo(); ?>
</div>

<ul class="list-unstyled components">
    <?php if (!empty($menuAll[10])) : ?>
        <?php foreach ($menuAll[10] as $menu): ?>
            <?php if (!empty($menuAll[$menu['id']])): sort($menuAll[$menu['id']]); ?>
                <li class="<?= 'icon-' . $menu['slug']; ?> "
                    id="menu-<?= $menu['slug']; ?>">
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
                <li class="<?= activePage($menu['slug']) . ' icon-' . $menu['slug']; ?>"
                    id="menu-<?= $menu['slug']; ?>">
                    <a href="<?= (!empty($menu['pluginName'])) ? getPluginUrl($menu['pluginName'] . '/page/' . $menu['slug'] . '/') : getUrl(($menu['slug'] == 'index') ? 'home' : $menu['slug'] . '/'); ?>"><?= trans($menu['name']); ?></a>
                </li>
            <?php endif ?>
        <?php endforeach ?>
    <?php endif ?>
    <?php includePluginsPrimaryMenu(); ?>
    <li id="liUserStatutMenu">
        <ul class="list-inline" id="usersStatsSubMenu"></ul>
    </li>
    <div class="progress mt-2" style="height: 1px;">
        <div id="appStatus" class="progress-bar progress-bar-striped bg-light" role="progressbar"
             aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
    </div>
</ul>

<div id="sidebarInfos" class="mb-2 text-center">
    <small>Art Of Event - <strong>APPOE</strong></small>
    <?php
    \App\Version::setFile(WEB_APP_PATH . 'version.json');
    if (\App\Version::show()): ?>
        <small><em><?= \App\Version::getVersion(); ?></em></small>
    <?php endif; ?>
</div>