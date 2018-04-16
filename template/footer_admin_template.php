</div><!-- END DIV MAIN CONTENT-->
</div><!-- END DIV MAIN -->
<div id="notifications_footer"><?php getSessionNotifications(); ?></div>
<?php App\Flash::constructAndDisplay(); ?>
<div id="overlay">
    <div id="overlayContent" class="overlayContent"></div>
</div>
</div><!-- END DIV SITE -->

<!-- MODAL INFO -->
<div class="modal fade" id="modalInfo" tabindex="-1" role="dialog" aria-labelledby="modalTitle"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
            </div>
            <div class="modal-body" id="modalBody"></div>
            <div class="modal-footer" id="modalFooter">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= trans('Fermer'); ?></button>
            </div>
        </div>
    </div>
</div>
<!-- MODAL INFO END -->
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
<script>
    CKEDITOR.replaceAll('ckeditor');
    CKEDITOR.config.height = 400;
</script>
<script>
    function convertToSlug(str) {
        str = str.replace(/^\s+|\s+$/g, ''); // trim
        str = str.toLowerCase();

        // remove accents, swap ñ for n, etc
        var from = "ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;";
        var to = "aaaaaeeeeeiiiiooooouuuunc------";
        for (var i = 0, l = from.length; i < l; i++) {
            str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
        }

        str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
            .replace(/\s+/g, '-') // collapse whitespace and replace by -
            .replace(/-+/g, '-'); // collapse dashes

        return str;
    }

    $(document).ready(function () {

        //cookie for sidebar
        if (!getCookie('toggleSidebar')) {
            setCookie('toggleSidebar', 'open', 1);
        }

        if (getCookie('toggleSidebar') === 'close') {
            $('#sidebar, #mainContent').toggleClass('active');
            $('.collapse.in').toggleClass('in');
            $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        }

        //sidebar event
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar, #mainContent').toggleClass('active');
            $('.collapse.in').toggleClass('in');
            $('a[aria-expanded=true]').attr('aria-expanded', 'false');

            if ($("#mainContent").hasClass('active')) {
                setCookie('toggleSidebar', 'close', 1);
            } else {
                setCookie('toggleSidebar', 'open', 1);
            }

            if ($(document).width() <= 750) {
                setCookie('toggleSidebar', 'open', 1);
                if ($("#mainContent").hasClass('active')) {
                    $("#mainContent.active").css("width", $(document).width() - 250 + 'px');
                } else {
                    $("#mainContent").css("width", '100%');
                }
            }
            getCookie('toggleSidebar');
        });

        $('li.active').parent('ul').addClass('show').prev('a').attr('aria-expanded', 'true').removeClass('collapsed');

        //anchor link event
        $('html,body').on('click', 'a[href^="#"]:not(.sidebarLink)', function (e) {
            e.preventDefault();
            $('html,body').animate({scrollTop: $($(this).attr('href')).offset().top}, 'slow');
        });

        //datePicker
        $('.datepicker').datepicker({
            altFormat: "yy-mm-dd",
            dateFormat: "yy-mm-dd",
            dayNames: ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"],
            dayNamesMin: ["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa"],
            dayNamesShort: ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"],
            monthNames: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
            monthNamesShort: ["Jan", "Fev", "Mar", "Avr", "Mai", "Jun", "Jul", "Aou", "Sep", "Oct", "Nov", "Dec"],
            minDate: 0
        });

        var dataTableLang = '//cdn.datatables.net/plug-ins/1.10.16/i18n/French.json';

        switch ('<?= LANG; ?>') {
            case 'fr':
                dataTableLang = '//cdn.datatables.net/plug-ins/1.10.16/i18n/French.json';
                break;
            case 'en':
                dataTableLang = '//cdn.datatables.net/plug-ins/1.10.16/i18n/English.json';
                break;
            case 'de':
                dataTableLang = '//cdn.datatables.net/plug-ins/1.10.16/i18n/German.json';
                break;
            default:
                dataTableLang = '//cdn.datatables.net/plug-ins/1.10.16/i18n/French.json';
                break;
        }

        //sort Table
        $('.sortableTable').DataTable({
            "language": {
                "url": dataTableLang
            },
            "info": false,
            "iDisplayLength": 25,
        });

        //Loading text on submit form
        $('form').submit(function () {
            $('[type="submit"]', this).html('<?= trans('Chargement'); ?>').addClass('disabled');
            $('input, textarea').addClass('disabled');
            $('#chargement').fadeIn();
        });

        //clean input
        $('form input').keyup(function (e) {

            var code = e.keyCode || e.which;
            var $input = $(this).val();
            if (code == 13) {
                return false;
            }
            var rep = $input.replace(/`|<|>|\\/gi, ' ');
            $(this).val(rep);
        });

        //clean input
        $('form input').blur(function (e) {
            var $input = $(this).val();
            var rep = $input.replace(/`|<|>|\\/gi, ' ');
            $(this).val(rep);
        });

        $('#notifications_footer').on('click', '.deleteInfoSession', function () {
            var infoSession = $(this).data('idnotif');
            var $parent = $(this).parent('div');
            $.post(
                '<?= WEB_DIR; ?>app/ajax/notifications.php',
                {
                    deleteNotifsFromSession: 'OK',
                    idNotif: infoSession
                },
                function (data) {
                    if (data === true || data == 'true') {
                        $parent.slideUp();
                    } else {
                        alert('<?= trans('Un problème est survenu lors de la suppression de la notification'); ?>');
                    }
                }
            );
        });

        $('#notifications_footer').on('click', '.deleteAlertSession', function () {
            var infoSession = $(this).data('idalert');
            var $parent = $(this).parent('div');
            $.post(
                '<?= WEB_DIR; ?>app/ajax/notifications.php',
                {
                    deleteNotifsFromSession: 'OK',
                    idAlert: infoSession
                },
                function (data) {
                    if (data === true || data == 'true') {
                        $parent.slideUp();
                    } else {
                        alert('<?= trans('Un problème est survenu lors de la suppression de la notification'); ?>');
                    }
                }
            );
        });

        $('.md-select').on('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            $(this).toggleClass('active');
        });

        $('.md-select ul li').on('click', function () {
            var v = $(this).text();
            $('.md-select ul li').not($(this)).removeClass('active');
            $(this).addClass('active');
            $('.md-select label button').text(v);

            setTimeout(function () {
                if (v != "<?= LANG; ?>") {
                    $('#loader').fadeIn('fast');
                    $.post(
                        '<?= WEB_DIR; ?>app/ajax/lang.php',
                        {
                            lang: v
                        }, function (data) {
                            if (data) {
                                window.location = window.location.href;
                                window.location.reload(true);
                            }
                        }
                    );
                }
            }, 300);
        });
    });


    (function ($) {
        $(window).on("load", function () {
            $("#sidebar").mCustomScrollbar({
                theme: "minimal"
            });
        });
    })(jQuery);

</script>
</body>
</html>