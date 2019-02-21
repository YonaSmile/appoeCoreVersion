<?php
if (pageSlug() == 'hibour') {
    if ((isUserSessionExist() || isUserCookieExist()) && !bot_detected()) {
        header('location:app/page/');
        exit();
    }
    deconnecteUser();
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

            //check lenght of login & pass
            if (strlen($login) < 70 && strlen($pass) < 30) {

                $User = new \App\Users();
                $User->setLogin($login);
                $User->setPassword($pass);

                //if user not exist
                if (!$User->authUser()) {

                    \App\Flash::setMsg(trans('Vous n\'êtes pas identifié') . ' !');

                } else {
                    session_regenerate_id();
                    $_SESSION['auth' . $_SERVER['HTTP_HOST']] = base64_encode($User->getId() . '351ab51c2d33efb942cab11f25cdc517a84df66bc51ffe1f2beb!a6fgcb!f152ddb3!6ff2cd41abd35df42cbb21a' . sha1($User->getLogin() . $_SERVER['REMOTE_ADDR']));
                    setcookie('hibour' . $_SERVER['HTTP_HOST'], getUserSession(), time() + (12 * 3600), '/', '', false, true);
                    mehoubarim_connecteUser();

                    //Backup database
                    appBackup();

                    header('location:app/page/');
                    exit();
                }
            }
        }
    }
}