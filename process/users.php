<?php
if ( checkPostAndTokenRequest() ) {

	//Clean data
	$_POST = cleanRequest( $_POST );

	//Response class
	$Response = new App\Response();

	if ( isset( $_POST['ADDUSER'] )
	     && ! empty( $_POST['email'] )
	     && filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL )
	     && ! empty( $_POST['password'] )
	     && ! empty( $_POST['role'] )
	     && ! empty( $_POST['nom'] )
	     && ! empty( $_POST['prenom'] )
	) {

        $UserUpdate = new App\Users();

		//Add User
        $UserUpdate->feed( $_POST );

		if ( ! $UserUpdate->exist() ) {
			if ( $UserUpdate->save() ) {

				//Delete post data
				unset( $_POST );

				$Response->status     = 'success';
				$Response->error_code = 0;
				$Response->error_msg  = trans('L\'utilisateur a été enregistré');

			} else {

				$Response->status     = 'danger';
				$Response->error_code = 1;
				$Response->error_msg  = trans('Un problème est survenu lors de l\'enregistrement du nouvel utilisateur');
			}
		} else {

			$Response->status     = 'warning';
			$Response->error_code = 2;
			$Response->error_msg  = trans('Cette adresse Email est déjà utilisé par un utilisateur');
		}
	} else {

		$Response->status     = 'danger';
		$Response->error_code = 1;
		$Response->error_msg  = trans('Tous les champs sont obligatoires');
	}

    if ( isset( $_POST['UPDATEUSER'] ) ) {

        if ( ! empty( $_POST['id'] )
            && ! empty( $_POST['email'] && filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL ) )
            && ! empty( $_POST['nom'] )
            && ! empty( $_POST['prenom'] )
        ) {

            $UserUpdate = new App\Users( $_POST['id'] );

            $UserUpdate->feed( $_POST );

            if ( ! $UserUpdate->exist( true ) ) {
                if ( $UserUpdate->update() ) {

                    //Delete post data
                    unset( $_POST );

                    $Response->status     = 'success';
                    $Response->error_code = 0;
                    $Response->error_msg  = trans('L\'utilisateur a été mis à jour');

                } else {

                    $Response->status     = 'danger';
                    $Response->error_code = 1;
                    $Response->error_msg  = trans('Un problème est survenu lors de la mise à jour de l\'utilisateur');
                }
            } else {

                $Response->status     = 'warning';
                $Response->error_code = 2;
                $Response->error_msg  = trans('Cette adresse Email est déjà utilisé par un utilisateur');
            }
        } else {

            $Response->status     = 'danger';
            $Response->error_code = 1;
            $Response->error_msg  = trans('Tous les champs sont obligatoires');
        }
    }

    if ( isset( $_POST['UPDATEPASSWORD'] ) ) {

        if ( ! empty( $_POST['id'] )
            && ! empty( $_POST['password'] )
            && ! empty( $_POST['password2'] )
        ) {

            if ( $_POST['password'] == $_POST['password2'] ) {

                $UserUpdate = new App\Users( $_POST['id'] );

                if ( $UserUpdate->exist() ) {

                    $UserUpdate->setPassword( $_POST['password'] );

                    if ( $UserUpdate->updatePassword() ) {

                        //Delete post data
                        unset( $_POST );

                        $Response->status     = 'success';
                        $Response->error_code = 0;
                        $Response->error_msg  = trans('Le nouveau mot de passe a été enregistré');

                    } else {

                        $Response->status     = 'danger';
                        $Response->error_code = 1;
                        $Response->error_msg  = trans('Un problème est survenu lors de la mise à jour du nouveau mot de passe');
                    }
                } else {

                    $Response->status     = 'warning';
                    $Response->error_code = 2;
                    $Response->error_msg  = trans('Cet utilisateur n\'est pas identifié');
                }
            } else {
                $Response->status     = 'danger';
                $Response->error_code = 1;
                $Response->error_msg  = trans('Le mot de passe n\'est pas confirmé correctement');
            }
        } else {

            $Response->status     = 'danger';
            $Response->error_code = 1;
            $Response->error_msg  = trans('Tous les champs sont obligatoires');
        }
    }
}