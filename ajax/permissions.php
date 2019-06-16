<?php
require_once('header.php');

use App\Menu;

if (checkAjaxRequest()) {

    if (getUserIdSession()) {

        $_POST = cleanRequest($_POST);

        //update permission
        if (isset($_POST['updatePermission'])
            && !empty($_POST['id'])
            && !empty($_POST['name'])
            && !empty($_POST['slug'])
            && !empty($_POST['min_role_id'])
            && !empty($_POST['parent_id'])
            && isset($_POST['statut'])
            && isset($_POST['order_menu'])
            && isset($_POST['pluginName'])
        ) {
            $Menu = new Menu();
            if ($Menu->updateMenu($_POST['id'],
                $_POST['name'], $_POST['slug'], $_POST['min_role_id'], $_POST['statut'],
                $_POST['parent_id'], $_POST['order_menu'], $_POST['pluginName'])
            ) {
                echo 'true';
            }
        }

        //add new permission
        if (isset($_POST['ADDPERMISSION'])
            && !empty($_POST['id'])
            && !empty($_POST['slug'])
            && !empty($_POST['name'])
            && !empty($_POST['min_role_id'])
            && isset($_POST['statut'])
            && !empty($_POST['parent_id'])
            && isset($_POST['order_menu'])
            && isset($_POST['pluginName'])
        ) {
            $Menu = new Menu();
            if ($Menu->insertMenu($_POST['id'], $_POST['slug'],
                $_POST['name'], $_POST['min_role_id'],
                $_POST['statut'], $_POST['parent_id'],
                $_POST['pluginName'], $_POST['order_menu'])
            ) {
                echo 'true';
            }
        }
    }
}