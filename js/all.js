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

function Notif(title, text = '', timeout = 4000, icon = '/app/images/logo_app.png') {
    Push.create(title, {
        body: text,
        icon: icon,
        timeout: timeout,
        onClick: function () {
            window.focus();
            this.close();
        }
    });
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
     * Clear all selected medias
     */
    $(document).on('click', '#closeAllMediaModalBtn', function (event) {
        event.stopPropagation();
        event.preventDefault();

        $('.checkedFile').each(function (i) {
            $(this).children('button.selectParentOnClick').trigger('click');
        });
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

    /**
     * Delete medias definitely
     */
    $(document).on('click', '.deleteDefinitelyImageByName', function (event) {
        event.stopPropagation();
        event.preventDefault();


        var $btn = $(this);
        var $addBtn = $btn.prev('button.selectParentOnClick');
        var $file = $btn.parent();
        var $filename = $btn.data('imagename');

        if (confirm('Vous allez supprimer ce fichier définitivement')) {

            if ($file.hasClass('checkedFile')) {

                if ($.inArray($filename, selectedFiles) > -1) {
                    selectedFiles.splice($.inArray($filename, selectedFiles), 1);
                }
                $addBtn.html('<i class="fas fa-plus"></i>');
                $file.removeClass('border borderColorPrimary checkedFile');

                $('#inputSelectFiles').val(selectedFiles.length + ' médias');
                $('#textareaSelectedFile').val(selectedFiles.join('|||'));
                $('#saveMediaModalBtn').html(selectedFiles.length + ' médias');

            }

            mediaAjaxRequest({
                deleteDefinitelyImageByName: 'OK',
                filename: $filename
            }).done(function (data) {
                if (data == 'true' || data === true) {
                    $file.fadeOut();
                } else {
                    alert(data);
                }
            });
        }
    });

    $('img.seeDataOnHover').popover({
        html: true,
        trigger: 'hover',
        placement: 'top',
        content: function () {

            if ($(this).data('width') !== undefined && $(this).data('height') !== undefined) {
                return '<div><strong>Nom:</strong> ' + $(this).data('filename') + '<br><strong>Largeur:</strong> ' + $(this).data('width') + 'px<br><strong>Hauteur:</strong> ' + $(this).data('height') + 'px</div>';
            } else if ($(this).data('filename').length > 0) {
                return '<div><strong>Nom:</strong> ' + $(this).data('filename') + '</div>';
            }
        }
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

        $Img.attr('data-toggle', 'popover');
        if ($Img.attr('data-originsrc') !== undefined) {

            img.src = $Img.attr('data-originsrc');
            img.onload = function () {
                $Img.attr('data-width', this.width);
                $Img.attr('data-height', this.height);
            };
        } else {
            $Img.attr('data-filename', $Img.data('filename'));
        }
    });
});