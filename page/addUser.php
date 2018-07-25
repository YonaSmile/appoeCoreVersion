<?php require('header.php'); ?>
<?= getTitle($Page->getName(), $Page->getSlug()); ?>
<div class="container-fluid">
    <?php require_once(WEB_PROCESS_PATH . 'users.php'); ?>

    <?php if (isset($Response)): ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-<?= $Response->display()->status ?>" role="alert">
                    <?= $Response->display()->error_msg; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <form action="" method="post" id="addProjetForm">
        <?= getTokenField(); ?>

        <div class="row">
            <div class="col-12 my-2">
                <?= App\Form::text('Email', 'email', 'email', !empty($_POST['email']) ? $_POST['email'] : '', true); ?>
            </div>
            <div class="col-12 my-2">
                <?= App\Form::text('Mot de passe', 'password', 'password', !empty($_POST['password']) ? $_POST['password'] : '', true); ?>
            </div>
            <div class="col-12 my-2">
                <?= App\Form::select('Rôle', 'role', array_map('trans', ROLES), !empty($_POST['role']) ? $_POST['role'] : '', true, '', $USER->getRole(), '>'); ?>
            </div>
            <div class="col-12 my-2">
                <?= App\Form::text('Nom', 'nom', 'text', !empty($_POST['nom']) ? $_POST['nom'] : '', true); ?>
            </div>
            <div class="col-12 my-2">
                <?= App\Form::text('Prénom', 'prenom', 'text', !empty($_POST['prenom']) ? $_POST['prenom'] : '', true); ?>
            </div>
        </div>
        <div class="my-2"></div>
        <div class="row">
            <div class="col-12">
                <?= App\Form::target('ADDUSER'); ?>
                <?= App\Form::submit('Enregistrer', 'addUserSubmit'); ?>
            </div>
        </div>
    </form>
</div>
<?php require('footer.php'); ?>
