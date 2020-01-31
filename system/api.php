<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/main.php');
includePluginsFiles(true);

//Clean data
$_GET = cleanRequest($_GET);
$Config = getConfig();

if (!empty($_GET['token']) && $Config['options']['allowApi'] === 'true' && $_GET['token'] == $Config['data']['apiToken']) {

    header("Content-Type: application/json; charset=UTF-8");

    $lang = !empty($_GET['lang']) && is_string($_GET['lang']) ? $_GET['lang'] : false;

    //Get recent articles
    if (!empty($_GET['getArticles']) && $_GET['getArticles'] == 'all') {

        $length = !empty($_GET['length']) && is_numeric($_GET['length']) ? $_GET['length'] : false;
        echo jsonHtmlParse(getRecentArticles($length, $lang));
        exit();
    }

    //Get articles by category
    if (!empty($_GET['getArticleByCategory']) && is_numeric($_GET['getArticleByCategory'])) {

        $parent = !empty($_GET['parent']) && $_GET['parent'] == 'true' ? true : false;
        $length = !empty($_GET['length']) && is_numeric($_GET['length']) ? $_GET['length'] : false;
        echo jsonHtmlParse(getArticlesByCategory($_GET['getArticleByCategory'], $parent, $length, $lang));
        exit();
    }

}

if (!headers_sent()) {
    header('HTTP/1.1 404 Not Found', true, 404);
}
echo file_exists(ROOT_PATH . '404.php') ? getFileContent(ROOT_PATH . '404.php') : getAsset('404', true);
exit();