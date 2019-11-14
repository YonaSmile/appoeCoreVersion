<?php
if ((isUserSessionExist() || isUserCookieExist()) && appoeMinRole() && !bot_detected()) {

    $userConnexion = getUserConnexion();

    if ($userConnexion ) {

        //Check if user exist & valide
        $USER = new \App\Users($userConnexion['idUserConnexion']);
        if (!$USER->exist() OR !$USER->getStatut()) {

            disconnectUser(false);
            if (!headers_sent()) {
                header('location:' . WEB_DIR_URL);
            }
            exit();
        }

        //Check valid session
        $key = sha1($USER->getLogin() . $_SERVER['REMOTE_ADDR']);
        if ($key != $userConnexion['loginUserConnexion']) {

            disconnectUser(false);
            if (!headers_sent()) {
                header('location:' . WEB_DIR_URL);
            }
            exit();
        }

        //Check if user have right access to this page
        $Page = new \App\Page(substr(basename($_SERVER['PHP_SELF']), 0, -4));
        if (!$Page->isExist() OR $Page->getMinRoleId() > $USER->getRole()) {

            disconnectUser(false);
            if (!headers_sent()) {
                header('location:' . WEB_DIR_URL);
            }
            exit();
        }

        setAppPageName($Page->getName());
        setAppPageSlug($Page->getSlug());

    } else {

        disconnectUser(false);
        if (!headers_sent()) {
            header('location:' . WEB_DIR_URL);
        }
        exit();
    }

} else {

    disconnectUser(false);
    if (!headers_sent()) {
        header('location:' . WEB_DIR_URL);
    }
    exit();
}