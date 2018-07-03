</div><!-- END BASE -->
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
    CKEDITOR.config.toolbar = [
        {
            name: 'basicstyles',
            groups: ['basicstyles', 'cleanup'],
            items: ['Bold', 'Italic', 'Underline', 'Strike', 'Superscript', '-', 'RemoveFormat']
        },
        {
            name: 'paragraph',
            groups: ['list', 'indent', 'blocks', 'align', 'bidi'],
            items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']
        },
        {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
        {name: 'insert', items: ['Image', 'Table', 'HorizontalRule']},
        {name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize']},
        {name: 'colors', items: ['TextColor', 'BGColor']},
        {name: 'tools', items: ['ShowBlocks']},
        {name: 'clipboard', groups: ['clipboard', 'undo'], items: ['PasteFromWord', '-', 'Undo', 'Redo']},
        {name: 'editing', groups: ['find', 'selection', 'spellchecker'], items: ['Scayt']}
    ];
</script>
<script>
    (function ($) {

        //cookie for sidebar
        if (!getCookie('toggleSidebar')) {
            setCookie('toggleSidebar', 'open', 1);
        }

        if (getCookie('toggleSidebar') === 'close') {
            $('#sidebar, #mainContent').toggleClass('active');
            $('.collapse.in').toggleClass('in');
            $('a[aria-expanded="true"]').attr('aria-expanded', 'false');
        }

        //sidebar event
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar, #mainContent').toggleClass('active');
            $('.collapse.in').toggleClass('in');
            $('a[aria-expanded="true"]').attr('aria-expanded', 'false');

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
        $('form').on('submit', function () {
            $('[type="submit"]', this).attr('disabled', 'disabled').html(loaderHtml()).addClass('disabled');
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

        $(window).on("load", function () {
            $("#sidebar").mCustomScrollbar({
                theme: "minimal"
            });
        });
    })(jQuery);
</script>
</body>
</html>