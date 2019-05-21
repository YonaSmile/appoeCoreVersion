<?php
require('header.php');
require(WEB_PROCESS_PATH . 'media.php');

$Media = new \App\Media();

$Category = new \App\Category();
$allLibrary = extractFromObjToSimpleArr($Category->showAll(), 'id', 'name');

$Category->setType('MEDIA');
$listCatgories = extractFromObjToArrForList($Category->showByType(), 'id');
echo getTitle($Page->getName(), $Page->getSlug()); ?>
    <div id="mediaContainer">
        <nav>
            <!--<div class="float-right">
                <button type="button" role="button" class="btn btn-sm listView">
                    <i class="fas fa-th-list"></i>
                </button>
            </div>-->
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-allLibraries-tab" data-toggle="tab"
                   href="#nav-allLibraries"
                   role="tab" aria-controls="nav-allLibraries"
                   aria-selected="true"><?= trans('Les bibliothèques'); ?></a>
                <a class="nav-item nav-link" id="nav-newFiles-tab" data-toggle="tab" href="#nav-newFiles" role="tab"
                   aria-controls="nav-newFiles" aria-selected="false"><?= trans('Téléchargement des médias'); ?></a>
            </div>
        </nav>
        <div class="tab-content border border-top-0 bg-white py-3" id="nav-mediaTabContent">
            <div class="tab-pane fade show active" id="nav-allLibraries" role="tabpanel"
                 aria-labelledby="nav-home-tab">
                <?php if ($allLibrary): ?>
                    <div class="container-fluid">
                        <div id="shortAccessBtns" class="mb-4 float-right">
                            <button type="button" class="btn btn-sm btn-secondary"
                                    data-libraryid="all"><?= trans('Tous'); ?></button>
                        </div>
                        <?php foreach ($allLibrary as $id => $name):
                            $Media->setTypeId($id);
                            $allFiles = $Media->showFiles();
                            if ($allFiles): ?>
                                <div class="mediaContainer" data-libraryid="media-<?= $id; ?>">
                                    <h5 class="libraryName p-3" id="media-<?= $id; ?>"><?= $name; ?></h5>
                                    <hr class="my-3 mx-5">
                                    <div class="card-columns">
                                        <?php foreach ($allFiles as $file): ?>
                                            <div class="card fileContent bg-none border-0"
                                                 data-file-id="<?= $file->id; ?>">
                                                <a href="#" class="getMediaDetails"
                                                   data-file-id="<?= $file->id; ?>">
                                                    <?php if (isImage(FILE_DIR_PATH . $file->name)): ?>
                                                        <img src="<?= getThumb($file->name, 370); ?>"
                                                             class="img-fluid">
                                                    <?php else: ?>
                                                        <img src="<?= getImgAccordingExtension(getFileExtension($file->name)); ?>">
                                                    <?php endif; ?>
                                                </a>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="my-3"></div>
                                </div>
                            <?php endif;
                        endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="tab-pane fade" id="nav-newFiles" role="tabpanel" aria-labelledby="nav-profile-tab">
                <div class="container-fluid">
                    <form class="row" id="galleryForm" action="" method="post" enctype="multipart/form-data">
                        <?= getTokenField(); ?>
                        <div class="col-12 col-lg-6 my-2">
                            <?= \App\Form::file('Importer des médias', 'inputFile[]', false, 'multiple'); ?>
                        </div>
                        <div class="col-12 col-lg-3 my-2">
                                <textarea name="textareaSelectedFile" id="textareaSelectedFile"
                                          class="d-none"></textarea>
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
        <div id="mediaDetails" data-file-id="">Aucun fichier sélectionné</div>
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

        <script type="text/javascript" src="/app/lib/template/js/media.js"></script>
    </div>
<?php require('footer.php'); ?>