$(document).ready(function () {

    var Roles = {};

    $.post('/app/ajax/users.php', {GETUSERSROLES: 'OK'},
        function (data) {
            if (data) {
                Roles = JSON.parse(data);
            }
        }
    );

    $('#permissionTable').on('click', '.updatePermissionBtn', function () {

        var $btn = $(this);
        var idMenu = $btn.data('idmenu');

        $btn.removeClass('btn-warning updatePermissionBtn').addClass('btn-success checkPermissionBtn').html('<i class="fas fa-save"></i>');
        var $TR = $btn.parent('td').parent('tr');

        $TR.find('td.changeableTd').each(function () {
            var originalContent = $(this).text();

            if ($(this).data('dbname') === 'min_role_id') {
                var roleId = $.trim($(this).text());
                if (roleId > Object.keys(Roles).length) {
                    roleId = 1;
                }
                originalContent = getKeyByValueInObject(Roles, roleId);
            }

            $(this).html('<input value="' + originalContent + '" class="w-100">');
        });
    });

    $('#permissionTable').on('click', '.checkPermissionBtn', function () {

        var $btn = $(this);
        $btn.html(loaderHtml());

        var idMenu = $btn.data('idmenu');
        var $TR = $btn.parent('td').parent('tr');

        var name = $TR.find('td[data-dbname="name"]').children('input').val();
        var slug = $TR.find('td[data-dbname="slug"]').children('input').val();
        var role = $TR.find('td[data-dbname="min_role_id"]').children('input').val();
        var statut = $TR.find('td[data-dbname="statut"]').children('input').val();
        var order = $TR.find('td[data-dbname="order_menu"]').children('input').val();
        var pluginName = $TR.find('td[data-dbname="pluginName"]').children('input').val();


        $TR.find('td.changeableTd').each(function () {

            var originalContent = $(this).children('input').val();

            if ($(this).data('dbname') === 'min_role_id') {
                originalContent = Roles[$(this).children('input').val()];
            }

            $(this).html(originalContent);
        });

        if (name.length > 0 && role > 0 && order > 0) {
            busyApp();
            $.post(
                '/app/ajax/permissions.php',
                {
                    updatePermission: 'OK',
                    id: idMenu,
                    name: name,
                    slug: slug,
                    min_role_id: role,
                    statut: statut,
                    order_menu: order,
                    pluginName: pluginName
                },
                function (data) {
                    if (data && (data == 'true' || data === true)) {
                        $btn.removeClass('checkPermissionBtn').html('<i class="fas fa-check"></i>');
                        availableApp();

                        setTimeout(function () {
                            $btn.removeClass('btn-success').addClass('updatePermissionBtn').html('<span class="btnEdit"><i class="fas fa-wrench"></i></span>');
                        }, 2000);
                    }
                }
            )
        }
    });

    $('#addPermissionBtn').on('click', function (event) {
        event.stopPropagation();
        event.preventDefault();

        busyApp();

        $.post(
            '/app/ajax/permissions.php',
            $('#addPermissionForm').serialize(),
            function (data) {
                if (data && (data == 'true' || data === true)) {
                    availableApp();
                    $('#loader').fadeIn(400);
                    location.reload();
                } else {
                    $('#permissionFormInfos')
                        .html('<p class="bg-danger text-white">Une erreur s\'est produite. Réessayer ultérieurement</p>');
                }
            }
        )
    });
});