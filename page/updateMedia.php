<?php
require('header.php');
require(WEB_PROCESS_PATH . 'media.php');

$Media = new App\Media();

$Category = new App\Category();
$allLibrary = extractFromObjToSimpleArr($Category->showAll(), 'id', 'name');

$Category->setType('MEDIA');
$listCatgories = extractFromObjToArrForList($Category->showByType(), 'id');
?>
<?= getTitle($Page->getName(), $Page->getSlug()); ?>
    <div class="container-fluid">
        <h2 class="subTitle"><?= trans('Téléchargement des médias'); ?></h2>
        <form class="row" id="galleryForm" action="" method="post" enctype="multipart/form-data">
            <?= getTokenField(); ?>
            <div class="col-12 col-lg-9 my-2">
                <?= App\Form::text('Sélection des médias', 'inputFile[]', 'file', '', true, 800, 'multiple'); ?>
            </div>
            <div class="col-12 col-lg-3 my-2">
                <?= App\Form::select('Bibliothèques', 'library', $listCatgories, '', true); ?>
            </div>
            <div class="col-12">
                <?= App\Form::target('ADDIMAGES'); ?>
                <?= App\Form::submit('Enregistrer', 'addImageSubmit'); ?>
            </div>
        </form>
        <div class="my-4"></div>
    </div>
<?php if ($allLibrary): ?>
    <div class="container-fluid">
        <h2 class="subTitle"><?= trans('Les bibliothèques'); ?></h2>
        <div id="shortAccessBtns" class="mb-4"></div>
        <?php foreach ($allLibrary as $id => $name): ?>
            <?php
            $Media->setTypeId($id);
            $allFiles = $Media->showFiles();
            if ($allFiles): ?>
                <h5 class="libraryName p-3" id="media-<?= $id; ?>"><?= $name; ?></h5>
                <hr class="my-3 mx-5">
                <div class="card-columns">
                    <?php foreach ($allFiles as $file): ?>
                        <div class="card fileContent bg-none border-0">
                            <?php if (isImage(FILE_DIR_PATH . $file->name)): ?>
                                <img src="<?= getThumb($file->name, 370); ?>" alt="<?= $file->description; ?>"
                                     data-originsrc="<?= FILE_DIR_URL . $file->name; ?>"
                                     data-filename="<?= $file->name; ?>"
                                     class="img-fluid seeOnOverlay seeDataOnHover">
                            <?php else: ?>
                                <a href="<?= FILE_DIR_URL . $file->name; ?>" target="_blank">
                                    <img src="<?= getImgAccordingExtension(getFileExtension($file->name)); ?>"
                                         data-filename="<?= $file->name; ?>"
                                         class="seeDataOnHover">
                                </a>
                            <?php endif; ?>
                            <div class="form-group mt-1 mb-0">
                                <small style="font-size: 9px;">
                                    <strong class="fileLink" data-src="<?= FILE_DIR_URL . $file->name; ?>">
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
                                    <input type="url" name="link" class="form-control form-control-sm imagelink"
                                           value="<?= $file->link; ?>"
                                           placeholder="<?= trans('Lien'); ?>">
                                    <input type="tel" name="position" class="form-control form-control-sm imagePosition"
                                           value="<?= $file->position; ?>"
                                           placeholder="<?= trans('Position'); ?>">
                                    <select class="custom-select custom-select-sm imageTypeId" name="typeId">
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
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

    <script>
        $(document).ready(function () {
            $('form#galleryForm').submit(function () {
                $('#loader').fadeIn('fast');
            });

            $.each($('h5.libraryName'), function () {
                var id = $(this).attr('id');
                $('#shortAccessBtns').append('<a class="btn btn-info mr-3 mb-3" href="#' + id + '">' + $(this).text() + '</a>');
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

            $('.deleteImage').on('click', function (event) {
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