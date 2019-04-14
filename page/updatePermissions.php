<?php
require('header.php');
$Menu = new \App\Menu();
$allPermissions = $Menu->displayMenuAll();
echo getTitle($Page->getName(), $Page->getSlug()); ?>

    <div class="row">
        <div class="col-12">
            <button id="addPermission" type="button" class="btn btn-info btn-sm mb-4" data-toggle="modal"
                    data-target="#modalAddPermission">
                <?= trans('Nouvelle Permission'); ?>
            </button>
        </div>
    </div>
<?php if ($allPermissions): ?>
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table id="permissionTable"
                       class="sortableTable table table-bordered">
                    <thead>
                    <tr>
                        <th><?= trans('ID'); ?></th>
                        <th><?= trans('Nom'); ?></th>
                        <th><?= trans('Slug'); ?></th>
                        <th><?= trans('Rôle requis'); ?></th>
                        <th><?= trans('Statut'); ?></th>
                        <th><?= trans('Ordre'); ?></th>
                        <th><?= trans('Parent'); ?></th>
                        <th><?= trans('Plugin'); ?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($allPermissions as $permission): ?>
                        <tr class="changeableTr" data-idmenu="<?= $permission->id; ?>">
                            <th><?= $permission->id ?></th>
                            <td class="changeableTd" data-dbname="name"><?= $permission->name ?></td>
                            <td class="changeableTd" data-dbname="slug"><?= $permission->slug ?></td>
                            <td class="changeableTd"
                                data-dbname="min_role_id"><?= getRoleName($permission->min_role_id) ?></td>
                            <td class="changeableTd" data-dbname="statut"><?= $permission->statut ?></td>
                            <td class="changeableTd"
                                data-dbname="order_menu"><?= $permission->order_menu ?></td>
                            <td class="changeableTd"
                                data-dbname="parent_id"><?= $permission->parent_id ?></td>
                            <td class="changeableTd"
                                data-dbname="pluginName"><?= $permission->pluginName ?></td>
                            <td>
                                <button type="button" class="btn btn-sm updatePermissionBtn"
                                        title="<?= trans('Modifier'); ?>"
                                        data-idmenu="<?= $permission->id; ?>">
                                    <span class="btnEdit"><i class="fas fa-wrench"></i></span>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>
    <div class="modal fade" id="modalAddPermission" tabindex="-1" role="dialog"
         aria-labelledby="modalAddPermissionTitle"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="" method="post" id="addPermissionForm">
                    <div class="modal-header">
                        <h5 class="modal-title"
                            id="modalAddPermissionTitle"><?= trans('Ajouter une permission'); ?></h5>
                    </div>
                    <div class="modal-body" id="modalPermissionBody">
                        <?= getTokenField(); ?>
                        <div class="row">
                            <div class="col-12 my-2">
                                <?= \App\Form::text('ID', 'id', 'number', !empty($_POST['id']) ? $_POST['id'] : '', true, 11); ?>
                            </div>
                            <div class="col-12 my-2">
                                <?= \App\Form::text('Slug', 'slug', 'text', !empty($_POST['slug']) ? $_POST['slug'] : '', true, 40); ?>
                            </div>
                            <div class="col-12 my-2">
                                <?= \App\Form::text('Nom', 'name', 'text', !empty($_POST['name']) ? $_POST['name'] : '', true, 50); ?>
                            </div>
                            <div class="col-12 my-2">
                                <?= \App\Form::select('Rôle requis', 'min_role_id', getRoles(), '', true); ?>
                            </div>
                            <div class="col-12 my-2">
                                <?= \App\Form::text('Statut', 'statut', 'number', !empty($_POST['statut']) ? $_POST['statut'] : '', true, 11); ?>
                            </div>
                            <div class="col-12 my-2">
                                <?= \App\Form::select('Permission Parente', 'parent_id', extractFromObjToSimpleArr($allPermissions, 'id', 'name') + array(10 => 'Aucun Parent'), '', true); ?>
                            </div>
                            <div class="col-12 my-2">
                                <?= \App\Form::text('Ordre', 'order_menu', 'number', !empty($_POST['order_menu']) ? $_POST['order_menu'] : '', false, 11); ?>
                            </div>
                            <div class="col-12 my-2">
                                <?= \App\Form::text('Nom du plugin', 'pluginName', 'text', !empty($_POST['pluginName']) ? $_POST['pluginName'] : '', false, 200); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 my-2" id="permissionFormInfos"></div>
                        </div>
                    </div>
                    <div class="modal-footer" id="modalPermissionFooter">
                        <?= \App\Form::target('ADDPERMISSION'); ?>
                        <button type="submit" id="addPermissionBtn"
                                class="btn btn-primary"><?= trans('Enregistrer'); ?></button>
                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal"><?= trans('Fermer'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="/app/lib/template/js/permissions.js"></script>
<?php require('footer.php'); ?>