CKEDITOR.replaceAll('ckeditor');
CKEDITOR.config.height = 400;
CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
CKEDITOR.config.shiftEnterMode = CKEDITOR.ENTER_P;
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
    {name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar']},
    {name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize']},
    {name: 'colors', items: ['TextColor', 'BGColor']},
    {name: 'tools', items: ['ShowBlocks']},
    {name: 'clipboard', groups: ['clipboard', 'undo'], items: ['PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']},
    {name: 'editing', groups: ['find', 'selection', 'spellchecker'], items: ['Scayt']},
    {name: 'document', groups: ['mode', 'document', 'doctools'], items: ['Source']}
];

$(document).ready(function () {

    //cookie for welcome message
    if (!getCookie('welcomeMsg')) {
        setCookie('welcomeMsg', 'OK', 356);
        Notif('Bienvenu sur APPOE', 'Ces notifications vont vous permettre de suivre de près l\'évolution de votre travail avec APPOE.', 10000);
    }

    //cookie for sidebar
    if (!getCookie('toggleSidebar')) {
        setCookie('toggleSidebar', 'open', 1);
    }

    if (getCookie('toggleSidebar') === 'close') {
        $('#sidebar, #mainContent').toggleClass('active');
        $('#navbarUser').toggleClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded="true"]').attr('aria-expanded', 'false');
    }

    //sidebar event
    $('.sidebarCollapse').on('click', function () {
        $('#sidebar, #mainContent').toggleClass('active');
        $('#navbarUser').toggleClass('active');
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

    //Sidebar open on plugin for user experience
    $('#sidebar > ul > li').each(function (i, val) {

        var menu = $(this);

        if ($(val).attr('id').indexOf('-') >= 0) {

            var pluginName = $(val).attr('id').split('-')[1];
            var urlPage = window.location.href.split('/');

            if (jQuery.inArray(pluginName, urlPage) >= 0) {
                if (menu.has('ul')) {
                    menu.children('a').attr('aria-expanded', 'true').removeClass('collapsed').next('ul').addClass('show');
                }
            }
        }
    });

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

    $('#languageSelectorContainer').on('mouseenter', function () {
        $(this).find('div.dropdown').addClass('show');
        $('#languageSelectorContent').addClass('show');
    });

    $('#languageSelectorContainer').on('mouseleave', function () {
        $(this).find('div.dropdown').removeClass('show');
        $('#languageSelectorContent').removeClass('show');
    });

    var appLang = $('html').attr('lang');
    var dataTableLang = '//cdn.datatables.net/plug-ins/1.10.16/i18n/French.json';

    //sort Table
    $('.sortableTable').DataTable({
        "language": {
            "url": dataTableLang
        },
        "info": false,
        "iDisplayLength": 25,
    });

    switch (appLang) {
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

    $('.langSelector').on('click', function () {

        var langChoice = $(this).attr('id');
        if (langChoice != appLang) {

            $('#languageSelectorContent').remove();

            var imgLangChoise = $(this).html();
            $('#languageSelectorBtn').html(imgLangChoise);

            setTimeout(function () {

                if (langChoice != appLang) {

                    $('#loader').fadeIn('fast');

                    setLang(langChoice).done(function (data) {
                        if (data) {
                            window.location.href = window.location.href;
                        }
                    });
                }
            }, 300);
        }
    });

});

$(window).on("load", function () {
    $("#sidebar").mCustomScrollbar({
        theme: "minimal"
    });
});