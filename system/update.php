<?php
require('ini.php');
require($_SERVER['DOCUMENT_ROOT'] . '/app/Autoloader.php');
\App\Autoloader::register();
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/functions.php');

use App\Version;

//Github links
$gitHub = 'https://github.com/YonaSmile/';
$gitHubUserContent = 'https://raw.githubusercontent.com/YonaSmile/';

//Core version
Version::setFile(WEB_APP_PATH . 'version.json');
if (Version::show() && Version::getVersion() < getHttpRequest($gitHubUserContent . 'appoeCoreVersion/master/version.json')) {

    //Update Core
    if (downloadZip(ROOT_PATH . 'appoeCore.zip', 'https://github.com/YonaSmile/appoeCoreVersion/archive/master.zip')) {
        if (unzipSkipFirstFolder(ROOT_PATH . 'appoeCore.zip', ROOT_PATH, 'appoeCoreVersion-master', WEB_APP_PATH)) {

            //Update Rooter
            if (downloadZip(ROOT_PATH . 'rooter.zip', 'https://github.com/YonaSmile/appoeRooterVersion/archive/master.zip')) {
                if (unzipSkipFirstFolder(ROOT_PATH . 'rooter.zip', ROOT_PATH, 'appoeRooterVersion-master', ROOT_PATH)) {

                    //Update DataBase
                    updateDB();
                }
            }
        }
    }
}

//Lib version
Version::setFile(WEB_LIB_PATH . 'version.json');
if (Version::show() && Version::getVersion() < getHttpRequest($gitHubUserContent . 'appoeLibVersion/master/version.json')) {

    //Update Lib
    if (downloadZip(ROOT_PATH . 'appoeLib.zip', 'https://github.com/YonaSmile/appoeLibVersion/archive/master.zip')) {
        unzipSkipFirstFolder(ROOT_PATH . 'appoeLib.zip', ROOT_PATH, 'appoeLibVersion-master', WEB_LIB_PATH);
    }
}

//Plugins versions
$plugins = getPlugins();
if (!isArrayEmpty($plugins)) {
    foreach ($plugins as $plugin) {
        if (!empty($plugin['versionPath'])) {
            Version::setFile($plugin['versionPath']);
            if (Version::show() && Version::getVersion() < getHttpRequest($gitHubUserContent . 'appoePluginsVersions/master/' . $plugin['name'] . '/version.json')) {

                //Update Plugin
                if (downloadZip(ROOT_PATH . 'plugins.zip', 'https://github.com/YonaSmile/appoePluginsVersions/archive/master.zip')) {
                    unzipSkipFirstFolder(ROOT_PATH . 'plugins.zip', ROOT_PATH, 'appoePluginsVersions-master', WEB_PLUGIN_PATH);
                    exit();
                }
            }
        }
    }
}

if(@unlink(WEB_TEMPLATE_PATH.'plugins/bootstrap/css/bootstrap.css.map')) {
    if (@unlink(WEB_TEMPLATE_PATH . 'plugins/bootstrap/css/bootstrap.min.css.map')) {
        if (@unlink(WEB_TEMPLATE_PATH . 'plugins/bootstrap/js/bootstrap.bundle.js.map')) {
            @unlink(WEB_TEMPLATE_PATH . 'plugins/bootstrap/js/bootstrap.bundle.min.js.map');
        }
    }
}