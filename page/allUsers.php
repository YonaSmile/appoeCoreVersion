<?php require('header.php');
echo getTitle($Page->getName(), $Page->getSlug()); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table id="clientTable"
                           class="sortableTable table table-bordered">
                        <thead>
                        <tr>
                            <th><?= trans('Login'); ?></th>
                            <th><?= trans('Nom'); ?></th>
                            <th><?= trans('Prénom'); ?></th>
                            <th><?= trans('Email'); ?></th>
                            <th><?= trans('Rôle'); ?></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (defined('ALLUSERS')):
                            foreach (getAllUsers() as $userId => $user):
                                if (getRoleId($user->role) <= getUserRoleId()): ?>
                                    <tr class="<?= $user->statut == 0 ? 'table-secondary' : ''; ?>">
                                        <td><?= $user->login ?></td>
                                        <td><?= $user->nom ?></td>
                                        <td><?= $user->prenom ?></td>
                                        <td><?= $user->email ?></td>
                                        <td><?= getRoleName($user->role) ?></td>
                                        <td>
                                            <?php if (getUserIdSession() == $user->id || getUserRoleId() >= getRoleId($user->role)): ?>
                                                <a href="<?= getUrl('user/', $user->id) ?>"
                                                   class="btn btn-sm" title="<?= trans('Modifier'); ?>">
                                                    <span class="btnEdit"><i class="fas fa-wrench"></i></span>
                                                </a>
                                            <?php endif;
                                            if ($user->id != getUserIdSession() && getUserRoleId() > getRoleId($user->role) && $user->statut > 0): ?>
                                                <button type="button" class="btn btn-sm deleteUser"
                                                        title="<?= trans('Bannir'); ?>"
                                                        data-iduser="<?= $user->id ?>">
                                                    <span class="btnArchive"><i class="fas fa-ban"></i></span>
                                                </button>
                                            <?php endif;
                                            if ($user->id != getUserIdSession() && getUserRoleId() > getRoleId($user->role) && $user->statut == 0): ?>
                                                <button type="button" class="btn btn-sm valideUser"
                                                        title="<?= trans('Valider'); ?>"
                                                        data-iduser="<?= $user->id ?>">
                                                    <span class="btnEdit"><i class="fas fa-user-check"></i></span>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif;
                            endforeach;
                        endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="/app/js/user.js"></script>
<?php require('footer.php'); ?>