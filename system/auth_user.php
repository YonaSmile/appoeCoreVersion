<?php
if (pageSlug() == 'hibour') {
    if ((isUserSessionExist() || isUserCookieExist()) && !bot_detected()) {
        header('location:app/page/');
        exit();
    }
    disconnectUser(false);
}

if (isset($_POST['APPOECONNEXION'])) {

    if (checkPostAndTokenRequest(false)) {

        //Clean form
        $_POST = cleanRequest($_POST);

        if (!empty($_POST['loginInput'])
            AND !empty($_POST['passwordInput'])
            AND empty($_POST['identifiant'])
            AND !empty($_POST['checkPass'])) {

            $login = trim($_POST['loginInput']);
            $pass = $_POST['passwordInput'];

            //check length of login & pass
            if (strlen($login) < 70 && strlen($pass) < 30) {

                $User = new \App\Users();
                $User->setLogin($login);
                $User->setPassword($pass);

                //if user not exist
                if (!$User->authUser()) {

                    \App\Flash::setMsg(trans('Vous n\'êtes pas identifié') . ' !');

                } else {
                    session_regenerate_id();
                    $sessionCrypted = \App\Shinoui::Crypter($User->getId() . '!a6fgcb!f152ddb3!' . sha1($User->getLogin() . $_SERVER['REMOTE_ADDR']));
                    $_SESSION['auth' . slugify($_SERVER['HTTP_HOST'])] = $sessionCrypted;
                    setcookie('hibour' . slugify($_SERVER['HTTP_HOST']), $sessionCrypted, time() + (12 * 3600), '/', '', false, true);
                    mehoubarim_connecteUser();

                    //Backup database
                    appBackup();

                    //Check if user is a visitor
                    if (!isUserAuthorized('cms') && !isUserAuthorized('itemGlue') && !isUserAuthorized('shop')) {
                        $_SESSION['visitor'] = true;
                    }

                    //Check for forwarding page
                    if (isset($_POST['forwardPage'])) {
                        if (!empty($_POST['forwardPage'])) {
                            header('location:' . $_POST['forwardPage']);
                            exit();
                        }
                    }

                    header('location:/app/page/');
                    exit();
                }
            }
        }
    }
}