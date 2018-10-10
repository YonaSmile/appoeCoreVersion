<?php
require('header.php');
require(WEB_PROCESS_PATH . 'media.php');

$Media = new \App\Media();

$Category = new \App\Category();
$allLibrary = extractFromObjToSimpleArr($Category->showAll(), 'id', 'name');

$Category->setType('MEDIA');
$listCatgories = extractFromObjToArrForList($Category->showByType(), 'id');
?>
<?= getTitle($Page->getName(), $Page->getSlug()); ?>
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-allLibraries-tab" data-toggle="tab" href="#nav-allLibraries"
               role="tab" aria-controls="nav-allLibraries" aria-selected="true"><?= trans('Les bibliothèques'); ?></a>
            <a class="nav-item nav-link" id="nav-newFiles-tab" data-toggle="tab" href="#nav-newFiles" role="tab"
               aria-controls="nav-newFiles" aria-selected="false"><?= trans('Téléchargement des médias'); ?></a>
        </div>
    </nav>
    <div class="tab-content border-top-0 bg-white py-3" id="nav-mediaTabContent">
        <div class="tab-pane fade show active" id="nav-allLibraries" role="tabpanel" aria-labelledby="nav-home-tab">
            <?php if ($allLibrary): ?>
                <div class="container-fluid">
                    <div id="shortAccessBtns" class="mb-4 float-right">
                        <button type="button" class="btn btn-sm btn-secondary"
                                data-libraryid="all"><?= trans('Tous'); ?></button>
                    </div>
                    <?php foreach ($allLibrary as $id => $name): ?>
                        <?php
                        $Media->setTypeId($id);
                        $allFiles = $Media->showFiles();
                        if ($allFiles): ?>
                            <div class="mediaContainer" data-libraryid="media-<?= $id; ?>">
                                <h5 class="libraryName p-3" id="media-<?= $id; ?>"><?= $name; ?></h5>
                                <hr class="my-3 mx-5">
                                <div class="card-columns">
                                    <?php foreach ($allFiles as $file): ?>
                                        <div class="card fileContent bg-none border-0">
                                            <?php if (isImage(FILE_DIR_PATH . $file->name)): ?>
                                                <img src="<?= getThumb($file->name, 370); ?>"
                                                     alt="<?= $file->description; ?>"
                                                     data-originsrc="<?= WEB_DIR_INCLUDE . $file->name; ?>"
                                                     data-filename="<?= $file->name; ?>"
                                                     class="img-fluid seeOnOverlay seeDataOnHover">
                                            <?php else: ?>
                                                <a href="<?= WEB_DIR_INCLUDE . $file->name; ?>" target="_blank">
                                                    <img src="<?= getImgAccordingExtension(getFileExtension($file->name)); ?>"
                                                         data-filename="<?= $file->name; ?>"
                                                         class="seeDataOnHover">
                                                </a>
                                            <?php endif; ?>
                                            <div class="form-group mt-1 mb-0">
                                                <small style="font-size: 9px;">
                                                    <strong class="fileLink"
                                                            data-src="<?= WEB_DIR_INCLUDE . $file->name; ?>">
                                                        <button class="btn btn-sm btn-outline-info btn-block copyLinkOnClick">
                                                            <?= trans('Copier le lien du média'); ?>
                                                        </button>
                                                    </strong>
                                                </small>
                                                <form method="post" data-imageid="<?= $file->id; ?>">
                                                    <input type="text" name="description"
                                                           class="form-control form-control-sm imageDescription"
                                                           value="<?= $file->description; ?>"
                                                           placeholder="<?= trans('Description'); ?>">
                                                    <input type="url" name="link"
                                                           class="form-control form-control-sm imagelink"
                                                           value="<?= $file->link; ?>"
                                                           placeholder="<?= trans('Lien'); ?>">
                                                    <input type="tel" name="position"
                                                           class="form-control form-control-sm imagePosition"
                                                           value="<?= $file->position; ?>"
                                                           placeholder="<?= trans('Position'); ?>">
                                                    <select class="custom-select custom-select-sm imageTypeId"
                                                            name="typeId">
                                                        <?php foreach ($listCatgories as $typeId => $name): ?>
                                                            <option value="<?= $typeId; ?>" <?= $typeId == $file->typeId ? 'selected' : ''; ?>><?= $name; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <small class="infosMedia"></small>
                                                </form>
                                            </div>
                                            <button type="button" class="deleteImage btn btn-danger btn-sm"
                                                    style="position: absolute; top: 0; right: 0;z-index: 10"
                                                    data-imageid="<?= $file->id; ?>">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="my-3"></div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="tab-pane fade" id="nav-newFiles" role="tabpanel" aria-labelledby="nav-profile-tab">
            <div class="container-fluid">
                <form class="row" id="galleryForm" action="" method="post" enctype="multipart/form-data">
                    <?= getTokenField(); ?>
                    <div class="col-12 col-lg-6 my-2">
                        <?= \App\Form::text('Importer des médias', 'inputFile[]', 'file', '', false, 800, 'multiple'); ?>
                    </div>
                    <div class="col-12 col-lg-3 my-2">
                        <textarea name="textareaSelectedFile" id="textareaSelectedFile" class="d-none"></textarea>
                        <?= \App\Form::text('Choisissez des médias', 'inputSelectFiles', 'text', '0 fichiers', false, 300, 'readonly data-toggle="modal" data-target="#allMediasModal"'); ?>
                    </div>
                    <div class="col-12 col-lg-3 my-2">
                        <?= \App\Form::select('Bibliothèques', 'library', $listCatgories, '', true); ?>
                    </div>
                    <div class="col-12">
                        <?= \App\Form::target('ADDIMAGES'); ?>
                        <?= \App\Form::submit('Enregistrer', 'addImageSubmit'); ?>
                    </div>
                </form>
            </div>
        </div>
        <div class="my-4"></div>
    </div>

    <div class="modal fade" id="allMediasModal" tabindex="-1" role="dialog" aria-labelledby="allMediasModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="allMediasModalLabel"><?= trans('Tous les médias'); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="allMediaModalContainer"></div>
                <div class="modal-footer">
                    <button type="button" id="closeAllMediaModalBtn" class="btn btn-secondary" data-dismiss="modal">
                        <?= trans('Fermer et annuler la sélection'); ?></button>
                    <button type="button" id="saveMediaModalBtn" class="btn btn-info" data-dismiss="modal">
                        0 <?= trans('médias'); ?></button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {

            $('#allMediaModalContainer').load('/app/ajax/media.php?getAllMedia');

            $('img.seeDataOnHover').popover({
                html: true,
                trigger: 'hover',
                placement: 'top',
                content: function () {
                    return '<div><strong>Largeur:</strong> ' + $(this).data('width') + 'px<br><strong>Hauteur:</strong> ' + $(this).data('height') + 'px</div>';
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

            $('input.imageDescription, input.imagelink, input.imagePosition, select.imageTypeId ').on('keyup change', function () {
                busyApp();
                $('small.infosMedia').hide().html('');
                var $input = $(this);
                var $form = $input.parent('form');
                var idImage = $form.data('imageid');
                var description = $form.children('input.imageDescription').val();
                var link = $form.children('input.imagelink').val();
                var position = $form.children('input.imagePosition').val();
                var typeId = $form.children('select.imageTypeId').val();
                var $info = $form.children('small.infosMedia');

                $.post(
                    '<?= WEB_DIR; ?>app/ajax/media.php',
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
                            $info.html('<?= trans('Enregistré'); ?>').show();
                            availableApp();
                        }
                    }
                )
            });

            $('button.deleteImage').on('click', function (event) {
                event.preventDefault();
                event.stopPropagation();

                if (confirm('<?= trans('Vous allez supprimer cette image'); ?>')) {
                    busyApp();
                    var $btn = $(this);
                    var idImage = $btn.data('imageid');

                    $.post(
                        '<?= WEB_DIR; ?>app/ajax/media.php',
                        {
                            deleteImage: 'OK',
                            idImage: idImage
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

            var textDefaultCopyFile = '<?= trans('Copier le lien du média'); ?>';
            $('.copyLinkOnClick').on('click', function (e) {
                e.preventDefault();
                $('.copyLinkOnClick').text(textDefaultCopyFile);
                copyToClipboard($(this).parent().data('src'));
                $(this).text('<?= trans('copié'); ?>');
            });
        });
    </script>
<?php require('footer.php'); ?>