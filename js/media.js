$(document).ready(function () {
    $('#allMediaModalContainer').load('/app/ajax/media.php?getAllMedia');

    $('img.seeDataOnHover').popover({
        html: true,
        trigger: 'hover',
        placement: 'top',
        content: function () {

            if ($(this).data('width') !== undefined && $(this).data('height') !== undefined) {
                return '<div><strong>Largeur:</strong> ' + $(this).data('width') + 'px<br><strong>Hauteur:</strong> ' + $(this).data('height') + 'px</div>';
            } else if ($(this).data('filename').length > 0) {
                return '<div><strong>' + $(this).data('filename') + '</strong></div>';
            }
        }
    });

    $('form#galleryForm').submit(function () {
        $('#loader').fadeIn('fast');
    });

    $.each($('h5.libraryName'), function () {
        var id = $(this).attr('id');
        $('#shortAccessBtns').append('<button type="button" class="btn btn-sm btn-secondary" data-libraryid="' + id + '">' + $(this).text() + '</button>');
    });

    $('#shortAccessBtns button').on('click', function (event) {
        event.preventDefault();

        var libraryId = $(this).data('libraryid');

        if (libraryId !== 'all') {
            $('div.mediaContainer').hide();
            $('div.mediaContainer[data-libraryid="' + libraryId + '"]').show();
        } else {
            $('div.mediaContainer').show();
        }

        return false;
    });

    var delay = (function () {
        var timer = 0;
        return function (callback, ms) {
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();

    $('input.imageDescription, input.imagelink, input.imagePosition, select.imageTypeId ').bind('keyup change blur', function () {

        $('small.infosMedia').hide().html('');
        busyApp();
        var $input = $(this);
        var $form = $input.closest('form');
        var idImage = $form.data('imageid');
        var description = $form.find('input.imageDescription').val();
        var link = $form.find('input.imagelink').val();
        var position = $form.find('input.imagePosition').val();
        var typeId = $form.find('select.imageTypeId').val();
        var $info = $form.find('small.infosMedia');

        delay(function () {
            $.post(
                '/app/ajax/media.php',
                {
                    updateDetailsImg: 'OK',
                    idImage: idImage,
                    description: description,
                    link: link,
                    position: position,
                    typeId: typeId
                },
                function (data) {
                    if (data && (data == 'true' || data === true)) {
                        $info.html('Enregistré').show();
                        availableApp();
                    }
                }
            )
        }, 200);
    });

    $('button.deleteImage').on('click', function (event) {
        event.preventDefault();
        event.stopPropagation();

        if (confirm('Vous allez supprimer cette image')) {
            busyApp();
            var $btn = $(this);
            var idImage = $btn.data('imageid');
            var thumbWidth = $btn.data('thumbwidth');

            $.post(
                '/app/ajax/media.php',
                {
                    deleteImage: 'OK',
                    idImage: idImage,
                    thumbWidth: thumbWidth
                },
                function (data) {
                    if (data && (data == 'true' || data === true)) {
                        $btn.parent('div').fadeOut('fast');
                        availableApp();
                    }
                }
            )
        }
    });

    var textDefaultCopyFile = 'Copier le lien du média';
    $('.copyLinkOnClick').on('click', function (e) {
        e.preventDefault();
        $('.copyLinkOnClick').text(textDefaultCopyFile);
        copyToClipboard($(this).parent().data('src'));
        $(this).text('copié');
    });
});