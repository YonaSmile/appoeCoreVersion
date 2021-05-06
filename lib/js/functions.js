const WEB_PROTOCOL_URL = window.location.protocol;
const WEB_DIR_URL = WEB_PROTOCOL_URL + '//' + window.location.hostname + '/';
const WEB_APP_URL = WEB_DIR_URL + 'app/';
const WEB_PLUGIN_URL = WEB_APP_URL + 'plugin/';

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

function PopupBlocked() {
    var PUtest = window.open(null, "", "width=100,height=100");
    try {
        PUtest.close();
        return false;
    } catch (e) {
        return true;
    }
}

function showExternalLink() {
    $('a').filter(function (index) {
        return $('img', this).length !== 1 && $(this).attr('target') === '_blank' && !$(this).hasClass('fa') && !$(this).is('[class*="icon"]');
    }).append('<img style="margin-left: 3px;width: 10px;height: 10px;vertical-align: text-top;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAABQCAYAAACOEfKtAAAAAXNSR0IArs4c6QAAAoJJREFUeJzt3PFN3DAUgPGvqAPcCNmAblBGuBFugzICG5QNjk7Q6wRlA7IBbABM0P4REOFwLsbPfvaz3idFgkiJyU/JiZgQ8DzP83ppAH4D/wosj8CF3qHU6ZoyeHPEd52VPJoKbbT33xvgQXvAL9oDKjQAW9LPxh8r2/Zolq0965+D3kIxeA640BLeU2Cdd9QS3g1wFVjvzTqFBw54sjU8cMDFYvDAAYPF4kEE4NeIATfAOfo30iPwJ/M+98AusP7XwnpRG+AnZW/O15brjMfzmTPvteRLeADuFwbUXO5jf+CVUvBAAPh3YUDtJcfkQCoeJALuFgasgSf93JXgQSLgIbDRjvJzbbmT4kEi4PFnn/ocW4Zy4EEi4PEGV58ctHa58CACsLcZadXf86AvwBJ4TykbWbyEc16284aU/VkDLIX32pa3ucEhZgNLgKXxkrIC2CQe2ABsFg/aB2waD9oGbB4P2gU0gQdtAprBg/YATeFBW4Dm8KAdQJN40AagWTyoD1gK7wK4Y3pMdy/c18lqApY88+6O9vktwz6D1QIsfdkWOa5WJlTVZ5Jz1QKgWTyoD2gaD+oCmseDeoBd4EEdwG7wQB+wKzzQBbykMzzQBQw9nGQaD3QBb4Dn2ffm8SDuGelcPTDdf+5evjYxs7KWJiBMcLVnuLNW+07EfA4ozAGFOaAwBxTmgMIcUJgDCnNAYQ4ozAGFOaAwBxTmgMJiAKP+uaTxVI/hlvfPkHx46aDBLvn4bEyWl2iEJlRH4Pvs+w0T4oFpQtRaW8JPYo2lBhwIv3SrpyXn20CCtfLehBLLiNLrC7b0dyYeUH73w8B0ut9iF3NkgjP/J1TP87zW+g9xQCOTh4NeSQAAAABJRU5ErkJggg==">');
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
    document.cookie = name + "=" + (value || "") + expires + "; path=/; SameSite=Secure";
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
    document.cookie = name + '=; Max-Age=-99999999;path=/';
}

function deleteAllCookies() {
    var cookies = document.cookie.split(";");

    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        var eqPos = cookie.indexOf("=");
        var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/";
    }
}

function financial(x, useSpace = true, fractionDigits = 2) {
    if (!useSpace) {
        return Number.parseFloat(x).toFixed(fractionDigits);
    }
    return numberWithSpaces(Number.parseFloat(x).toFixed(fractionDigits));
}

function numberWithSpaces(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    return parts.join(".");
}

function parseReelFloat(x) {
    return x ? Number.parseFloat(x.toString().replace(/ /g, "")) : 0;
}

function rgb2hex(rgb) {
    rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);

    function hex(x) {
        return ("0" + parseInt(x).toString(16)).slice(-2);
    }

    return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}

function hex2Rgb(hex) {
    return hex.replace(/^#?([a-f\d])([a-f\d])([a-f\d])$/i
        , (m, r, g, b) => '#' + r + r + g + g + b + b)
        .substring(1).match(/.{2}/g)
        .map(x => parseInt(x, 16))
}

function isUrlValid(url) {
    return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
}

