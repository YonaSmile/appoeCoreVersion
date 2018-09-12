<?php
require_once( 'header.php' );

if ( checkAjaxRequest() ) {

	if ( getUserIdSession() ) {

		if ( ! empty( $_POST['idDeleteUser'] ) ) {
			$User = new \App\Users( $_POST['idDeleteUser'] );
			if ( $User->delete() ) {
				echo json_encode( true );
			}
		}
	}
}