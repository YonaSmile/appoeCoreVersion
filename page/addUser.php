<?php require('header.php');
require_once(WEB_PROCESS_PATH . 'users.php');
echo getTitle($Page->getName(), $Page->getSlug());
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
    <div class="col-12 col-lg-6">
        <form action="" method="post" id="addProjetForm">
            <?= getTokenField(); ?>
            <div class="row">
                <div class="col-12 col-lg-6 my-2">
                    <?= \App\Form::text('Login', 'login', 'text', !empty($_POST['login']) ? $_POST['login'] : '', true, 70); ?>
                </div>
                <div class="col-12 col-lg-6 my-2">
                    <?= \App\Form::text('Mot de passe', 'password', 'password', !empty($_POST['password']) ? $_POST['password'] : '', true); ?>
                </div>
                <div class="col-12 col-lg-6 my-2">
                    <?= \App\Form::select('Rôle', 'role', array_map('trans', getRoles()), !empty($_POST['role']) ? $_POST['role'] : '', true, '', getUserRoleId(), '>'); ?>
                </div>
                <div class="col-12 col-lg-6 my-2">
                    <?= \App\Form::text('Email', 'email', 'email', !empty($_POST['email']) ? $_POST['email'] : ''); ?>
                </div>
                <div class="col-12 col-lg-6 my-2">
                    <?= \App\Form::text('Nom', 'nom', 'text', !empty($_POST['nom']) ? $_POST['nom'] : '', true); ?>
                </div>
                <div class="col-12 col-lg-6 my-2">
                    <?= \App\Form::text('Prénom', 'prenom', 'text', !empty($_POST['prenom']) ? $_POST['prenom'] : '', true); ?>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <?= \App\Form::target('ADDUSER'); ?>
                    <?= \App\Form::submit('Enregistrer', 'addUserSubmit'); ?>
                </div>
            </div>
        </form>
    </div>
</div>
<?php require('footer.php'); ?>
