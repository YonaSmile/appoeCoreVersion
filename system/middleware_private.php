<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/main.php');
includePluginsFiles();
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/auth_user.php');

//Check maintenance mode
if (!checkMaintenance() && pageSlug() != 'hibour') {
    header('HTTP/1.1 503 Service Unavailable');
    header('Status: 503 Service Temporarily Unavailable');
    header('Retry-After: 3600');
    echo getFileContent($_SERVER['DOCUMENT_ROOT'] . '/maintenance.php');
    exit();
}

//Backup database
appBackup();
