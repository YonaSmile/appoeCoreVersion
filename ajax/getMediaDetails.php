<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/main.php');

use App\Category;
use App\Form;
use App\Media;

if (checkAjaxRequest()):

    if (isset($_GET['filename']) && !empty(trim($_GET['filename'])) && is_string($_GET['filename'])
        && false !== strpos($_GET['filename'], '.') && file_exists(FILE_DIR_PATH . $_GET['filename'])):
        $file = $_GET['filename'];
        $filePath = FILE_DIR_PATH . $_GET['filename'];
        $fileUrl = WEB_DIR_INCLUDE . $file; ?>
        <div id="mediaEdition">
            <div class="col-12 p-0">

                <?php $fileSize = getSizeName(filesize($filePath));
                if (isImage($filePath)):
                    $fileDimensions = getimagesize($filePath); ?>
                    <div class="mediaItem">
                        <img src="<?= getThumb($file, 400); ?>"
                             alt="Image"
                             data-originsrc="<?= $fileUrl; ?>"
                             data-filename="<?= $file; ?>"
                             class="img-fluid seeOnOverlay">
                        <div class="mediaCaption">
                            <small><?= $file; ?></small><br>
                            <strong>L:</strong> <?= $fileDimensions[0]; ?>px
                            <strong>| H:</strong> <?= $fileDimensions[1]; ?>px
                            <strong>| P:</strong> <?= $fileSize; ?>
                        </div>
                    </div>

                <?php elseif (isAudio($filePath)): ?>
                    <div class="mediaItem">
                        <audio controls src="<?= $fileUrl; ?>"
                               data-originsrc="<?= $fileUrl; ?>"></audio>
                        <div class="mediaCaption">
                            <small><?= $file; ?></small><br>
                            <strong>Poids:</strong> <?= $fileSize; ?>
                        </div>
                    </div>
                <?php elseif (isVideo($filePath)): ?>
                    <div class="mediaItem">
                        <video controls class="d-block">
                            <source src="<?= $fileUrl; ?>"
                                    data-originsrc="<?= $fileUrl; ?>"
                                    type="<?= mime_content_type($filePath); ?>">
                        </video>
                        <div class="mediaCaption">
                            <small><?= $file; ?></small><br>
                            <strong>Poids:</strong> <?= $fileSize; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="mediaItem">
                        <a href="<?= $fileUrl; ?>" target="_blank">
                            <img src="<?= getImgAccordingExtension(getFileExtension($file)); ?>"
                                 data-originsrc="<?= $fileUrl; ?>"
                                 data-filename="<?= $file; ?>" alt="Fichier">
                        </a>
                        <div class="mediaCaption">
                            <small><?= $file; ?></small><br>
                            <strong>Poids:</strong> <?= $fileSize; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="row m-0">
                    <button type="button" class="btn btn-secondary seeMediaCaption col"
                            title="<?= trans('Voir les informations de l\'image'); ?>">
                        <i class="fas fa-info"></i>
                    </button>
                    <button type="button" class="btn btn-secondary col copyLinkOnClick"
                            data-src="<?= $fileUrl; ?>"
                            title="<?= trans('Copier le lien du média'); ?>">
                        <i class="far fa-copy"></i>
                    </button>
                    <a title="<?= trans('Visualiser le fichier dans un nouvel onglet'); ?>"
                       href="<?= $fileUrl; ?>" target="_blank"
                       data-file-mime="<?= mime_content_type($filePath); ?>" class="btn btn-secondary col">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php exit();
    endif;

    if (isset($_GET['fileId']) && !empty(trim($_GET['fileId'])) && is_numeric($_GET['fileId'])):

        $Category = new Category();
        $Category->setType('MEDIA');
        $listCatgories = extractFromObjToArrForList($Category->showByType(), 'id');

        $Media = new Media();
        $Media->setId($_GET['fileId']);
        $Media->setLang(APP_LANG);

        if ($Media->show()): ?>
            <div id="mediaEdition">
                <div class="col-12 p-0">
                    <?php
                    $file = FILE_DIR_PATH . $Media->getName();
                    $fileSize = getSizeName(filesize($file));
                    if (isImage($file)):
                        $fileDimensions = getimagesize($file); ?>
                        <div class="mediaItem">
                            <img src="<?= getThumb($Media->getName(), 400); ?>"
                                 alt="<?= $Media->getTitle(); ?>"
                                 data-originsrc="<?= WEB_DIR_INCLUDE . $Media->getName(); ?>"
                                 data-filename="<?= $Media->getName(); ?>"
                                 class="img-fluid seeOnOverlay">
                            <div class="mediaCaption">
                                <small><?= $Media->getName(); ?></small><br>
                                <strong>L:</strong> <?= $fileDimensions[0]; ?>px
                                <strong>| H:</strong> <?= $fileDimensions[1]; ?>px
                                <strong>| P:</strong> <?= $fileSize; ?>
                            </div>
                        </div>

                    <?php elseif (isAudio($file)): ?>
                        <div class="mediaItem">
                            <audio controls src="<?= WEB_DIR_INCLUDE . $Media->getName(); ?>"
                                   data-originsrc="<?= WEB_DIR_INCLUDE . $Media->getName(); ?>"></audio>
                            <div class="mediaCaption">
                                <small><?= $Media->getName(); ?></small><br>
                                <strong>Poids:</strong> <?= $fileSize; ?>
                            </div>
                        </div>
                    <?php elseif (isVideo($file)): ?>
                        <div class="mediaItem">
                            <video controls class="d-block">
                                <source src="<?= WEB_DIR_INCLUDE . $Media->getName(); ?>"
                                        data-originsrc="<?= WEB_DIR_INCLUDE . $Media->getName(); ?>"
                                        type="<?= mime_content_type($file); ?>">
                            </video>
                            <div class="mediaCaption">
                                <small><?= $Media->getName(); ?></small><br>
                                <strong>Poids:</strong> <?= $fileSize; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="mediaItem">
                            <a href="<?= WEB_DIR_INCLUDE . $Media->getName(); ?>" target="_blank">
                                <img src="<?= getImgAccordingExtension(getFileExtension($Media->getName())); ?>"
                                     data-originsrc="<?= WEB_DIR_INCLUDE . $Media->getName(); ?>"
                                     data-filename="<?= $Media->getName(); ?>" alt="<?= $Media->getTitle(); ?>">
                            </a>
                            <div class="mediaCaption">
                                <small><?= $Media->getName(); ?></small><br>
                                <strong>Poids:</strong> <?= $fileSize; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="row m-0">
                        <button type="button" class="btn btn-secondary seeMediaCaption col"
                                title="<?= trans('Voir les informations de l\'image'); ?>">
                            <i class="fas fa-info"></i>
                        </button>
                        <button type="button" class="btn btn-secondary col copyLinkOnClick"
                                data-src="<?= WEB_DIR_INCLUDE . $Media->getName(); ?>"
                                title="<?= trans('Copier le lien du média'); ?>">
                            <i class="far fa-copy"></i>
                        </button>
                        <a title="<?= trans('Visualiser le fichier dans un nouvel onglet'); ?>"
                           href="<?= WEB_DIR_INCLUDE . $Media->getName(); ?>" target="_blank"
                           data-file-mime="<?= mime_content_type($file); ?>" class="btn btn-secondary col">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                        <button type="button" class="btn btn-secondary renameMediaFile col"
                                title="<?= trans('Renommer le fichier'); ?>">
                            <i class="fas fa-wrench"></i>
                        </button>
                        <button type="button" class="btn btn-secondary deleteImage col"
                                title="<?= trans('Supprimer le fichier'); ?>" data-filename="<?= $Media->getName(); ?>"
                                data-imageid="<?= $Media->getId(); ?>" data-thumbwidth="400">
                            <i class="fas fa-times text-danger"></i>
                        </button>
                    </div>
                </div>
                <div class="col-12">
                    <h5 class="p-2" id="mediaTitle"><?= $Media->getTitle(); ?></h5>
                    <form method="post" class="my-2" id="filenameInputForm" style="display:none;">
                        <input type="hidden" name="id" value="<?= $Media->getId(); ?>">
                        <input type="hidden" name="oldName" value="<?= $Media->getName(); ?>">
                        <?= Form::text('Nom du fichier', 'filename', 'text', $Media->getName(), true, 255, '', '', 'form-control-sm'); ?>
                        <?= Form::submit('Enregistrer', 'RENAMEFILENAME'); ?>
                    </form>
                    <form method="post" id="mediaDetailsForm" class="my-2">
                        <input type="hidden" name="id" value="<?= $Media->getId(); ?>">
                        <input type="hidden" name="imageType" value="<?= $Media->getType(); ?>">
                        <?= Form::text('Titre (texte alternatif)', 'title', 'text', $Media->getTitle(), false, 255, '', '', 'form-control-sm imageTitle upImgForm'); ?>
                        <?= Form::textarea('Description', 'description', $Media->getDescription(), 2, false, '', 'form-control-sm imageDescription upImgForm'); ?>
                        <?= Form::text('Lien', 'link', 'url', $Media->getLink(), false, 255, '', '', 'form-control-sm imagelink upImgForm'); ?>
                        <?= Form::text('Position', 'position', 'text', $Media->getPosition(), false, 5, '', '', 'form-control-sm imagePosition upImgForm'); ?>
                        <?php if ($Media->getType() === 'MEDIA'): ?>
                            <?= Form::select('Bibliothèques', 'typeId', $listCatgories, $Media->getTypeId(), true, ' data-old-type="' . $Media->getTypeId() . '" ', '', '', 'custom-select-sm imageTypeId upImgForm'); ?>
                        <?php else: ?>
                            <input type="hidden" name="typeId" class="imageTypeId" value="<?= $Media->getTypeId(); ?>">
                            <?= Form::select('Zone du thème', 'templatePosition', FILE_TEMPLATE_POSITIONS, getSerializedOptions($Media->getOptions(), 'templatePosition'), '', '', '', '', 'custom-select-sm templatePosition upImgForm'); ?>
                        <?php endif; ?>
                    </form>
                    <small id="infosMedia" class="float-end text-success"></small>
                </div>
            </div>
        <?php endif;
    endif;
else: ?>
    <p><?= trans('Ce fichier n\'existe pas'); ?> !</p>
<?php endif; ?>