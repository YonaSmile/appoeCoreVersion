<?php require('header.php'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1 class="display-4 bigTitle"><?= trans('Utilisateurs'); ?></h1>
            </div>
        </div>
        <div class="my-4"></div>
        <?php $listUsers = $USER->showAll(); ?>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table id="clientTable"
                           class="sortableTable table table-striped">
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
                        <?php if ($listUsers): ?>
                            <?php foreach ($listUsers as $user): ?>
                                <?php if ($user->role <= $USER->getRole()): ?>
                                    <tr>
                                        <td><?= $user->nom ?></td>
                                        <td><?= $user->prenom ?></td>
                                        <td><?= $user->email ?></td>
                                        <td><?= ROLES[$user->role] ?></td>
                                        <td>
                                            <?php if ($USER->getId() == $user->id || ($USER->getRole() >= 3 && $USER->getRole() >= $user->role)): ?>
                                                <a href="<?= getUrl('user/', $user->id) ?>"
                                                   class="btn btn-warning btn-sm" title="<?= trans('Modifier'); ?>">
                                                    <span class="fas fa-cog"></span>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($user->id != $USER->getId() && $USER->getRole() >= 3 && $USER->getRole() > $user->role): ?>
                                                <button type="button" class="btn btn-danger btn-sm deleteUser"
                                                        title="<?= trans('Archiver'); ?>"
                                                        data-iduser="<?= $user->id ?>">
                                                    <span class="fas fa-archive" aria-hidden="true"></span>
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
        <div class="my-4"></div>
    </div>
    <script>
        $(document).ready(function () {

            $('.deleteUser').click(function () {

                if (confirm('<?= trans('Vous allez archiver cet utilisateur'); ?> !')) {
                    var $btn = $(this);
                    var idUser = $btn.data('iduser');

                    $.post(
                        '<?= WEB_DIR; ?>app/ajax/users.php',
                        {
                            idDeleteUser: idUser
                        },
                        function (data) {
                            if (true === data || data == 'true') {
                                $btn.parent('td').parent('tr').slideUp();
                            }
                        }
                    );
                }
            });
        });
    </script>
<?php require('footer.php'); ?>