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

$(document).ready(function () {
    $('#loader').fadeIn('slow');

    $('.seeOnOverlay').on('click', function (event) {
        event.preventDefault();
        var originSrc = $(this).data('originsrc');
        var $file = $(this).clone().attr('src', originSrc).removeClass();

        $('#overlay #overlayContent').html($file);
        $('#overlay').css('display', 'flex').hide().fadeIn(200);
    });

    $('#overlay').on('click', function () {
        $(this).css('display', 'none');
        $('#overlay #overlayContent').html();
    });
});

$(window).load(function () {
    $('#loader').fadeOut('slow');
    $('#site').css({
        display: 'block',
        opacity: 0,
        visibility: 'visible'
    }).animate({opacity: 1});
});