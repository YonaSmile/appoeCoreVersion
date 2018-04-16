<?php
if (checkPostAndTokenRequest()) {

    //Clean data
    $_POST = cleanRequest($_POST);

    $Response = new App\Response();

    if (isset($_POST['ADDCATEGORY'])) {

        if (!empty($_POST['name'])
            && !empty($_POST['type'])
            && !empty($_POST['parentId'])
        ) {

            $Category = new App\Category();

            //Add Page
            $Category->feed($_POST);
            if ($Category->notExist()) {
                if ($Category->save()) {

                    //Add Translation
                    $Traduction = new App\Plugin\Traduction\Traduction();
                    $Traduction->setLang(LANG);
                    $Traduction->setMetaKey($Category->getName());
                    $Traduction->setMetaValue($Category->getName());
                    $Traduction->save();

                    //Delete post data
                    unset($_POST);

                    $Response->status = 'success';
                    $Response->error_code = 0;
                    $Response->error_msg = trans('La catégorie a été enregistrée');

                } else {
                    $Response->status = 'danger';
                    $Response->error_code = 1;
                    $Response->error_msg = trans('Un problème est survenu lors de l\'enregistrement de la catégorie');
                }
            } else {
                $Response->status = 'danger';
                $Response->error_code = 1;

                if ($Category->getStatus() == 0) {
                    $Response->error_msg = trans('Cette catégorie est archivée. Voulez vous la restaurer')
                        . ' ? <button type="button" data-restaureid="' . $Category->getId() . '" class="btn btn-link retaureCategory">Oui</button>';
                } else {
                    $Response->error_msg = trans('Cette catégorie existe déjà');
                }
            }
        } else {
            $Response->status = 'danger';
            $Response->error_code = 1;
            $Response->error_msg = trans('Tous les champs sont obligatoires');
        }
    }
}