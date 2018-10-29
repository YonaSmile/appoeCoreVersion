$(document).ready(function () {

    $('.deleteUser').click(function () {

        if (confirm('Vous allez archiver cet utilisateur !')) {
            var $btn = $(this);
            var idUser = $btn.data('iduser');

            $.post(
                '/app/ajax/users.php',
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

    $('.valideUser').click(function () {

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
                        $btn.parent('td').parent('tr').removeClass('table-warning');
                        $btn.remove();
                    }
                }
            );
        }
    });

});