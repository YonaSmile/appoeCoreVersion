<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/main.php');
includePluginsFiles(true);

//Clean data
$_GET = cleanRequest($_GET);
$Config = getConfig();

if(!empty($_GET['token']) && $Config['options']['allowApi'] === 'true' && $_GET['token'] == $Config['data']['apiToken']){


    exit();
}

if (!headers_sent()) {
    header('HTTP/1.1 404 Not Found', true, 404);
}
echo file_exists(ROOT_PATH . '404.php') ? getFileContent(ROOT_PATH . '404.php') : getAsset('404', true);