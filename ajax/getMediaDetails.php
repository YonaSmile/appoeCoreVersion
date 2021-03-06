<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/main.php');

use App\Category;
use App\Media;

if (checkAjaxRequest() && !empty($_GET['fileId']) && is_numeric($_GET['fileId'])):

    $Category = new Category();
    $Category->setType('MEDIA');
    $listCatgories = extractFromObjToArrForList($Category->showByType(), 'id');

    $Media = new Media();
    $Media->setId($_GET['fileId']);
    $Media->setLang(APP_LANG);

    if ($Media->show()): ?>
        <div class="py-2">
            <div class="col-12">
                <?php if (isImage(FILE_DIR_PATH . $Media->getName())):
                    $imgSize = getimagesize(FILE_DIR_PATH . $Media->getName()); ?>
                    <div class="mb-2">
                        <strong>Largeur:</strong> <?= $imgSize[0]; ?>px
                        <strong>|</strong> <strong>Hauteur:</strong> <?= $imgSize[1]; ?>px
                        <a title="<?= trans('Renommer le fichier'); ?>" class="renameMediaFile float-right"
                           style="cursor: pointer;">
                            <i class="fas fa-wrench"></i>
                        </a>
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
                <small>
                    <span class="copyLinkOnClick" title="<?= trans('Copier le lien du m??dia'); ?>"
                          data-src="<?= WEB_DIR_INCLUDE . $Media->getName(); ?>">
                        <?= $Media->getName(); ?>
                    </span>
                    <a title="<?= trans('Visualiser le fichier dans un nouvel onglet'); ?>"
                       href="<?= WEB_DIR_INCLUDE . $Media->getName(); ?>" target="_blank"
                       data-file-mime="<?= mime_content_type(FILE_DIR_PATH . $Media->getName()); ?>">
                        <i class="fas fa-external-link-alt mx-2"></i>
                    </a>
                </small>
                <form method="post" class="my-2" id="filenameInputForm" style="display:none;">
                    <input type="hidden" name="id" value="<?= $Media->getId(); ?>">
                    <input type="hidden" name="oldName" value="<?= $Media->getName(); ?>">
                    <div class="mb-2">
                        <?= \App\Form::text('Nom du fichier', 'filename', 'text', $Media->getName(), true, 255, '', '', 'form-control-sm'); ?>
                    </div>
                    <div class="mb-2">
                        <?= \App\Form::submit('Enregistrer', 'RENAMEFILENAME'); ?>
                    </div>
                </form>
                <form method="post" id="mediaDetailsForm" class="my-2">
                    <input type="hidden" name="id" value="<?= $Media->getId(); ?>">
                    <input type="hidden" name="imageType" value="<?= $Media->getType(); ?>">
                    <div class="mb-2">
                        <?= \App\Form::text('Titre (texte alternatif)', 'title', 'text', $Media->getTitle(), false, 255, '', '', 'form-control-sm imageTitle upImgForm'); ?>
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
                    <?php if ($Media->getType() === 'MEDIA'): ?>
                        <div class="mb-2">
                            <?= \App\Form::select('Biblioth??ques', 'typeId', $listCatgories, $Media->getTypeId(), true, ' data-old-type="' . $Media->getTypeId() . '" ', '', '', 'custom-select-sm imageTypeId upImgForm'); ?>
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="typeId" class="imageTypeId" value="<?= $Media->getTypeId(); ?>">
                        <div class="mb-2">
                            <?= \App\Form::select('Zone du th??me', 'templatePosition', FILE_TEMPLATE_POSITIONS, getSerializedOptions($Media->getOptions(), 'templatePosition'), '', '', '', '', 'custom-select-sm templatePosition upImgForm'); ?>
                        </div>
                    <?php endif; ?>
                </form>
                <small id="infosMedia" class="float-right text-success"></small>
                <hr class="mx-5 my-3">
                <div class="row">
                    <div class="col-6 text-left">
                        <button type="button" class="closeMediaDetails btn btn-outline-secondary btn-sm">
                            Fermer <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    <div class="col-6 text-right">
                        <button type="button" class="deleteImage btn btn-outline-danger btn-sm"
                                data-imageid="<?= $Media->getId(); ?>" data-thumbwidth="370">
                            <i class="fas fa-times"></i> Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php else: ?>
    <p><?= trans('Ce fichier n\'existe pas'); ?> !</p>
<?php endif; ?>