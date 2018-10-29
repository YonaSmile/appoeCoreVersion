<?php
require_once('header.php');

if (checkAjaxRequest()) {

    if (getUserIdSession()) {

        //Ban user
        if (!empty($_POST['idDeleteUser'])) {
            $User = new \App\Users($_POST['idDeleteUser']);
            if ($User->delete()) {
                echo json_encode(true);
            }
        }

        //Valide user
        if (!empty($_POST['idValideUser'])) {
            $User = new \App\Users($_POST['idValideUser']);
            $User->setStatut(1);
            if ($User->update()) {
                echo json_encode(true);
            }
        }

        //Get users roles
        if (!empty($_POST['GETUSERSROLES'])) {
            echo json_encode(getRoles(), JSON_UNESCAPED_UNICODE);
        }
    }
}