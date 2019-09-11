<?php require('header.php');
if (!empty($_GET['id'])):
    require_once(WEB_PROCESS_PATH . 'users.php');
    $UpdateUser = new \App\Users();
    $UpdateUser->setId($_GET['id']);
    if ($UpdateUser->show() && $UpdateUser->getRole() <= getUserRoleId()):
        echo getTitle(getAppPageName(), getAppPageSlug());
        if (isset($Response)): ?>
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
                            <?php
                            $help = '<small id="loginHelp" class="form-text text-muted">' . trans('En changeant votre login vous serez déconnecté du logiciel') . '</small>';
                            echo \App\Form::text('Login', 'login', 'text', $UpdateUser->getLogin(), true, 70, 'aria-describedby="loginHelp"', $UpdateUser->getId() == getUserIdSession() ? $help : '');
                            ?>
                        </div>
                        <div class="col-12 my-2">
                            <?= \App\Form::text('Nom', 'nom', 'text', $UpdateUser->getNom(), true, 40); ?>
                        </div>
                        <div class="col-12 my-2">
                            <?= \App\Form::text('Prénom', 'prenom', 'text', $UpdateUser->getPrenom(), true, 40); ?>
                        </div>
                        <?php if ($UpdateUser->getId() != getUserIdSession() && $UpdateUser->getRole() < (getTechnicienRoleId() + 1)): ?>
                            <div class="col-12 my-2">
                                <?= \App\Form::select('Rôle', 'role', array_map('trans', getRoles()), $UpdateUser->getRole(), true, '', getUserRoleId(), '>'); ?>
                            </div>
                        <?php endif; ?>
                        <div class="col-12 my-2">
                            <?= \App\Form::text('Email', 'email', 'email', $UpdateUser->getEmail(), false); ?>
                        </div>
                    </div>
                    <div class="my-2"></div>
                    <div class="row">
                        <div class="col-12">
                            <?= \App\Form::target('UPDATEUSER'); ?>
                            <?= \App\Form::submit('Enregistrer', 'UPDATEUSERSUBMIT'); ?>
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
                            <?= \App\Form::text('Nouveau Mot de passe', 'password', 'password', 'password', true, 150, 'autocomplete="off"'); ?>
                        </div>

                        <div class="col-12 my-2">
                            <?= \App\Form::text('Confirmation du Mot de passe', 'password2', 'password', '', true, 150, 'autocomplete="off"'); ?>
                        </div>
                    </div>
                    <div class="my-2"></div>
                    <div class="row">
                        <div class="col-12">
                            <?= \App\Form::target('UPDATEPASSWORD'); ?>
                            <?= \App\Form::submit('Enregistrer', 'UPDATEPASSWORDSUBMIT'); ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php else:
        echo getContainerErrorMsg(trans('Cet utilisateur n\'existe pas'));
    endif;
    require('footer.php');
else:
    echo trans('Cet utilisateur n\'existe pas');
endif; ?>