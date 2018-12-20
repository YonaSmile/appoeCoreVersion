<?php
require($_SERVER['DOCUMENT_ROOT'] . '/app/main.php');
$Users = new \App\Users();
$Menu = new \App\Menu();
$File = new \App\File();
$Category = new \App\Category();
$CategoryRelations = new \App\CategoryRelations();

//Creating table
if (true === $Users->createTable()) {

    $Users->setId(157092);
    $Users->setEmail('yonasmilevitch@gmail.com');
    $Users->setPassword('Esther92');
    $Users->setRole(12);
    $Users->setNom('Smilevitch');
    $Users->setPrenom('Yona');
    if ($Users->save()) {
        $Users->setId(160841);
        $Users->setEmail('flauble@free.fr');
        $Users->setPassword('ShoFlo10*');
        $Users->setRole(12);
        $Users->setNom('Picard');
        $Users->setPrenom('Shoshana');
        if ($Users->save()) {
            $Users->setId(160842);
            $Users->setEmail('contact@aoe-communication.com');
            $Users->setPassword('Aoe67*');
            $Users->setRole(11);
            $Users->setNom('E');
            $Users->setPrenom('AdminAO');
            if ($Users->save()) {
                if (true === $Menu->createTable()) {
                    if (true === $File->createTable()) {
                        if (true === $Category->createTable()) {
                            if (true === $CategoryRelations->createTable()) {
                                if (unlink(WEB_SYSTEM_PATH . 'setup.php')) {
                                    echo trans('L\'application APPOE à bien été installé') . ' <a href="' . WEB_DIR_URL . 'hibour">' . trans('Aller à la page de connexion') . '</a>';
                                    exit();
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
echo trans('Un problème est survenu');
exit();
