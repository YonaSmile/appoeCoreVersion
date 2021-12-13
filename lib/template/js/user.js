$(document).ready(function () {

    $(document.body).on('click', '.bannishUser', function () {

        if (confirm('Vous allez bannir cet utilisateur !')) {
            var $btn = $(this);
            var idUser = $btn.data('iduser');

            $.post(
                '/app/ajax/users.php',
                {
                    idDeleteUser: idUser
                },
                function (data) {
                    if (true === data || data == 'true') {
                        $('div.admin-tab[data-user="' + idUser + '"]').find('div.admin-tab-header').addClass('bg-secondary');
                        $('div.admin-tab[data-user="' + idUser + '"]').find('div.admin-tab-content-header button.bannishUser')
                            .removeClass('bannishUser').addClass('valideUser').html('Valider');
                        closeOffCanvas();
                    }
                }
            );
        }
    });

    $(document.body).on('click', '.defaultEmailUser', function () {

        if (confirm($(this).data('email') + ' deviendra l\'adresse Email par défaut du back-office !')) {
            let $btn = $(this);
            let icon = $btn.find('span');

            busyApp(false);
            setPreference('DATA', 'defaultEmail', $(this).data('email')).done(function (data) {
                if (data == 'true' || data === true) {

                    $('button.defaultEmailUser').each(function (num, el) {
                        $(el).prop('disabled', false);
                        $(el).find('span').removeClass('text-success');
                    }).promise().done(function () {
                        $btn.prop('disabled', true);
                        icon.addClass('text-success');
                    });
                } else {
                    alert('Problèmes');
                }
                availableApp();
            });
        }
    });

    $(document.body).on('click', '.valideUser', function () {

        if (confirm('Vous allez accepter cet utilisateur !')) {
            var $btn = $(this);
            var idUser = $btn.data('iduser');

            $.post(
                '/app/ajax/users.php',
                {
                    idValideUser: idUser
                },
                function (data) {
                    if (true === data || data == 'true') {
                        $btn.html('Bannir');
                        $('div.admin-tab[data-user="' + idUser + '"]').find('div.admin-tab-header').removeClass('bg-secondary');
                        $('div.admin-tab[data-user="' + idUser + '"]').find('div.admin-tab-content-header button.valideUser')
                            .removeClass('valideUser').addClass('bannishUser').html('Bannir');
                    }
                }
            );
        }
    });

    $(document.body).on('click', '#seePswd', function (e) {
        e.preventDefault();

        let $btn = $(this);
        let $inputPass = $('input[name="password"]');

        if ($inputPass.attr('type') === 'password') {
            $inputPass.attr('type', 'text');
            $btn.html('<i class="far fa-eye-slash"></i>');
        } else {
            $inputPass.attr('type', 'password');
            $btn.html('<i class="far fa-eye"></i>');
        }
    });
});