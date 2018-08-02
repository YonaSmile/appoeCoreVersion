<?php
require_once('../main.php');
if (checkAjaxRequest()) {

    if (getUserIdSession()) {

        $_POST = cleanRequest($_POST);

        if (isset($_POST['updateDetailsImg']) && !empty($_POST['idImage'])
            && isset($_POST['description']) && isset($_POST['link']) && isset($_POST['position'])
            && !empty($_POST['typeId']) && is_numeric($_POST['typeId'])) {

            $Media = new App\Media();
            $Media->setId($_POST['idImage']);
            if ($Media->show()) {
                $Media->setDescription($_POST['description']);
                $Media->setLink($_POST['link']);
                $Media->setPosition($_POST['position']);
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
        }

        if (isset($_POST['deleteImage']) && !empty($_POST['idImage'])) {

            $Media = new App\Media();
            $Media->setId($_POST['idImage']);
            if ($Media->show()) {
                if ($Media->delete()) {
                    echo 'true';
                }
            }
        }
    }
}