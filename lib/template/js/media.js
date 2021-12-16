$(document).ready(function () {

    if ($('#allMediaModalContainer').length) {
        $('#allMediaModalContainer').load('/app/ajax/media.php?getAllMedia');
    }

    $(document.body).on('submit', 'form#mediaLibraryForm', function (event) {
        event.preventDefault();
        event.stopPropagation();

        var $form = $(this);
        var $btn = $('button[type="submit"]', $form);

        $btn.html(loaderHtml());

        busyApp(false);

        sendPostFiles($form).done(function (data) {
            if (data) {
                $btn.html(data);
                getMedia('#chooseFileLibrary');
            }
            availableApp();
        });

    });

    $(document.body).on('submit', 'form#galleryForm', function () {
        $('#loader').fadeIn('fast');
    });

    shortAccessLibrary();

    $(document.body).on('click', '.renameMediaFile', function () {

        let $btn = $(this);
        let $filenameForm = $('#filenameInputForm');
        let $detailsForm = $('#mediaDetailsForm');
        $btn.blur();

        if ($filenameForm.is(':visible')) {
            $filenameForm.slideUp('slow', function () {
                $detailsForm.slideDown();
                $btn.removeClass('active');
            });
        } else {
            $detailsForm.slideUp('slow', function () {
                $filenameForm.slideDown();
                $btn.addClass('active');
                $('input#filename').focus();
            });
        }
    });

    $(document.body).on('click', 'a.getMediaDetails', function (event) {
        event.preventDefault();
        event.stopPropagation();

        let $btn = $(this);
        var offCanvas = $('#offCanvas');
        var body = $('div#offCanvasBody', offCanvas);
        let id = $btn.attr('data-file-id');

        openOffCanvas({attr: 'data-file-id="' + id + '"', title: 'Détails du média'});
        body.html('<div class="p-3">' + loaderHtml() + '</div>');
        body.load('/app/ajax/getMediaDetails.php?fileId=' + id);

    });

    $(document.body).on('click', 'a.getFileDetails', function (event) {
        event.preventDefault();
        event.stopPropagation();

        let $btn = $(this);
        var offCanvas = $('#offCanvas');
        var body = $('div#offCanvasBody', offCanvas);
        let filename = $btn.closest('div.card').attr('data-filename');

        openOffCanvas({attr: 'data-filename="' + filename + '"', title: 'Détails du média'});
        body.html('<div class="p-3">' + loaderHtml() + '</div>');
        body.load('/app/ajax/getMediaDetails.php?filename=' + filename);

    });

    $(document.body).on('click', '.listView', function () {

        var $btn = $(this);
        $btn.removeClass('listView').addClass('gridView');
        $btn.html('<i class="fas fa-th"></i>');

        $('.fileFormInput').css({
            transform: 'scale(1)',
            position: 'relative'
        });
    });

    $(document.body).on('click', '.gridView', function () {

        var $btn = $(this);
        $btn.removeClass('gridView').addClass('listView');
        $btn.html('<i class="fas fa-th-list"></i>');

        $('.fileFormInput').css({
            transform: 'scale(0)',
            position: 'absolute'
        });
    });


    $(document.body).on('click', '#shortAccessBtns button', function (event) {
        event.preventDefault();

        var libraryId = $(this).data('library-parent-id');

        if (libraryId !== 'all') {
            $('div.mediaContainer').hide();
            $('div.mediaContainer[data-library-parent-id="' + libraryId + '"]').show();
        } else {
            $('div.mediaContainer').show();
        }

        return false;
    });

    $(document.body).on('click', '.seeMediaCaption', function () {
        let $btn = $(this);
        let $container = $btn.closest('div#offCanvas');
        let $caption = $container.find('.mediaCaption');
        $btn.blur();

        if ($caption.hasClass('show')) {
            $caption.removeClass('show').stop().fadeOut();
            $btn.removeClass('active');
        } else {
            $caption.addClass('show').stop().fadeIn();
            $btn.addClass('active');
        }
    });

    $(document.body).on('submit', 'form#filenameInputForm', function (e) {
        e.preventDefault();

        let $form = $(this);
        let $btn = $form.find('button[type="submit"]');
        let $container = $form.closest('div#offCanvas');

        $btn.html(loaderHtml());
        busyApp();

        let id = $form.find('input[name="id"]').val();
        let oldName = $form.find('input[name="oldName"]').val();
        let newName = $form.find('input[name="filename"]').val();

        $.post(
            '/app/ajax/media.php',
            {
                renameMediaFile: 'OK',
                idImage: id,
                oldName: oldName,
                newName: newName
            },
            function (data) {
                if (data == 'true' || data === true) {
                    $btn.html('Le fichier à été renommé').addClass('btn-success');

                    setTimeout(function () {
                        $('#filenameInputForm').slideUp('slow', function () {
                            $btn.html('Enregistrer').removeClass('btn-success').addClass('btn-info');
                            $('#mediaDetailsForm').slideDown();
                        });
                    }, 600);

                    $form.find('input[name="oldName"]').val(newName);

                    let $img = $('img, audio, video source', $container);
                    let $copyLink = $('.copyLinkOnClick', $container);
                    let $externalLink = $copyLink.next('a');

                    $img.attr('data-filename', newName);
                    $img.attr('data-originsrc', $img.data('originsrc').replace(oldName, newName));
                    $copyLink.attr('data-src', $copyLink.data('src').replace(oldName, newName));
                    $('.mediaCaption > small').html(newName);
                    $externalLink.attr('href', $externalLink.attr('href').replace(oldName, newName));

                    $('a.getMediaDetails[data-file-id="' + id + '"').find('small').html(newName);

                } else {
                    $btn.html(data).addClass('btn-danger');
                }
                availableApp();
            }
        );
    });

    $(document.body).on('input', '.upImgForm', function () {

        busyApp();

        let $info = $('#infosMedia');
        $info.hide().html('');

        let $form = $('form#mediaDetailsForm');

        let idImage = $form.find('input[name="id"]').val();
        let title = $form.find('input.imageTitle').val();
        let description = $form.find('textarea.imageDescription').val();
        let link = $form.find('input.imagelink').val();
        let position = $form.find('input.imagePosition').val();
        let type = $form.find('input[name="imageType"]').val();
        let typeId = $form.find('.imageTypeId').val();

        let options = null;
        let oldTypeId = null;

        if (type === "MEDIA") {
            oldTypeId = $form.find('.imageTypeId').attr('data-old-type');
        } else {
            options = $form.find('select.templatePosition').val();
        }

        let $container = $('div.card[data-file-id="' + idImage + '"]').clone();

        $('#mediaTitle').html(title);

        delay(function () {
            $.post(
                '/app/ajax/media.php',
                {
                    updateDetailsImg: 'OK',
                    idImage: idImage,
                    title: title,
                    description: description,
                    link: link,
                    position: position,
                    typeId: typeId,
                    templatePosition: options
                },
                function (data) {
                    if (data && (data == 'true' || data === true)) {
                        $info.html('Enregistré').show();
                        availableApp();

                        if (oldTypeId && oldTypeId != typeId) {
                            $('div.card[data-file-id="' + idImage + '"]').fadeOut('fast').remove();
                            $('div.mediaContainer[data-library-id="' + typeId + '"] div.card-columns').append($container.hide().fadeIn('fast'));
                            $form.find('.imageTypeId').attr('data-old-type', typeId);
                        }
                    }
                }
            );
        }, 300);
    });

    var selectedAllMediaFile = [];

    $(document.body).on('click', '.selectOptionFile > .successIcon', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var $btn = $(this);
        var $file = $btn.closest('div.card');
        var $filename = $file.data('filename');

        if ($file.hasClass('selected')) {

            if ($.inArray($filename, selectedAllMediaFile) > -1) {
                selectedAllMediaFile.splice($.inArray($filename, selectedAllMediaFile), 1);
            }
            $file.removeClass('selected').blur();

        } else {

            if ($.inArray($filename, selectedAllMediaFile) === -1) {
                selectedAllMediaFile.push($filename);
            }
            $file.addClass('selected');
        }

        let selectedFileCountMsg = selectedAllMediaFile.length > 1
            ? selectedAllMediaFile.length + ' éléments sélectionnés'
            : selectedAllMediaFile.length + ' élément sélectionné';

        if (selectedAllMediaFile.length > 0) {

            let filesNav = $('div#nav-allMedias');
            if (filesNav.find('div#selectedStickyOptions').length === 0) {
                filesNav.prepend('<div id="selectedStickyOptions"><div id="countEls"></div><div id="actionEls" class="btn-group btn-group-sm" role="group" aria-label="Action"></div></div>');
                $('div#selectedStickyOptions > #actionEls').html('<button type="button" class="btn btn-danger deleteSelectedFiles"><i class="far fa-trash-alt"></i></button>');
            }

            $('div#selectedStickyOptions > #countEls').html(selectedFileCountMsg);

        } else {
            $('div#selectedStickyOptions').remove();
        }
    });

    $(document.body).on('click', 'button.deleteSelectedFiles', function (e) {
        e.preventDefault();
        e.stopPropagation();

        let confirmMsg = selectedAllMediaFile.length > 1
            ? 'Vous allez supprimer les ' + selectedAllMediaFile.length + ' éléments sélectionnés'
            : 'Vous allez supprimer l\'élément sélectionné';

        if (confirm(confirmMsg)) {
            busyApp();

            $.post(
                '/app/ajax/media.php',
                {
                    deleteSelectedFiles: 'OK',
                    filenames: selectedAllMediaFile.join('|||')
                },
                function (data) {
                    if (data !== 'false') {

                        let deletedSelected = data.split('|||');
                        let successMsg = deletedSelected.length > 1
                            ? 'Les ' + deletedSelected.length + '/' + selectedAllMediaFile.length + ' éléments ont été supprimés'
                            : (selectedAllMediaFile.length > 1 ? deletedSelected.length + '/' + selectedAllMediaFile.length + ' des éléments ont été supprimés' : 'L\'élément sélectionné à été supprimé');

                        $.each(deletedSelected, function (i, filename) {
                            $('#nav-mediaTabContent .card[data-filename="' + filename + '"]').fadeOut(300, function () {
                                $(this).remove();
                            });
                        });

                        notification(successMsg);
                    } else {
                        notification('Suppression échouée', 'danger');
                    }

                    $('.selectOptionFile.selected').addClass('border-danger').removeClass('selected');
                    selectedAllMediaFile = [];
                    $('div#selectedStickyOptions').hide().remove();
                    availableApp();
                }
            );
        }
    });

    $(document.body).off('click', 'button.deleteImage').on('click', 'button.deleteImage', function (event) {
        event.preventDefault();
        event.stopPropagation();

        if (confirm('Vous allez supprimer cette image')) {
            busyApp();
            var $btn = $(this);
            var idImage = $btn.data('imageid');
            var filenameImg = $btn.data('filename');
            var thumbWidth = $btn.data('thumbwidth');

            $.post(
                '/app/ajax/media.php',
                {
                    deleteImage: 'OK',
                    idImage: idImage,
                    thumbWidth: thumbWidth
                },
                function (data) {
                    if (data === 'true' || data === true) {
                        closeOffCanvas();
                        $('.card[data-file-id="' + idImage + '"], .card[data-filename="' + filenameImg + '"]').fadeOut().remove();
                        availableApp();
                    }
                }
            );
        }
    });

    $(document.body).on('click', '.copyLinkOnClick', function (e) {
        e.preventDefault();
        copyToClipboard($(this).data('src'));
        $(this).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
    });
});