<?php if (!empty($_GET['id'])): ?>
    <?php require('header.php'); ?>
    <?php if ($_GET['id'] == $USER->getId() || $USER->getRole() >= 3): ?>
        <?php
        require_once(WEB_PROCESS_PATH . 'users.php');
        $UpdateUser = new App\Users();
        $UpdateUser->setId($_GET['id']);
        if ($UpdateUser->show() && $UpdateUser->getRole() <= $USER->getRole()) :?>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1 class="bigTitle"><?= trans('Mise à jour de l\'utilisateur'); ?></h1>
                        <hr class="my-4">
                    </div>
                </div>

                <?php if (isset($Response)): ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-<?= $Response->display()->status ?>" role="alert">
                                <?= $Response->display()->error_msg; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-12 col-lg-6 my-2">
                        <form action="" method="post" id="updateUserForm">
                            <?= getTokenField(); ?>
                            <input type="hidden" name="id" value="<?= $UpdateUser->getId() ?>">
                            <div class="row">
                                <div class="col-12 my-2">
                                    <?= App\Form::text('Email', 'email', 'email', $UpdateUser->getEmail(), true, 60, 'aria-describedby="emailHelp"', '<small id="emailHelp" class="form-text text-muted">' . trans('En changeant votre adresse email vous serez déconnecté du logiciel') . '</small>'); ?>
                                </div>
                                <div class="col-12 my-2">
                                    <?= App\Form::text('Nom', 'nom', 'text', $UpdateUser->getNom(), true, 40); ?>
                                </div>
                                <div class="col-12">
                                    <?= App\Form::text('Prénom', 'prenom', 'text', $UpdateUser->getPrenom(), true, 40); ?>
                                </div>
                                <?php if ($UpdateUser->getId() != $USER->getId()): ?>
                                    <div class="col-12">
                                        <?= App\Form::select('Rôle', 'role', array_map('trans', ROLES), $UpdateUser->getRole(), true, '', $USER->getRole(), '>'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="my-2"></div>
                            <div class="row">
                                <div class="col-12">
                                    <?= App\Form::target('UPDATEUSER'); ?>
                                    <?= App\Form::submit('Enregistrer', 'UPDATEUSERSUBMIT'); ?>
                                </div>
                            </div>
                        </form>
                    </div>
                    <hr class="hrStyle d-lg-none d-md-block my-4">
                    <div class="col-12 col-lg-6 my-2">
                        <form action="" method="post" id="updatePasswordUserForm" autocomplete="off">
                            <input type="hidden" name="_token" value="<?= getToken() ?>">
                            <input type="hidden" name="id" value="<?= $UpdateUser->getId() ?>">
                            <div class="row">
                                <div class="col-12 my-2">
                                    <?= App\Form::text('Nouveau Mot de passe', 'password', 'password', 'password', true, 150, 'autocomplete="off"'); ?>
                                </div>

                                <div class="col-12 my-2">
                                    <?= App\Form::text('Confirmation du Mot de passe', 'password2', 'password', '', true, 150, 'autocomplete="off"'); ?>
                                </div>
                            </div>
                            <div class="my-2"></div>
                            <div class="row">
                                <div class="col-12">
                                    <?= App\Form::target('UPDATEPASSWORD'); ?>
                                    <?= App\Form::submit('Enregistrer', 'UPDATEPASSWORDSUBMIT'); ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="my-4"></div>
            </div>
        <?php else: ?>
            <?= getContainerErrorMsg(trans('Cet utilisateur n\'existe pas')); ?>
        <?php endif; ?>
    <?php else: ?>
        <?= getContainerErrorMsg(trans('Cet utilisateur n\'existe pas')); ?>
    <?php endif; ?>
    <?php require('footer.php'); ?>
<?php else: ?>
    <?= trans('Cet utilisateur n\'existe pas'); ?>
<?php endif; ?>