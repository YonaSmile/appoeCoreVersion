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

function fixeTableHeader(top) {

    if ($('.fixed-header').length) {

        var thSize = [];
        var tdSize = [];
        var tablePosition = parseInt($('.fixed-header').offset().top);


        $('.fixed-header tbody tr:has(td) > *').each(function (index, val) {
            tdSize[index] = $(this).width();
        });

        $('.fixed-header thead th').each(function (index, val) {
            thSize[index] = $(this).width();
        });

        if (top > tablePosition) {
            $('.fixed-header thead').stop().css({
                top: (top - tablePosition),
                left: 0,
                position: 'absolute'
            });
            $('.fixed-header thead th').each(function (index, val) {
                $(this).width(thSize[index]);
            });

            var tdLength = $('.fixed-header tbody tr:has(td):eq(0) > *').length;
            if (tdLength > Object.keys(thSize).length) {
                $('.fixed-header tbody tr:has(td):eq(0) > *').each(function (index, val) {
                    if ($(this).width() == tdSize[index]) {
                        return false;
                    } else {
                        $('.fixed-header tbody tr:has(td) > *').each(function (index, val) {
                            $(this).width(tdSize[index]);
                        });
                        return false;
                    }

                });
            }

        } else {
            $('.fixed-header thead').css({top: 0, left: 0, position: 'static'});
        }

    }
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