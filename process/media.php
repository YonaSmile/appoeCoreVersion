<?php
if (checkPostAndTokenRequest()) {

    //Clean data
    $_POST = cleanRequest($_POST);

    if (isset($_POST['ADDIMAGES']) && !empty($_POST['library']) && !empty($_FILES)) {
        $Media = new App\Media();
        $Media->setTypeId($_POST['library']);
        $Media->setUploadFiles($_FILES['inputFile']);
        $Media->setUserId($USER->getId());

        $files = $Media->upload();
        App\Flash::setMsg(trans('Fichiers téléchargés') . ' : ' . $files['countUpload'], 'info');
    }
}