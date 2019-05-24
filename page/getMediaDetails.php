<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/main.php');
if (checkAjaxRequest() && !empty($_GET['fileId']) && is_numeric($_GET['fileId'])):

    $Category = new \App\Category();
    $Category->setType('MEDIA');
    $listCatgories = extractFromObjToArrForList($Category->showByType(), 'id');

    $Media = new \App\Media();
    $Media->setId($_GET['fileId']);
    $Media->setLang(APP_LANG);

    if ($Media->show()): ?>
        <div>

            <?php if (isImage(FILE_DIR_PATH . $Media->getName())):
                $imgSize = getimagesize(FILE_DIR_PATH . $Media->getName()); ?>
                <div class="mb-2">
                    <strong>Largeur:</strong> <?= $imgSize[0]; ?>px
                    <strong>|</strong> <strong>Hauteur:</strong> <?= $imgSize[1]; ?>px
                </div>
                <img src="<?= getThumb($Media->getName(), 370); ?>"
                     alt="<?= $Media->getTitle(); ?>"
                     data-originsrc="<?= WEB_DIR_INCLUDE . $Media->getName(); ?>"
                     data-filename="<?= $Media->getName(); ?>"
                     class="img-fluid seeOnOverlay">

            <?php elseif (isAudio(FILE_DIR_PATH . $Media->getName())): ?>
                <audio controls src="<?= WEB_DIR_INCLUDE . $Media->getName(); ?>"></audio>

            <?php elseif (isVideo(FILE_DIR_PATH . $Media->getName())): ?>
                <video controls>
                    <source src="<?= WEB_DIR_INCLUDE . $Media->getName(); ?>"
                            type="<?= mime_content_type(FILE_DIR_PATH . $Media->getName()); ?>">
                </video>

            <?php else: ?>
                <a href="<?= WEB_DIR_INCLUDE . $Media->getName(); ?>" target="_blank">
                    <img src="<?= getImgAccordingExtension(getFileExtension($Media->getName())); ?>"
                         data-filename="<?= $Media->getName(); ?>">
                </a>
            <?php endif; ?>

            <h5 class="my-2" id="mediaTitle"><?= $Media->getTitle(); ?></h5>

            <small title="<?= trans('Copier le lien du média'); ?>">
                <span class="copyLinkOnClick" data-src="<?= WEB_DIR_INCLUDE . $Media->getName(); ?>">
                    <?= WEB_DIR_INCLUDE . $Media->getName(); ?>
                </span>
                <a href="<?= WEB_DIR_INCLUDE . $Media->getName(); ?>" target="_blank"
                   data-file-mime="<?= mime_content_type(FILE_DIR_PATH . $Media->getName()); ?>">
                    <i class="fas fa-external-link-alt mx-2"></i>
                </a>
            </small>

            <form method="post" id="mediaDetailsForm" class="my-2">
                <input type="hidden" name="id" value="<?= $Media->getId(); ?>">
                <div class="mb-2">
                    <?= \App\Form::text('Titre', 'title', 'text', $Media->getTitle(), false, 255, '', '', 'form-control-sm imageTitle upImgForm'); ?>
                </div>
                <div class="mb-2">
                    <?= \App\Form::textarea('Description', 'description', $Media->getDescription(), 1, false, '', 'form-control-sm imageDescription upImgForm'); ?>
                </div>
                <div class="mb-2">
                    <?= \App\Form::text('Lien', 'link', 'url', $Media->getLink(), false, 255, '', '', 'form-control-sm imagelink upImgForm'); ?>
                </div>
                <div class="mb-2">
                    <?= \App\Form::text('Position', 'position', 'text', $Media->getPosition(), false, 5, '', '', 'form-control-sm imagePosition upImgForm'); ?>
                </div>
                <div class="mb-2">
                    <?= \App\Form::select('Bibliothèques', 'typeId', $listCatgories, $Media->getTypeId(), '', '', '', '', 'custom-select-sm imageTypeId upImgForm'); ?>
                </div>
            </form>

            <small id="infosMedia" class="float-right text-success"></small>

            <hr class="mx-5 my-3">
            <button type="button" class="closeMediaDetails btn btn-outline-secondary btn-sm float-left">
                Fermer <i class="fas fa-chevron-right"></i>
            </button>

            <button type="button" class="deleteImage btn btn-outline-danger btn-sm float-right"
                    data-imageid="<?= $Media->getId(); ?>" data-thumbwidth="370">
                <i class="fas fa-times"></i> Supprimer
            </button>
        </div>
    <?php endif; ?>
<?php else: ?>
    <p><?= trans('Ce fichier n\'existe pas'); ?> !</p>
<?php endif; ?>