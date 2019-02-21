<?php
require_once('../main.php');
if (checkAjaxRequest()) {

    if (getUserIdSession()) {

        $_POST = cleanRequest($_POST);

        if (isset($_POST['updateDetailsImg']) && !empty($_POST['idImage'])
            && !empty($_POST['typeId']) && is_numeric($_POST['typeId'])) {

            $Media = new \App\Media();
            $Media->setId($_POST['idImage']);
            if ($Media->show()) {

                if (!empty($_POST['description'])) {
                    $Media->setDescription($_POST['description']);
                }

                if (!empty($_POST['link'])) {
                    $Media->setLink($_POST['link']);
                }
                if (!empty($_POST['position'])) {
                    $Media->setPosition($_POST['position']);
                }

                $Media->setTypeId($_POST['typeId']);
                $Media->setUserId(getUserIdSession());

                if (!empty($_POST['templatePosition'])) {

                    $mediaOptions = getSerializedOptions($Media->getOptions());

                    if (is_array($mediaOptions)) {
                        $Media->setOptions(serialize(array_replace_recursive(
                            $mediaOptions,
                            array('templatePosition' => $_POST['templatePosition'])
                        )));
                    } else {
                        $Media->setOptions(serialize(array('templatePosition' => $_POST['templatePosition'])));
                    }
                }

                if ($Media->update()) {
                    echo 'true';
                }
            }
            exit();
        }

        if (isset($_POST['deleteImage']) && !empty($_POST['idImage'])) {

            $Media = new \App\Media();
            $Media->setId($_POST['idImage']);
            if ($Media->show()) {

                if (!empty($_POST['thumbWidth'])) {
                    deleteThumb($Media->getName(), $_POST['thumbWidth']);
                }

                if ($Media->delete()) {
                    echo 'true';
                }
            }
            exit();
        }

        if (isset($_REQUEST['getAllMedia'])) {

            $html = '<div class="card-columns">';
            $includeFiles = getFilesFromDir(FILE_DIR_PATH, true);

            foreach ($includeFiles as $key => $includeFile) {

                $html .= '<div class="card fileContent allMediaCard" data-filename="' . $includeFile . '">';

                if (isImage(FILE_DIR_PATH . $includeFile)) {
                    $html .= '<img src="' . WEB_DIR_INCLUDE . $includeFile . '" class="img-fluid seeOnOverlay seeDataOnHover" data-originsrc="' . WEB_DIR_INCLUDE . $includeFile . '">';
                } else {
                    $html .= '<span class="contentOnHover">' . $includeFile . '</span><a href="' . WEB_DIR_INCLUDE . $includeFile . '" target="_blank"><img src = "' . getImgAccordingExtension(getFileExtension($includeFile)) . '" class="img-fluid seeDataOnHover"></a>';
                }

                $html .= '<button class="btn btn-sm littleBtn addLittleBtn selectParentOnClick bgColorPrimary"><i class="fas fa-plus"></i></button>';
                $html .= '<button type="button" class="deleteDefinitelyImageByName btn btn-sm btn-danger littleBtn deleteLittleBtn" data-imagename="' . $includeFile . '"> <i class="fas fa-times"></i></button></div>';
            }

            $html .= '</div>';
            echo $html;
            exit();
        }

        if (!empty($_POST['deleteDefinitelyImageByName']) && !empty($_POST['filename'])) {
            $File = new \App\File();
            $File->setName($_POST['filename']);
            $fileDeleted = $File->deleteFileByPath();

            if (true !== $fileDeleted) {

                if (false === $fileDeleted) {
                    echo trans('Un problème est survenue lors de la suppression du fichier');
                } else {
                    echo $fileDeleted;
                }

            } else {

                if ($File->deleteFileByName()) {
                    echo json_encode(true);
                } else {
                    echo trans('Le fichier a été supprimé mais un problème est survenue lors de la suppression du fichier dans la base de données');
                }
            }
            exit();
        }
    }
}