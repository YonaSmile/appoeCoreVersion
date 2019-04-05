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

function setLang(lang, interface_lang = false) {
    return $.post('/app/ajax/lang.php',
        {
            lang: lang,
            interfaceLang: !interface_lang ? 'content' : 'interface'
        });
}

/**
 * Input filter results by "data-filter"
 * @param inputId
 * @param elements like "div.card"
 */
function inputFilter(inputId, elements) {
    var input, filter, element, i, txtValue;
    input = $('#' + inputId);
    filter = input.val().toUpperCase();
    element = $(elements);

    for (i = 0; i < element.length; i++) {
        txtValue = element[i].getAttribute('data-filter');
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            element[i].style.display = "";
        } else {
            element[i].style.display = "none";
        }
    }
}

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

function financial(x, useSpace = true) {
    if (!useSpace) {
        return Number.parseFloat(x).toFixed(2);
    }
    return numberWithSpaces(Number.parseFloat(x).toFixed(2));
}

function numberWithSpaces(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    return parts.join(".");
}

function parseReelFloat(x) {
    return x ? Number.parseFloat(x.toString().replace(/ /g, "")) : 0;
}

function getMonthsName(month = null) {

    let months = ['', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août',
        'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    return month !== null ? months[month] : months;
}

function escapeHtml(text) {
    return text
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
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

function mediaAjaxRequest(data) {
    return $.post('/app/ajax/media.php', data);
}

function systemAjaxRequest(data) {

    //Active Loader
    $('#loader').fadeIn('fast');
    $('#loaderInfos').html('Veuillez <strong>ne pas quitter</strong> votre navigateur');

    return $.post('/app/ajax/plugin.php', data);
}

(function ($) {
    $.fn.serializefiles = function () {

        var form = $(this),
            formData = new FormData(),
            formParams = form.serializeArray();

        $.each(form.find('input[type="file"]'), function (i, tag) {
            $.each($(tag)[0].files, function (i, file) {
                formData.append(tag.name, file);
            });
        });

        $.each(formParams, function (i, val) {
            formData.append(val.name, val.value);
        });

        return formData;

    };
})(jQuery);

function sendPostFiles($form) {

    return $.ajax({
        url: $form.attr('action'),
        method: 'POST',
        type: 'POST',
        data: $form.serializefiles(),
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        dataType: "json"
    });
}