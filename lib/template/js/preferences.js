jQuery(document).ready(function ($) {

    $(document.body).on('change', '.updatePreference', function () {

        busyApp(false);
        let name = $(this).attr('name');
        let value = $(this).is(':checked');
        let type = $(this).attr('data-config-type');
        $.post('/app/ajax/config.php', {
            configName: name,
            configType: type,
            configValue: value
        }).done(function (data) {
            if (data == 'true' || data === true) {
                alert('Enregistré');
            } else {
                alert('Problèmes');
            }
            availableApp();
        });
    });

    $(document.body).on('click', '#submitAddPermissionAccess', function () {

        busyApp(false);
        let $input = $('input[name="addPermissionAccess"]');
        if ($input.val()) {
            if (isIP($input.val())) {

                $.post('/app/ajax/config.php', {
                    addAccessPermission: 'OK',
                    ipAddress: $input.val()
                }).done(function (data) {
                    if (data == 'true' || data === true) {
                        $('#allPersimissions .row').append('<div class="col-12 text-success">' + $input.val() + '</div>');
                        $input.val('');
                    } else {
                        alert('Problèmes');
                    }
                    availableApp();
                });

            } else {
                alert('L\'adresse IP est incorrecte !');
            }
        }
    });

    $(document.body).on('click', '.deleteIp', function () {

        busyApp(false);
        let $div = $(this).closest('div');
        let ip = $div.data('ip');
        if (ip && isIP(ip)) {

            $.post('/app/ajax/config.php', {
                deleteAccessPermission: 'OK',
                ipAddress: ip
            }).done(function (data) {
                if (data == 'true' || data === true) {
                    $div.effect('highlight').remove();
                } else {
                    alert('Problèmes');
                }
                availableApp();
            });

        } else {
            alert('L\'adresse IP est incorrecte !');
        }
    });

    $(document.body).on('mouseenter', 'div.ipAccess', function () {
        $(this).prepend('<i class="fas fa-times text-danger deleteIp" style="cursor:pointer"></i> ').css('font-weight', 'bold');
    });

    $(document.body).on('mouseleave', 'div.ipAccess', function () {
        $(this).html($(this).data('ip')).css('font-weight', 'normal');
    });

    $(document.body).on('click', '#restoreConfig', function () {

        if (confirm('Vous êtes sur le point de rétablir les préférences par défaut')) {

            var $btn = $(this);
            $btn.html(loaderHtml()).prop('disabled', true);

            busyApp(false);
            $.post('/app/ajax/config.php', {restoreConfig: 'OK'}).done(function (data) {
                if (data == 'true' || data === true) {
                    window.location.href = window.location.href;
                } else {
                    alert('Un problème est survenu lors du rétablissement par défaut des paramètres');
                }
                availableApp();
            });
        }
    });

    $(document.body).on('click', '#clearFilesCache', function () {

        if (confirm('Vous êtes sur le point de vider tous le cache des fichiers')) {

            var $btn = $(this);
            $btn.html(loaderHtml());

            busyApp(false);
            $.post('/app/plugin/cms/process/ajaxProcess.php', {clearFilesCache: 'OK'}).done(function (data) {
                if (data == 'true' || data === true) {
                    $btn.html('<i class="fas fa-check"></i> Cache des fichiers vidé!').blur()
                        .removeClass('btn-outline-danger').addClass('btn-success');
                } else {
                    alert('Un problème est survenu lors de la vidange du cache');
                }
                availableApp();
            });
        }
    });

    $(document.body).on('click', '#clearServerCache', function () {

        if (confirm('Vous êtes sur le point de purger le cache du serveur')) {

            var $btn = $(this);
            $btn.html(loaderHtml());

            busyApp(false);
            $.post('/app/ajax/config.php', {clearServerCache: 'OK'}).done(function (data) {
                if (data == 'true' || data === true) {
                    $btn.html('<i class="fas fa-check"></i> Cache du serveur purgé!').blur()
                        .removeClass('btn-outline-danger').addClass('btn-success');
                } else {
                    alert('Un problème est survenu lors de la purge du cache');
                }
                availableApp();
            });
        }
    });
});