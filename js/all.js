function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function eraseCookie(name) {
    document.cookie = name + '=; Max-Age=-99999999;';
}

function copyToClipboard(text) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(text).select();
    document.execCommand("copy");
    $temp.remove();
}

function getKeyByValueInObject(object, value) {
    return Object.keys(object).find(key => object[key] === value);
}

function setLang(lang, interface_lang = false) {
    return $.post('/app/ajax/lang.php',
        {
            lang: lang,
            interfaceLang: !interface_lang ? 'content' : 'interface'
        });
}

function loaderHtml() {
    return '<i class="fas fa-circle-notch fa-spin"></i>';
}

function busyApp() {
    $('#appStatus').removeClass(function (index, className) {
        return (className.match(/\bbg-\S+/g) || []).join(' ');
    }).addClass('progress-bar-animated bg-warning').parent('div.progress').stop().animate({"height": "10px"}, 200);
}

function availableApp() {
    $('#appStatus').removeClass(function (index, className) {
        return (className.match(/\bbg-\S+/g) || []).join(' ');
    }).removeClass('progress-bar-animated').addClass('bg-light').parent('div.progress').stop().animate({"height": "1px"}, 200);
}

function getHtmlLoader() {
    return '<div class="spinner"><div class="rect1"></div><div class="rect2"></div>' +
        '<div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div>';
}

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

function systemAjaxRequest(data) {

    //Active Loader
    $('#loader').fadeIn('fast');
    $('#loaderInfos').html('Veuillez <strong>ne pas quitter</strong> votre navigateur');

    return $.post('/app/ajax/plugin.php', data);
}

$(document).ready(function () {

    var selectedFiles = [];

    $('#loader').fadeIn('slow');

    $(document).on('click', '.seeOnOverlay', function (event) {
        event.stopPropagation();
        event.preventDefault();
        var originSrc = $(this).data('originsrc');
        var $file = $(this).clone().attr('src', originSrc).removeClass().removeAttr('style');

        setTimeout(function () {
            $('#overlay #overlayContent').html($file);
            $('#overlay').css('display', 'flex').hide().fadeIn(200);
        }, 50);
    });

    $(document).on('click', '#overlay', function () {
        $(this).css('display', 'none');
        $('#overlay #overlayContent').html();
    });

    /**
     * Select medias from all media container
     */
    $(document).on('click', '.selectParentOnClick', function (event) {
        event.stopPropagation();
        event.preventDefault();


        var $btn = $(this);
        var $file = $btn.parent();
        var $filename = $file.data('filename');

        if ($file.hasClass('checkedFile')) {

            if ($.inArray($filename, selectedFiles) > -1) {
                selectedFiles.splice($.inArray($filename, selectedFiles), 1);
            }
            $btn.html('<i class="fas fa-plus"></i>');
            $file.removeClass('border borderColorPrimary checkedFile');

        } else {

            if ($.inArray($filename, selectedFiles) === -1) {
                selectedFiles.push($filename);
            }
            $btn.html('<i class="fas fa-check"></i>');
            $file.addClass('border borderColorPrimary checkedFile');
        }

        $('#inputSelectFiles').val(selectedFiles.length + ' médias');
        $('#textareaSelectedFile').val(selectedFiles.join('|||'));
        $('#saveMediaModalBtn').html(selectedFiles.length + ' médias');
    });

});

$(window).load(function () {
    $('#loader').fadeOut('slow');
    $('#site').css({
        display: 'block',
        opacity: 0,
        visibility: 'visible'
    }).animate({opacity: 1});

    $('img.seeDataOnHover').each(function (index) {

        var $Img = $(this);
        var img = new Image();

        if ($Img.attr('data-originsrc') !== undefined) {

            img.src = $Img.attr('data-originsrc');
            img.onload = function () {

                $('<div class="contentOnHover"><div class="d-none d-lg-block">Largeur: ' + this.width + 'px<br>Hauteur: ' + this.height + 'px</div>' +
                    '<div class="d-lg-none">' + this.width + 'px / ' + this.height + 'px</div></div>')
                    .insertAfter($Img).hide().fadeIn(500);
                $($Img).data('width', this.width);
                $($Img).data('height', this.height);
            };
        } else {
            $('<div class="contentOnHover"><div class="d-block">' + $Img.data('filename') + '</div></div>')
                .insertAfter($Img).hide().fadeIn(500);
        }
    });
});