<?php
if ((isUserSessionExist() || isUserCookieExist()) && !bot_detected()) {
    
    $userConnexion = getUserConnexion();

    if ($userConnexion) {

        //Check if user exist & valide
        $USER = new \App\Users($userConnexion['idUserConnexion']);
        if (!$USER->exist() || !$USER->getStatut()) {
            header('location:' . WEB_DIR_URL);
            exit();
        }

        //Check valid session
        $key = sha1($USER->getEmail() . $_SERVER['REMOTE_ADDR']);
        if ($key != $userConnexion['emailUserConnexion']) {

            deconnecteUser();
            header('location:' . WEB_DIR_URL);
            exit();
        }

        //Check if user have right access to this page
        $Page = new \App\Page(substr(basename($_SERVER['PHP_SELF']), 0, -4));
        if (!$Page->isExist() OR $Page->getMinRoleId() > $USER->getRole()) {

            deconnecteUser();
            header('location:' . WEB_DIR_URL);
            exit();
        }
    } else {

        deconnecteUser();
        header('location:' . WEB_DIR_URL);
        exit();
    }

} else {

    deconnecteUser();
    header('location:' . WEB_DIR_URL);
    exit();
}
