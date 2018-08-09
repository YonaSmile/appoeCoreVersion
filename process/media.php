<?php
if (checkPostAndTokenRequest()) {

    //Clean data
    $_POST = cleanRequest($_POST);

    if (isset($_POST['ADDIMAGES']) && !empty($_POST['library']) && isset($_POST['textareaSelectedFile'])) {

        $html = '';
        $selectedFilesCount = 0;

        $Media = new App\Media();
        $Media->setTypeId($_POST['library']);
        $Media->setUserId($USER->getId());

        //Get uploaded files
        if (!empty($_FILES)) {
            $Media->setUploadFiles($_FILES['inputFile']);
            $files = $Media->upload();
            $html .= ' ' . trans('Fichiers importés') . ' : ' . $files['countUpload'] . '. ' . !empty($files['errors']) ? $files['errors'] : '';
        }

        //Get selected files
        if (!empty($_POST['textareaSelectedFile'])) {

            $selectedFiles = $_POST['textareaSelectedFile'];

            if (strpos($selectedFiles, '|||')) {
                $files = explode('|||', $selectedFiles);
            } else {
                $files = array($selectedFiles);
            }

            foreach ($files as $key => $file) {
                $Media->setName($file);
                if ($Media->save()) $selectedFilesCount++;
            }

            $html .= ' Fichiers sélectionnés ' . $selectedFilesCount . '. ';
        }

        App\Flash::setMsg($html, 'info');
    }
}