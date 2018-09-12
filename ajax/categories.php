<?php
require_once('header.php');
if (checkAjaxRequest()) {

    if (getUserIdSession()) {

        $_POST = cleanRequest($_POST);

        if (isset($_POST['updateCategoryName']) && !empty($_POST['idCategory']) && isset($_POST['newName'])) {

            $Category = new \App\Category($_POST['idCategory']);
            $Category->setName($_POST['newName']);

            if ($Category->update()) {
                echo 'true';
            }
        }

        if (isset($_POST['getCategoriesByType']) && !empty($_POST['categoryType'])) {
            $Category = new \App\Category();
            $Category->setType($_POST['categoryType']);
            $allCatgories = extractFromObjToArrForList($Category->showByType(), 'id');

            $allCatgories[10] = trans('Aucun parent');
            if ($allCatgories) {
                echo \App\Form::select(trans('Catégorie parente'), 'parentId', $allCatgories, '', true);
            }
        }

        if (isset($_POST['deleteCategory']) && !empty($_POST['idCategory'])) {

            $Category = new \App\Category($_POST['idCategory']);

            if ($Category->delete()) {
                echo 'true';
            }
        }

        if (isset($_POST['restaureCategory']) && !empty($_POST['idCategoryToRestaure'])) {

            $Category = new \App\Category($_POST['idCategoryToRestaure']);
            $Category->setStatus(1);

            if ($Category->update()) {
                echo 'true';
            }
        }
    }
}