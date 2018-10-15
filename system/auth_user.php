<?php
if (pageSlug() == 'hibour') {
    if (!empty($_SESSION['auth' . $_SERVER['HTTP_HOST']])) {
        header('location:app/page/');
    }
}

if (isset($_POST['APPOECONNEXION'])) {

    if (checkPostAndTokenRequest(false)) {

        //Clean form
        $_POST = cleanRequest($_POST);

        if (!empty($_POST['emailInput'])
            AND !empty($_POST['passwordInput'])
            AND empty($_POST['identifiant'])
            AND !empty($_POST['checkPass'])) {

            $email = str_replace(' ', '', strtolower($_POST['emailInput']));
            $pass = $_POST['passwordInput'];

            //check lenght of email & pass
            if (strlen($email) < 40 && strlen($pass) < 20) {

                //Check for valid email
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                    $User = new \App\Users();
                    $User->setEmail($email);
                    $User->setPassword($pass);

                    //if user not exist
                    if (!$User->authUser()) {

                        \App\Flash::setMsg(trans('Vous n\'êtes pas identifié') . ' !');

                    } else {

                        $_SESSION['auth' . $_SERVER['HTTP_HOST']] = $User->getId() . '351ab51c2d33efb942cab11f25cdc517a84df66bc51ffe1f2beb!a6fgcb!f152ddb3!6ff2cd41abd35df42cbb21a' . sha1($User->getEmail() . $_SERVER['REMOTE_ADDR']);

                        mehoubarim_connecteUser();

                        //Backup database
                        appBackup();

                        header('location:app/page/');
                    }
                }
            }
        }
    }
}