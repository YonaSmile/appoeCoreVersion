<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/main.php');
use App\Category;

$Category = new Category();
$Category->setType('MEDIA');
$allCategory = $Category->showByType();

$listCatgories = extractFromObjToArrForList($allCategory, 'id');
?>
<div class="modal fade" id="libraryModal" tabindex="-1" role="dialog"
     aria-labelledby="mediaLibraryModalTitle" aria-hidden="true" data-inputid="">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 0;">
                <h5 class="modal-title"
                    id="mediaLibraryModalTitle"><?= trans('Choisissez le fichier média'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-1" id="libraryModalContent">
                <div id="mediaContainer">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link sidebarLink active" id="nav-allLibraries-tab" data-bs-toggle="tab"
                               href="#nav-allLibraries"
                               role="tab" aria-controls="nav-allLibraries"
                               aria-selected="true"><?= trans('Les bibliothèques'); ?></a>
                            <a class="nav-item nav-link sidebarLink" id="nav-newFiles-tab" data-bs-toggle="tab"
                               href="#nav-newFiles" role="tab"
                               aria-controls="nav-newFiles"
                               aria-selected="false"><?= trans('Téléchargement des médias'); ?></a>
                        </div>
                    </nav>
                    <div class="tab-content border-top-0 bg-white py-3" id="nav-mediaTabContent"
                         style="border: 1px solid #ccc;">
                        <div class="tab-pane fade show active" id="nav-allLibraries" role="tabpanel"
                             aria-labelledby="nav-home-tab">
                            <div class="container-fluid" id="chooseFileLibrary">
                                <i class="fas fa-circle-notch fa-spin"></i>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-newFiles" role="tabpanel"
                             aria-labelledby="nav-profile-tab">
                            <div class="container-fluid">
                                <form class="row" id="mediaLibraryForm" action="/app/ajax/media.php" method="post"
                                      enctype="multipart/form-data">
                                    <div class="col-12 col-lg-3 my-2">
                                        <?= \App\Form::select('Bibliothèques', 'library', $listCatgories, '', true); ?>
                                    </div>
                                    <div class="col-12 col-lg-6 my-2">
                                        <?= \App\Form::file('Importer des médias', 'inputFile[]', false, 'multiple'); ?>
                                    </div>
                                    <div class="col-12 col-lg-3 my-2 d-flex align-self-end">
                                        <?= \App\Form::target('ADDIMAGES'); ?>
                                        <?= \App\Form::submit('Enregistrer', 'addImageSubmit'); ?>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="my-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/app/lib/template/js/media.js"></script>