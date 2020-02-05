<?php require('header.php');
require_once(WEB_PROCESS_PATH . 'users.php');
echo getTitle(getAppPageName(), getAppPageSlug());
showPostResponse(); ?>
<form action="" method="post" id="addProjetForm">
    <?= getTokenField(); ?>
    <div class="row">
        <div class="col-12 col-lg-6 my-2">
            <?= \App\Form::text('Login', 'login', 'text', !empty($_POST['login']) ? $_POST['login'] : '', true, 70, 'autocomplete="off"'); ?>
        </div>
        <div class="col-12 col-lg-6 my-2">
            <?= \App\Form::select('Rôle', 'role', array_map('trans', getRoles()), !empty($_POST['role']) ? $_POST['role'] : '', true, '', getUserRoleId(), '>'); ?>
        </div>
        <div class="col-12 col-lg-6 my-2">
            <?= \App\Form::text('Mot de passe', 'password', 'password', !empty($_POST['password']) ? $_POST['password'] : '', true, 150, 'autocomplete="off"'); ?>
            <span id="seePswd"
                  style="position: absolute;bottom: 0;right: 16px;padding: 5px 10px;font-size: 18px;cursor: pointer;color: #000;">
                <i class="far fa-eye"></i>
            </span>
        </div>
        <div class="col-12 col-lg-6 my-2">
            <?= \App\Form::text('Confirmation du Mot de passe', 'password2', 'password', !empty($_POST['password2']) ? $_POST['password2'] : '', true, 150, 'autocomplete="off"'); ?>
        </div>
        <div class="col-12 col-lg-5 my-2">
            <?= \App\Form::text('Email', 'email', 'email', !empty($_POST['email']) ? $_POST['email'] : ''); ?>
        </div>
        <div class="col-12 col-lg-4 my-2">
            <?= \App\Form::text('Nom', 'nom', 'text', !empty($_POST['nom']) ? $_POST['nom'] : '', true); ?>
        </div>
        <div class="col-12 col-lg-3 my-2">
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
<script type="text/javascript">
    $(document.body).on('click', '#seePswd', function (e) {
        e.preventDefault();

        let $btn = $(this);
        let $inputPass = $('input[name="password"]');

        if($inputPass.attr('type') === 'password'){
            $inputPass.attr('type', 'text');
            $btn.html('<i class="far fa-eye-slash"></i>');
        } else {
            $inputPass.attr('type', 'password');
            $btn.html('<i class="far fa-eye"></i>');
        }
    });
</script>
<?php require('footer.php'); ?>
