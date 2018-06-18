<?php
if ( isset( $_SESSION['auth'] ) && !bot_detected()) {

    list( $idSession, $emailSession ) = explode( '351ab51c2d33efb942cab11f25cdc517a84df66bc51ffe1f2beb!a6fgcb!f152ddb3!6ff2cd41abd35df42cbb21a', $_SESSION['auth'] );

    $USER = new App\Users( $idSession );

    //Check if user exist & valide
    if ( ! $USER->exist() || ! $USER->getStatut() ) {
        header( 'location:' . WEB_DIR_URL );
        exit();
    }

    $key = sha1( $USER->getEmail() . $_SERVER['REMOTE_ADDR'] );

    //Check valid session
    if ( $key != $emailSession ) {
        header( 'location:' . WEB_DIR_URL);
        exit();
    }

    $Page = new App\Page( substr( basename( $_SERVER['PHP_SELF'] ), 0, - 4 ) );

    //Check if user have right access to this page
    if ( ! $Page->isExist() OR $Page->getMinRoleId() > $USER->getRole() ) {
        header( 'location:' . WEB_DIR_URL );
        exit();
    }

} else {
    header( 'location:' . WEB_DIR_URL );
    exit();
}
