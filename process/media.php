<?php
if (checkPostAndTokenRequest()) {

    //Clean data
    $_POST = cleanRequest($_POST);

    if (isset($_POST['ADDIMAGES']) && !empty($_POST['library']) && !empty($_FILES)) {
        $Media = new App\Media();
        $Media->setTypeId($_POST['library']);
        $Media->setUploadFiles($_FILES['inputFile']);
        $Media->setUserId($User->getId());

        App\Flash::setMsg(trans('Images téléchargées') . ' : ' . $Media->upload(), 'info');
    }
}