<?php
if (isset($_SESSION['auth' . $_SERVER['HTTP_HOST']]) && !bot_detected()) {

    if (strstr($_SESSION['auth' . $_SERVER['HTTP_HOST']], '351ab51c2d33efb942cab11f25cdc517a84df66bc51ffe1f2beb!a6fgcb!f152ddb3!6ff2cd41abd35df42cbb21a')) {
        list($idSession, $emailSession) = explode('351ab51c2d33efb942cab11f25cdc517a84df66bc51ffe1f2beb!a6fgcb!f152ddb3!6ff2cd41abd35df42cbb21a', $_SESSION['auth' . $_SERVER['HTTP_HOST']]);

        //Check if user exist & valide
        $USER = new \App\Users($idSession);
        if (!$USER->exist() || !$USER->getStatut()) {
            header('location:' . WEB_DIR_URL);
            exit();
        }

        //Check valid session
        $key = sha1($USER->getEmail() . $_SERVER['REMOTE_ADDR']);
        if ($key != $emailSession) {

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
