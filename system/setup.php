<?php
require($_SERVER['DOCUMENT_ROOT'] . '/app/main.php');
$Users = new App\Users();
$Menu = new App\Menu();
$File = new App\File();
$Category = new App\Category();
$CategoryRelations = new App\CategoryRelations();

//Creating table
if (true === $Users->createTable()) {
    if (true === $Menu->createTable()) {
        if (true === $File->createTable()) {
            if (true === $Category->createTable()) {
                if (true === $CategoryRelations->createTable()) {
                    if (unlink(WEB_SYSTEM_PATH . 'setup.php')) {
                        echo trans('L\'application APPOE à bien été installé') . ' <a href="' . WEB_DIR_URL . '">' . trans('Aller à la page de connexion') . '</a>';
                        exit();
                    }
                }
            }
        }
    }
}
echo trans('Un problème est survenu');
