<?php
require('header.php');
require(WEB_PROCESS_PATH . 'media.php');

$Media = new App\Media();
$Category = new App\Category();

$Category->setType('MEDIA');
$allCategories = $Category->showByType();

$listCatgories = extractFromObjToArrForList($Category->showByType(), 'id');

$allLibrary = extractFromObjToSimpleArr($allCategories, 'id', 'name');
?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1 class="display-4 bigTitle"><?= trans('Média'); ?></h1>
            </div>
        </div>
        <hr class="my-4">
    </div>

    <div class="container-fluid">
        <form class="row" id="galleryForm" action="" method="post" enctype="multipart/form-data">
            <?= getTokenField(); ?>
            <div class="col-12 col-lg-9">
                <?= App\Form::text(trans('Télécharger des images'), 'inputFile[]', 'file', '', true, 800, 'multiple'); ?>
            </div>
            <div class="col-12 col-lg-3">
                <?= App\Form::select(trans('Bibliothèques'), 'library', $listCatgories, '', true); ?>
            </div>
            <div class="col-12">
                <?= App\Form::submit(trans('Enregistrer'), 'ADDIMAGES'); ?>
            </div>
        </form>
        <div class="my-4"></div>
    </div>
<?php if ($allLibrary): ?>
    <div class="container-fluid">
        <div id="shortAccessBtns" class="mb-4"></div>
        <?php foreach ($allLibrary as $id => $name): ?>
            <?php
            $Media->setTypeId($id);
            $allFiles = $Media->showFiles();
            if ($allFiles): ?>
                <h3 class="libraryName p-3" id="media-<?= $id; ?>"><?= $name; ?></h3>
                <hr class="my-3 mx-5">
                <div class="card-columns">
                    <?php foreach ($allFiles as $file): ?>
                        <div class="card fileContent bg-none border-0">
                            <img src="<?= FILE_DIR_URL . $file->name; ?>" alt="<?= $file->description; ?>"
                                 class="img-fluid seeOnOverlay">
                            <div class="form-group mt-1 mb-0">
                                <form method="post" data-imageid="<?= $file->id; ?>">
                                    <input type="text" name="description" class="form-control form-control-sm imageDescription"
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
                                    style="position: absolute; top: 0; right: 0;" data-imageid="<?= $file->id; ?>">
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

            $.each($('h3.libraryName'), function () {
                var id = $(this).attr('id');
                $('#shortAccessBtns').append('<a class="btn btn-info mr-3 mb-3" href="#' + id + '">' + $(this).text() + '</a>');
            });

            $('input.imageDescription, input.imagelink, input.imagePosition, select.imageTypeId ').on('keyup change', function () {

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
                        }
                    }
                )
            });

            $('.deleteImage').on('click', function (event) {
                event.preventDefault();
                event.stopPropagation();

                if (confirm('<?= trans('Vous allez supprimer cette image'); ?>')) {
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
                            }
                        }
                    )
                }
            });

        });
    </script>
<?php require('footer.php'); ?>