function getMonthsName(month = null) {

    let months = ['', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août',
        'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    return month !== null ? months[month] : months;
}

function isIP(ipVal) {
    let expression = /((^\s*((([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))\s*$)|(^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$))/g;
    return expression.test(ipVal);
}

function escapeHtml(text) {

    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#39;',
        '/': '&#x2F;',
        '`': '&#x60;',
        '=': '&#x3D;'
    };

    return String(text).replace(/[&<>"'`=\/]/g, function (s) {
        return map[s];
    });
}

function decodeEscapedHtml(text) {
    return $("<div/>").html(text);
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

function mediaAjaxRequest(data) {
    return $.post('/app/ajax/media.php', data);
}

function systemAjaxRequest(data) {

    //Active Loader
    $('#loader').fadeIn('fast');
    $('#loaderInfos').html('Veuillez <strong>ne pas quitter</strong> votre navigateur');

    return $.post('/app/ajax/plugin.php', data);
}

function checkUserSessionExit() {
    return $.post('/app/ajax/plugin.php', {checkUserSession: 'OK'});
}

function recaptcha($form, recaptchaSiteKey, _callback) {
    if (recaptchaSiteKey.length > 0) {
        $.getScript('https://www.google.com/recaptcha/api.js?render=' + recaptchaSiteKey, function () {
            if (typeof grecaptcha !== 'undefined') {
                grecaptcha.ready(function () {

                    var gaction = $form.data('gaction') ? $form.data('gaction') : 'homepage';
                    grecaptcha.execute(recaptchaSiteKey, {action: gaction}).then(function (token) {
                        $form.append('<input type="hidden" name="g-recaptcha-response" value="' + token + '">');
                        _callback();
                    });
                });
            } else {
                console.log('Not imported recaptcha library !');
            }
        });
    }
}

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

function processFormPostRequest($form, $recaptchaPublicKey) {

    //Prepare responses container
    $('#freturnMsg').remove();
    $('<div id="freturnMsg"></div>').insertAfter($form);
    let $result = $('#freturnMsg');
    $result.html(getHtmlLoader() + ' Envoi en cours...');

    //Add formType POST
    let ftype = $form.data('ftype') ? $form.data('ftype') : 'contact';
    if (!$('input[name="formType"]', $form).length) {
        $form.prepend('<input name="formType" type="hidden" value="' + ftype + '">');
    }

    //Add subject POST
    if ($form.data('fobject') && !$('input[name="object"]', $form).length) {
        $form.prepend('<input name="object" type="hidden" value="' + $form.data('fobject') + '">');
    }

    //Get form responses
    let errorMsg = $form.data('error') ? $form.data('error') : 'Une erreur s\'est produite !';
    let successMsg = $form.data('success') ? $form.data('success') : 'Votre demande a bien été envoyé !';

    //Check Recaptcha V3
    recaptcha($form, $recaptchaPublicKey, function () {

        //Send all form inputs
        sendPostFiles($form).done(function (data) {
            if (data === true || data == 'true') {
                $form.trigger("reset");
                $result.html(successMsg);
            } else {
                $result.html(errorMsg);
            }
        });
    });
}

var delay = (function () {
    var timer = 0;
    return function (callback, ms) {
        clearTimeout(timer);
        timer = setTimeout(callback, ms);
    };
})();

function fixeTableHeader(topAdd = 0, otherTop = 0) {

    var $fixedHeader = $('.fixed-header');
    if ($fixedHeader.length) {

        var $thead;
        var changeSize = false;
        var thSize = [];
        var top = otherTop === 0 ? $(window).scrollTop() + topAdd : otherTop;
        var tablePosition = parseInt($fixedHeader.offset().top);
        var tableHeight = parseInt($fixedHeader.height());

        //Check table's header cloned
        if (!$fixedHeader.hasClass('cloned')) {
            $fixedHeader.addClass('cloned');
            $thead = $('.fixed-header thead').clone().insertAfter('.fixed-header thead').addClass('clonedHead').hide();
        } else {
            $thead = $('.fixed-header.cloned thead.clonedHead');
        }

        //Responsive : recalculate the width
        if (!getCookie('screenDim') || getCookie('screenDim') !== $fixedHeader.width()) {
            setCookie('screenDim', $('.fixed-header').width());

            changeSize = true;

            $('.fixed-header thead th').each(function (index, val) {
                thSize[index] = $(this).width();
            });

            $('th', $thead).each(function (index, val) {
                $(this).width(thSize[index]);
            });
        }

        //Check if cloned header is needed
        if (tableHeight && top > tablePosition && (top - tablePosition < tableHeight)) {

            if (changeSize) {
                $('th', $thead).each(function (index, val) {
                    $(this).width(thSize[index]);
                });
            }

            $thead.stop().css({
                top: (top - tablePosition),
                left: 0,
                position: 'absolute'
            });

            if ($thead.is(":hidden")) {
                $thead.show();
            }

        } else {
            $thead.hide().css({top: 0, left: 0, position: 'static'});
        }
    }
}

function isMobile() {
    return navigator.userAgent.match(/(iPad)|(iPhone)|(iPod)|(Android)|(PlayBook)|(BB10)|(BlackBerry)|(Opera Mini)|(IEMobile)|(webOS)|(MeeGo)/i);

}

function isTouch() {
    return isMobile() !== null || document.createTouch !== undefined || ('ontouchstart' in window) || ('onmsgesturechange' in window) || navigator.msMaxTouchPoints;
}

function supportSVG() {
    return !!document.createElementNS && !!document.createElementNS('http://www.w3.org/2000/svg', 'svg').createSVGRect;
}

function countChars($input, type) {

    let maxLengthTable = {'title': 70, 'slug': 70, 'description': 158}

    let inputLength = $input.val().length;
    let inputSeoClass;

    if (inputLength < (maxLengthTable[type] / 4)) {
        inputSeoClass = 'danger'
    } else if (inputLength < (maxLengthTable[type] / 2)) {
        inputSeoClass = 'warning'
    } else if (inputLength < (maxLengthTable[type] / 1.2)) {
        inputSeoClass = 'info'
    } else {
        inputSeoClass = 'success'
    }

    let maxLength = '<span class="text-' + inputSeoClass + '">' + $input.val().length + '</span>/' + maxLengthTable[type];
    let id = $input.attr('id');

    $('span#maxLengthCount-' + id).html(maxLength);
}

!function (e) {
    e.fn.serializefiles = function () {
        var n = e(this), i = new FormData, a = n.serializeArray();
        return e.each(n.find('input[type="file"]'), function (n, a) {
            e.each(e(a)[0].files, function (e, n) {
                i.append(a.name, n)
            })
        }), e.each(a, function (e, n) {
            i.append(n.name, n.value)
        }), i
    }
}(jQuery);
!function (a) {
    a.fn.pagination = function (e = {}) {
        return this.each(function () {
            let t = a(this);
            if (t.length) {
                let l = {
                    categoriesBtnData: "data-category",
                    items: 6,
                    previous: '<span aria-hidden="true">&laquo;</span>',
                    next: '<span aria-hidden="true">&raquo;</span>',
                    defaultCategory: 'all'
                };
                jQuery.extend(l, e);
                let s = "[" + l.categoriesBtnData + "]", o = a(s), r = l.items, c = t.children();

                function n(e) {
                    let n = "", s = e.length;
                    if (s > r) {
                        let o = Math.floor(s / r), c = s % r, g = o;
                        if (o >= 1) for (let a = 1; a <= o; a++) n += '<li class="page-item"><a class="page-link" href="#' + a + '">' + a + "</a></li>";
                        c > 0 && (n += '<li class="page-item"><a class="page-link" href="#' + ++g + '">' + g + "</a></li>");
                        let p = 0;
                        e.each(function (e, t) {
                            e % r == 0 && p++, a(t).attr("data-page", "#" + p)
                        });
                        let f = '<nav aria-label="Page navigation" class="navPagination"><ul class="pagination justify-content-center"><li class="page-item"><a class="page-link" href="#prev" aria-label="Previous">' + l.previous + "</a></li> " + n + '<li class="page-item"><a class="page-link" href="#next" aria-label="Next">' + l.next + "</a></li> </ul></nav>";
                        a(f).insertAfter(t), i(e, "#1")
                    } else e.fadeIn()
                }

                function i(e, n = "") {
                    e.hide(), a("a.page-link").removeClass("active"), "" !== n && (a('a.page-link[href="' + n + '"]').addClass("active"), a('[data-page="' + n + '"]', t).fadeIn())
                }

                n(c), o.length && a(document.body).on("click", s, function (e) {
                    e.preventDefault(), e.stopPropagation();
                    let i = a(this), s = i.attr(l.categoriesBtnData);
                    i.hasClass("active") || (o.removeClass("active"), i.addClass("active"), t.children().hide().removeClass("showByCategory").removeAttr("data-page"), a("nav.navPagination").remove(), "all" === s ? n(t.children()) : (a("[data-filter*=" + s + "]", t).addClass("showByCategory"), n(a(".showByCategory", t))))
                }), a(document.body).on("click", ".navPagination a.page-link", function (e) {
                    e.preventDefault(), e.stopPropagation();
                    let n = a(this).attr("href");
                    if ("#prev" === n || "#next" === n) {
                        let e = parseInt(a("a.page-link.active", ".navPagination").attr("href").substring(1)), l = 0;
                        "#prev" === n ? l = e - 1 : "#next" === n && (l = e + 1), a('.navPagination a.page-link[href="#' + l.toString() + '"]').length && (i(c, "#" + l.toString()), a("html, body").animate({scrollTop: parseInt(a(t).offset().top) - 150}, 500))
                    } else a(this).hasClass("active") || (i(c, n), a("html, body").animate({scrollTop: parseInt(a(t).offset().top) - 150}, 500))
                });
                if(l.defaultCategory!=='all'){a('['+l.categoriesBtnData+'="'+l.defaultCategory+'"]').trigger('click')}
            }
        })
    }
}(jQuery);