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

});