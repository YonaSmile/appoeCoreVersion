<?php require( 'header.php' ); ?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="bigTitle"><?= trans('Nouvel utilisateur'); ?></h1>
            <hr class="my-4">
        </div>
    </div>
	<?php require_once( WEB_PROCESS_PATH . 'users.php' ); ?>

	<?php if ( isset( $Response ) ): ?>
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
				<?= App\Form::text( trans('Email'), 'email', 'email', ! empty( $_POST['email'] ) ? $_POST['email'] : '', true ); ?>
            </div>
            <div class="col-12 my-2">
				<?= App\Form::text( trans('Mot de passe'), 'password', 'password', ! empty( $_POST['password'] ) ? $_POST['password'] : '', true ); ?>
            </div>
            <div class="col-12 my-2">
				<?= App\Form::select( trans('Rôle'), 'role', array_map('trans',ROLES), ! empty( $_POST['role'] ) ? $_POST['role'] : '', true, '', $User->getRole(), '>' ); ?>
            </div>
            <div class="col-12 my-2">
				<?= App\Form::text( trans('Nom'), 'nom', 'text', ! empty( $_POST['nom'] ) ? $_POST['nom'] : '', true ); ?>
            </div>
            <div class="col-12">
				<?= App\Form::text( trans('Prénom'), 'prenom', 'text', ! empty( $_POST['prenom'] ) ? $_POST['prenom'] : '', true ); ?>
            </div>
        </div>
        <div class="my-2"></div>
        <div class="row">
            <div class="col-12">
                <button type="submit" name="ADDUSER"
                        class="btn btn-outline-primary btn-block btn-lg">
                    <?= trans('Enregistrer'); ?>
                </button>
            </div>
        </div>
    </form>
    <div class="my-4"></div>
</div>
<?php require( 'footer.php' ); ?>
