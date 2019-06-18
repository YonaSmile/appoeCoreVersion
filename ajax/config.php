<?php
require_once('header.php');

use App\AppConfig;

if (checkAjaxRequest()) {

    if (getUserIdSession()) {

        $_POST = cleanRequest($_POST);

        if (!empty($_POST['configName']) && !empty($_POST['configValue'])) {

            $data = array($_POST['configName'] => $_POST['configValue']);

            $Config = new AppConfig();
            if ($Config->write($data)) {

                echo json_encode(true);
                exit();
            }
        }

        if (!empty($_POST['restoreConfig']) && $_POST['restoreConfig'] == 'OK') {

            $Config = new AppConfig();
            if ($Config->restoreConfig()) {

                echo json_encode(true);
                exit();
            }
        }
    }
}
echo json_encode(false);