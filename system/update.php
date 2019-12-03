<?php
require('ini.php');
require($_SERVER['DOCUMENT_ROOT'] . '/app/Autoloader.php');
\App\Autoloader::register();
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/functions.php');

//Update Core
if (downloadZip(ROOT_PATH . 'appoeCore.zip', 'https://github.com/YonaSmile/appoeCoreVersion/archive/master.zip')) {
    if (unzipSkipFirstFolder(ROOT_PATH . 'appoeCore.zip', ROOT_PATH, 'appoeCoreVersion-master', WEB_APP_PATH)) {

        //Update Rooter
        if (downloadZip(ROOT_PATH . 'rooter.zip', 'https://github.com/YonaSmile/appoeRooterVersion/archive/master.zip')) {
            if (unzipSkipFirstFolder(ROOT_PATH . 'rooter.zip', ROOT_PATH, 'appoeRooterVersion-master', ROOT_PATH)) {

                //Update Lib
                if (downloadZip(ROOT_PATH . 'appoeLib.zip', 'https://github.com/YonaSmile/appoeLibVersion/archive/master.zip')) {
                    if (unzipSkipFirstFolder(ROOT_PATH . 'appoeLib.zip', ROOT_PATH, 'appoeLibVersion-master', WEB_LIB_PATH)) {

                        //Update Plugin
                        if (downloadZip(ROOT_PATH . 'plugins.zip', 'https://github.com/YonaSmile/appoePluginsVersions/archive/master.zip')) {
                            if (unzipSkipFirstFolder(ROOT_PATH . 'plugins.zip', ROOT_PATH, 'appoePluginsVersions-master', WEB_PLUGIN_PATH)) {

                                //Update DataBase
                                updateDB();

                                //Delete Files
                                //deleteAllFolderContent(WEB_APP_PATH . 'middleware.php');
                            }
                        }
                    }
                }
            }
        }
    }
}