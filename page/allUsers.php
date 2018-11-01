<?php require('header.php');
global $ALLUSERS; ?>
<?= getTitle($Page->getName(), $Page->getSlug()); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table id="clientTable"
                           class="sortableTable table table-bordered">
                        <thead>
                        <tr>
                            <th><?= trans('Nom'); ?></th>
                            <th><?= trans('Prénom'); ?></th>
                            <th><?= trans('Email'); ?></th>
                            <th><?= trans('Rôle'); ?></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($ALLUSERS): ?>
                            <?php foreach ($ALLUSERS as $userId => $user): ?>
                                <?php if (getRoleId($user->role) <= getUserRoleId()): ?>
                                    <tr class="<?= $user->statut == 0 ? 'table-warning' : ''; ?>">
                                        <td><?= $user->nom ?></td>
                                        <td><?= $user->prenom ?></td>
                                        <td><?= $user->email ?></td>
                                        <td><?= getRoleName($user->role) ?></td>
                                        <td>
                                            <?php if (getUserIdSession() == $user->id || (getUserRoleId() >= 3 && getUserRoleId() >= getRoleId($user->role))): ?>
                                                <a href="<?= getUrl('user/', $user->id) ?>"
                                                   class="btn btn-sm" title="<?= trans('Modifier'); ?>">
                                                    <span class="btnEdit"><i class="fas fa-wrench"></i></span>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($user->id != getUserIdSession() && getUserRoleId() >= 3 && getUserRoleId() > getRoleId($user->role) && $user->statut > 0): ?>
                                                <button type="button" class="btn btn-sm deleteUser"
                                                        title="<?= trans('Bannir'); ?>"
                                                        data-iduser="<?= $user->id ?>">
                                                    <span class="btnArchive"><i class="fas fa-ban"></i></span>
                                                </button>
                                            <?php endif; ?>
                                            <?php if ($user->id != getUserIdSession() && getUserRoleId() >= 3 && getUserRoleId() > getRoleId($user->role) && $user->statut == 0): ?>
                                                <button type="button" class="btn btn-sm valideUser"
                                                        title="<?= trans('Archiver'); ?>"
                                                        data-iduser="<?= $user->id ?>">
                                                    <span class="btnCheck"><i class="fas fa-user-check"></i></span>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="/app/js/user.js"></script>
<?php require('footer.php'); ?>