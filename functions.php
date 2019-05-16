<?php

//Get PHPMAILER
$phpMailerFolder = WEB_LIB_PATH . 'php/PHPMailer/';

use PHPMailer\PHPMailer\PHPMailer;

require_once $phpMailerFolder . 'Exception.php';
require_once $phpMailerFolder . 'PHPMailer.php';
require_once $phpMailerFolder . 'SMTP.php';

//Get all users in a const
$USER = new \App\Users();
$USER->setStatut(0);
define('ALLUSERS', serialize(extractFromObjArr($USER->showAll(), 'id')));

/**
 * @return string
 */
function pageSlug()
{
    return htmlentities(basename($_SERVER['REQUEST_URI']));
}

/**
 * @param $url
 * @param $classNameAdded
 * @param $scriptPage
 * @return string
 */
function activePage($url, $classNameAdded = 'active', $scriptPage = false)
{
    if (!empty($url)) {
        if (!$scriptPage) {
            if ($url == 'home' && empty(basename($_SERVER['REQUEST_URI']))) {
                return $classNameAdded;
            }

            if (false !== strpos(basename($_SERVER['REQUEST_URI']), $url)) {
                return $classNameAdded;
            }
        } else {
            if ($url == 'home' && empty(basename($_SERVER['SCRIPT_NAME']))) {
                return $classNameAdded;
            }

            if (false !== strpos(basename($_SERVER['SCRIPT_NAME']), $url)) {
                return $classNameAdded;
            }
        }
    }
    return '';
}

/**
 * Show Maintenance Header
 * @param String $text
 */
function showMaintenanceHeader($text = 'Page en maintenance !')
{
    echo '<h1 class="bg-danger m-5 text-white">' . $text . '</h1>';
}

/**
 * Construct general menu
 * @param $allPages
 *
 * @return array
 */

function constructMenu($allPages)
{
    //Create menu
    $menu = array();
    if (!empty($allPages)) {
        foreach ($allPages as $menuPage) {

            //check if is Page or URL
            if (is_null($menuPage->slug)) {
                $menuPage->slug = $menuPage->idCms;
            }

            //First level menu sorting by location and second level by parent Id.
            $menu[$menuPage->location][$menuPage->parentId][] = $menuPage;
        }
    }
    return $menu;
}

/**
 * @param int $primaryIndex
 * @param int $parent
 * @return array
 */
function getSessionMenu($primaryIndex = 1, $parent = 10)
{

    $sessionMenu = array();
    if (!isArrayEmpty($_SESSION['MENU']) && array_key_exists($primaryIndex, $_SESSION['MENU'])) {

        if (array_key_exists($parent, $_SESSION['MENU'][$primaryIndex])) {
            return $_SESSION['MENU'][$primaryIndex][$parent];
        }

        return $_SESSION['MENU'][$primaryIndex];
    };

    return $sessionMenu;
}

/**
 * @param int $primaryIndex
 * @return bool
 */
function hasMenu($primaryIndex = 1)
{
    return !isArrayEmpty($_SESSION['MENU']) && array_key_exists($primaryIndex, $_SESSION['MENU']);
}

/**
 * @param $index
 * @param int $menuPrimaryIndex
 * @return bool
 */
function hasSubMenu($index, $menuPrimaryIndex = 1)
{
    return array_key_exists($index, $_SESSION['MENU'][$menuPrimaryIndex]);
}

/**
 * @param $filename
 * @param string $jsonKey
 * @param string $jsonSecondKey
 * @return bool|array
 */
function getJsonContent($filename, $jsonKey = '', $jsonSecondKey = '')
{
    if (file_exists($filename)) {

        $json = file_get_contents($filename);
        $parsed_json = $json ? json_decode($json, true) : false;

        if (is_array($parsed_json)) {

            if (!empty($jsonKey)) {

                if (array_key_exists($jsonKey, $parsed_json)) {

                    if (!empty($jsonSecondKey && array_key_exists($jsonSecondKey, $parsed_json[$jsonKey]))) {

                        return $parsed_json[$jsonKey][$jsonSecondKey];
                    }

                    return $parsed_json[$jsonKey];

                }

                return false;

            } else {
                return $parsed_json;
            }
        }

    }

    return false;
}

/**
 * @param $filename
 * @param $content
 * @param $mode
 *
 * @return bool
 */
function putJsonContent($filename, $content, $mode = 'w+')
{

    $json_file = fopen($filename, $mode);
    if (false !== $json_file) {
        fwrite($json_file, json_encode($content));
        return fclose($json_file);
    }

    return false;
}

/**
 * @param string $appendName
 * @param string $name
 * @param string $slug
 * @return string
 */
function getTitle($name = '', $slug = '', $appendName = '')
{
    $html = '<div class="row"><div class="col-12">
            <h1 class="bigTitle icon-' . $slug . '"><span class="colorPrimary mr-2"></span>' . trans($name) . $appendName . '</h1>
            </div></div><hr class="mx-5 mt-3 mb-4">';

    return $html;
}

/**
 * @param string $color
 * @return string
 */
function getAppoeCredit($color = "#ccc")
{
    $html = 'Propulsé par <a target="_blank" style="color:' . $color . '" href="http://aoe-communication.com/" title="APPOE">APPOE</a>';

    return $html;
}

/**
 * @param $multiArray
 * @return bool
 */
function isArrayEmpty($multiArray)
{
    if (is_array($multiArray) and !empty($multiArray)) {

        $tmp = array_shift($multiArray);

        if (!isArrayEmpty($multiArray) or !isArrayEmpty($tmp)) {
            return false;
        }
        return true;
    }

    if (empty($multiArray)) {
        return true;
    }

    return false;
}

/**
 * Multidimensional Array Sort
 * @param $array
 * @param $keyName
 * @param int $order
 * @return array
 */
function array_sort($array, $keyName, $order = SORT_ASC)
{

    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $keyName) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
                break;
            case SORT_DESC:
                arsort($sortable_array);
                break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

/**
 * @param $mediaPath
 * @return bool
 */
function isImage($mediaPath)
{
    $imgExt = array('JPG', 'JPEG', 'PNG', 'GIF');
    return @is_array(getimagesize($mediaPath)) && in_array(strtoupper(getFileExtension($mediaPath)), $imgExt) ? true : false;
}

/**
 * @param $text
 * @param $size
 * @return string
 */
function shortenText($text, $size)
{
    return
        mb_strimwidth(
            strip_tags(
                html_entity_decode(
                    htmlspecialchars_decode($text))), 0, $size, '...', 'utf-8');
}

/**
 * Unset same key in array and return it sliced
 *
 * @param array $data
 * @param $compareKey
 * @param bool $returnSliceArray
 * @return array
 */
function unsetSameKeyInArr(array $data, $compareKey, $returnSliceArray = false)
{

    if (in_array($compareKey, array_keys($data))) {
        unset($data[$compareKey]);
    }

    if (false !== $returnSliceArray && is_int($returnSliceArray)) {
        $data = array_slice($data, 0, $returnSliceArray, true);
    }

    return $data;
}

/**
 * @param $text
 * @return string
 */
function minimalizeText($text)
{
    return strtolower(noaccent(trim($text)));
}

/**
 * @param $item
 * @return string
 */
function htmlSpeCharDecode($item)
{
    return htmlspecialchars_decode($item, ENT_QUOTES);
}

/**
 * @param $item
 * @return string
 */
function htmlEntityDecode($item)
{
    return html_entity_decode($item, ENT_QUOTES);
}

/**
 * @param $key
 * @param string $doc
 * @return mixed
 */
function trans($key, $doc = 'general')
{
    $trans = minimalizeText($key);
    if (INTERFACE_LANG != 'fr' && file_exists(FILE_LANG_PATH . INTERFACE_LANG . DIRECTORY_SEPARATOR . $doc . '.json')) {

        //get lang file
        $json = file_get_contents(FILE_LANG_PATH . INTERFACE_LANG . DIRECTORY_SEPARATOR . $doc . '.json');
        $parsedJson = json_decode($json, true);

        //preparing to compare
        $langArray = array_map('minimalizeText', array_keys($parsedJson));

        //comparing
        $tradPos = (false !== array_search($trans, $langArray)) ? array_search($trans, $langArray) : null;


        return !is_null($tradPos) ? $parsedJson[array_keys($parsedJson)[$tradPos]] : $key;

    } else {
        return $key;
    }
}

/**
 * @param string $urlPage
 * @return mixed
 */
function getPageHelp($urlPage)
{

    if (!isVisitor()) {
        $helpFile = FILE_LANG_PATH . INTERFACE_LANG . DIRECTORY_SEPARATOR . 'helpPages.json';

        //get help file
        $fileContent = getJsonContent($helpFile, $urlPage);

        return $fileContent ? $fileContent : false;
    }
    return false;
}

/**
 * @return bool
 */
function isVisitor()
{

    if (!empty($_SESSION['visitor'])) {
        return true;
    }

    return false;
}

/**
 * @param $text
 * @param $tradToOrigin
 * @param $lang
 * @return mixed
 */
function trad($text, $tradToOrigin = false, $lang = LANG)
{
    if (class_exists('App\Plugin\Traduction\Traduction')) {

        $Traduction = new \App\Plugin\Traduction\Traduction($lang);
        return !$tradToOrigin ? $Traduction->trans($text) : $Traduction->transToOrigin($text);
    }
    return $text;
}

/**
 * @param $text
 * @return null|string|string[]
 */
function slugify($text)
{
    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, '-');

    // remove duplicate -
    $text = preg_replace('~-+~', '-', $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

/**
 * @param $dirname
 * @param $onlyFiles
 * @return array
 */
function getFilesFromDir($dirname, $onlyFiles = false)
{
    $GLOBALS['dirname'] = $dirname;
    if ($onlyFiles) {
        return array_filter(scandir($dirname), function ($item) {
            return !is_dir($GLOBALS['dirname'] . $item);
        });
    }
    return array_diff(scandir($dirname), array('..', '.'));

}

/**
 * @return array
 */
function getLangs()
{
    return LANGUAGES;
}

/**
 * @return array
 */
function getAppLangs()
{
    return getFilesFromDir(WEB_SYSTEM_PATH . 'lang/');
}

/**
 * @param $name
 * @return string
 */
function getAppImg($name)
{
    return APP_IMG_URL . $name;
}

/**
 * @param $lang
 * @return bool
 */
function langExist($lang)
{

    if (array_key_exists($lang, LANGUAGES)) {
        return true;
    }
    return false;
}

/**
 * @return bool
 */
function getIP()
{
    foreach (
        array(
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ) as $key
    ) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                    return $ip;
                }
            }
        }
    }

    return false;
}

/**
 * @param $request
 *
 * @return bool
 */
function checkRequest($request)
{
    $unauthorized_characters = '"\'/\\#<>$*%!§;?([{)]}+=&²~|£µ';
    $request_error = 0;

    if (is_array($request)) {
        foreach ($request as $key => $value) {

            $requestLenght = strlen($value);

            for ($i = 0; $i < $requestLenght; $i++) {
                if (strpos($unauthorized_characters, strtolower($value[$i]))) {
                    $request_error++;
                }
            }
        }
    } else {

        $requestLenght = strlen($request);

        for ($i = 0; $i < $requestLenght; $i++) {
            if (strpos($unauthorized_characters, strtolower($request[$i]))) {
                $request_error++;
            }
        }
    }

    if ($request_error > 0) {
        return false;
    } else {
        return true;
    }

}

/**
 * @param $data
 * @return bool
 */
function generateSitemap($data)
{
    $sitemap = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
       http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

    $sitemap .= '<url>';
    $sitemap .= '<loc>' . WEB_DIR_URL . '</loc>';
    $sitemap .= '<changefreq>weekly</changefreq>';
    $sitemap .= '<priority>1.0</priority>';
    $sitemap .= '</url>';

    foreach ($data as $key => $url) {
        if (file_exists(WEB_PATH . $url->slug . '.php')) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . WEB_DIR_URL . $url->slug . '/</loc>';
            $sitemap .= '<changefreq>weekly</changefreq>';
            $sitemap .= '<priority>' . ($url->slug == 'home' ? '1.0' : '0.8') . '</priority>';
            $sitemap .= '</url>';
        }
    }
    $sitemap .= '</urlset>';

    if (false !== $file = fopen(ROOT_PATH . 'sitemap.xml', 'w+')) {
        if (false !== fwrite($file, $sitemap)) {
            return true;
        }
    }

    return false;
}

/**
 * @param array $concernedArray
 * @param $user
 * @param string $otherConcerned
 *
 * @return bool
 */
function userConcerned(array $concernedArray, $user, $otherConcerned = '')
{

    if (in_array($user, $concernedArray)) {
        return true;
    }

    if (!empty($otherConcerned)) {
        return $user == $otherConcerned ? true : false;
    }

    return false;
}

/**
 * Function to clean requests
 *
 * @param mixed $data
 * @param array|null $exclude
 *
 * @return array|string
 */
function cleanRequest($data, array $exclude = null)
{
    // Check if data is array
    if (is_array($data)) {

        //Check if there are values to exclude
        if (is_array($exclude) && !is_null($exclude)) {
            foreach ($data as $key => $value) {

                //check if data is not a multidimensionnel array
                if (!is_array($value)) {
                    if (!in_array($key, $exclude)) {
                        $data[$key] = cleanData($data[$key]);
                    }
                } else {
                    foreach ($value as $nkey => $nvalue) {
                        if (!in_array($nkey, $exclude)) {
                            $value[$nkey] = cleanData($value[$nkey]);
                        }
                    }
                }
            }
        } else {
            foreach ($data as $key => $value) {

                //check if data is not a multidimensionnel array
                if (!is_array($value)) {
                    $data[$key] = cleanData($data[$key]);

                } else {
                    foreach ($value as $nkey => $nvalue) {
                        $value[$nkey] = cleanData($value[$nkey]);
                    }
                }
            }
        }
    } else {
        $data = cleanData($data);
    }

    return $data;
}

/**
 * @param $data
 * @return string
 */
function cleanData($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

/**
 * Show unlimited params in array
 */
function showDebugData()
{
    echo '<pre>';
    print_r(func_get_args());
    echo '</pre>';
}

/**
 * @param $dateStr
 * @param string $format
 * @return bool
 */
function isValidDateTime($dateStr, $format = 'Y-m-d')
{
    $date = DateTime::createFromFormat($format, $dateStr);
    return $date && ($date->format($format) === $dateStr);
}

/**
 * @param $timestamp
 * @param bool $hour
 * @return string
 * @throws Exception
 */
function displayTimeStamp($timestamp, $hour = true)
{
    $Date = new \DateTime($timestamp, new \DateTimeZone('Europe/Paris'));

    if ($hour) {
        return $Date->format('d/m/Y H:i');
    }

    return $Date->format('d/m/Y');
}


/**
 * @param $date
 * @param bool $hour
 * @param string $format
 * @return bool|string
 * @throws Exception
 * @noinspection PhpUnhandledExceptionInspection
 */
function displayCompleteDate($date, $hour = false, $format = '%A %d %B %Y')
{
    if (isValidDateTime($date, 'd/m/Y')) {
        $date = DateTime::createFromFormat('d/m/Y', $date)->format('Y-m-d');
    }

    if (isValidDateTime($date)) {

        $Date = new \DateTime($date);
        $time = $hour ? $Date->format("Y-m-d H:i") : $Date->format("Y-m-d");

        $strFtime = empty($format) ? ($hour ? "%A %d %B %Y, %H:%M" : "%A %d %B %Y") : $format;

        return ucwords(strftime($strFtime, strtotime($time)));
    }
    return false;
}

/**
 * @param $duree
 *
 * @return string
 */
function displayDuree($duree)
{
    return (strlen($duree) == 2) ? $duree . ' min' : $duree;
}

/**
 * @param $date1
 * @param string $date2
 *
 * @return string
 * @throws Exception
 */
function getHoursFromDate($date1, $date2 = '')
{
    $Date1 = new \DateTime($date1);

    if (empty($date2)) {
        return $Date1->format('H:i');
    } else {
        $Date2 = new \DateTime($date2);

        return trans('De') . ' ' . $Date1->format('H:i') . ' ' . trans('à') . ' ' . $Date2->format('H:i');
    }
}

/**
 * @param $date
 *
 * @return string
 * @throws Exception
 */
function displayFrDate($date)
{
    if (isValidDateTime($date)) {

        $Date = new \DateTime($date);

        return $Date->format('d/m/Y');
    } else {
        return '';
    }
}

/**
 * @param $date
 *
 * @return string
 * @throws Exception
 */
function displayDBDate($date)
{
    if (isValidDateTime($date)) {

        $Date = new \DateTime($date);

        return $Date->format('Y-m-d');
    } else {
        return '';
    }
}

/**
 * @param $s
 * @return array[]|false|string[]
 */
function splitAtUpperCase($s)
{
    return preg_split('/(?=[A-Z])/', $s, -1, PREG_SPLIT_NO_EMPTY);
}

/**
 * @param $array
 * @param $searchingFor
 * @return bool
 */
function checkIfInArrayString($array, $searchingFor)
{
    foreach ($array as $element) {
        if (strpos($searchingFor, $element) !== false) {
            return true;
        }
    }
    return false;
}

/**
 * @return bool
 */
function checkMaintenance()
{
    if (true === MAINTENANCE) {
        if (
            (defined('IP_ALLOWED') && in_array(getIP(), IP_ALLOWED))
            ||
            (defined('IP_PARTS_ALLOWED') && checkIfInArrayString(IP_PARTS_ALLOWED, getIP()))
        ) {
            return false;
        }
        return true;
    }
    return false;
}

/**
 * @return array
 */
function getPlugins()
{

    $plugins = array();

    if ($pluginsDir = opendir(WEB_PLUGIN_PATH)) {
        while (false !== ($dossier = readdir($pluginsDir))) {

            $setupPath = '';
            $version = '';
            if ($dossier != '.' && $dossier != '..' && $dossier != 'index.php') {

                if (file_exists(WEB_PLUGIN_PATH . $dossier . '/setup.php')) {
                    $setupPath = WEB_PLUGIN_URL . $dossier . '/setup.php';
                }

                if (file_exists(WEB_PLUGIN_PATH . $dossier . '/version.json')) {
                    $version = WEB_PLUGIN_PATH . $dossier . '/version.json';
                }

                array_push($plugins, array(
                    'name' => $dossier,
                    'pluginPath' => WEB_PLUGIN_PATH . $dossier . '/',
                    'setupPath' => $setupPath,
                    'versionPath' => $version
                ));
            }
        }
    }

    return $plugins;
}

/**
 * @return array
 */
function getPluginsName()
{
    return array_keys(groupMultipleKeysArray(getPlugins(), 'name'));
}

/**
 * @param $setupPath
 * @return bool|string
 */
function activePlugin($setupPath)
{
    return file_get_contents($setupPath);
}

/**
 * @param $pluginName
 * @return bool
 */
function pluginExist($pluginName)
{
    $dir = WEB_PLUGIN_PATH . $pluginName;
    return file_exists($dir) && is_dir($dir);
}

/**
 * @param $octets
 * @return string
 */
function getSizeName($octets)
{
    $resultat = $octets;
    for ($i = 0; $i < 8 && $resultat >= 1024; $i++) {
        $resultat = $resultat / 1024;
    }
    if ($i > 0) {
        return preg_replace('/,00$/', '', number_format($resultat, 2, ',', ''))
            . ' ' . substr('KMGTPEZY', $i - 1, 1) . 'o';
    } else {
        return $resultat . ' o';
    }
}


/**
 * @param bool $DB
 * @return bool
 * @throws phpmailerException
 * @noinspection PhpUnhandledExceptionInspection
 */
function appBackup($DB = true)
{

    //check existing main backup folder or created it
    if (!file_exists(WEB_APP_PATH . 'backup/')) {
        if (mkdir(WEB_APP_PATH . 'backup', 0705)) {
            return appBackup();
        }
    }

    //check existing backup folder for today or created it
    if (!file_exists(WEB_BACKUP_PATH . date('Y-m-d') . DIRECTORY_SEPARATOR)) {
        if (mkdir(WEB_BACKUP_PATH . date('Y-m-d'), 0705)) {
            if ($DB) {

                //save db
                \App\DB::backup(date('Y-m-d'));

                //check if db was saved
                if (!file_exists(WEB_BACKUP_PATH . date('Y-m-d') . DIRECTORY_SEPARATOR . 'db.sql.gz')) {
                    error_log(date('d/m/Y H:i') . ' : La sauvegarde de la base de données de ' . WEB_TITLE . ' n\'a pas été effectué.', 0);
                }
            }
        }

        //delete old folders (-30 days)
        $maxAutorizedFolderDate = new \DateTime('-30 days');
        $directories = scandir(WEB_BACKUP_PATH);
        foreach ($directories as $directory) {
            if ($directory != '.' and $directory != '..') {
                if (is_dir(WEB_BACKUP_PATH . $directory)) {
                    if ($maxAutorizedFolderDate > new \DateTime($directory)) {
                        deleteAllFolderContent(WEB_BACKUP_PATH . $directory);
                    }
                }
            }
        }
    }

    return true;
}

/**
 * @param $path
 * @param $url
 * @return bool
 */
function downloadZip($path, $url)
{
    $fh = fopen($path, 'w');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FILE, $fh);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // this will follow redirects
    curl_exec($ch);
    curl_close($ch);
    fclose($fh);

    return true;
}


/**
 * Recursively copy files from one directory to another
 *
 * @param String $src - Source of files being moved
 * @param String $dest - Destination of files being moved
 * @return bool
 */
function rcopy($src, $dest)
{

    // If source is not a directory stop processing
    if (!is_dir($src)) return false;

    // If the destination directory does not exist create it
    if (!is_dir($dest)) {
        if (!mkdir($dest)) {
            // If the destination directory could not be created stop processing
            return false;
        }
    }

    // Open the source directory to read in files
    $i = new \DirectoryIterator($src);
    foreach ($i as $f) {
        if ($f->isFile()) {
            copy($f->getRealPath(), "$dest/" . $f->getFilename());
        } else if (!$f->isDot() && $f->isDir()) {
            rcopy($f->getRealPath(), "$dest/$f");
        }
    }

    return true;
}

/**
 * @param string $folder
 * @return array|bool
 */
function saveFiles($folder = 'public')
{

    // Get real path for our folder
    $rootPath = realpath(getenv('DOCUMENT_ROOT') . DIRECTORY_SEPARATOR . $folder);

    $dest = ROOT_PATH . 'app/backup/' . date('Y-m-d');

    if (!is_dir($dest) && !is_file($dest)) {
        if (!mkdir($dest)) {
            // If the destination directory could not be created stop processing
            return false;
        }
    }

    $filename = slugify($folder) . '-' . date('H_i_s') . '-files.zip';
    $saveFileName = $dest . DIRECTORY_SEPARATOR . $filename;
    $downloadFileName = WEB_DIR_URL . 'app/backup/' . date('Y-m-d') . DIRECTORY_SEPARATOR . $filename;

    // Initialize archive object
    $zip = new ZipArchive();
    if ($zip->open($saveFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {

        // Create recursive directory iterator
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        $filesSize = 0;

        foreach ($files as $name => $file) {

            // Skip directories (they would be added automatically)
            if (!$file->isDir()) {

                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);

                $filesSize += $file->getSize();
            }
        }

        // Zip archive will be created only after closing object
        if ($zip->close()) {
            return array('copySize' => $filesSize, 'zipSize' => filesize($saveFileName), 'downloadLink' => $downloadFileName);
        }
    }
    return false;
}

/**
 * Create folder
 * @param string $structure
 * @param int $chmod
 * @return bool
 */
function createFolder($structure, $chmod = 0755)
{
    if (!is_dir($structure)) {
        if (!mkdir($structure, $chmod)) {
            return false;
        }
    }
    return true;
}

/**
 * Create file
 * @param string $structure
 * @return bool
 */
function createFile($structure)
{
    if (!is_file($structure)) {
        if (!fopen($structure, 'w')) {
            return false;
        }
    }
    return true;
}

/**
 * Recursively move files from one directory to another
 *
 * @param String $src - Source of files being moved
 * @param String $dest - Destination of files being moved
 * @return bool
 */
function rmove($src, $dest)
{

    // If source is not a directory stop processing
    if (!is_dir($src) && !is_file($dest)) return false;

    // If the destination directory does not exist create it
    if (!is_dir($dest) && !is_file($dest)) {
        if (!mkdir($dest)) {
            // If the destination directory could not be created stop processing
            return false;
        }
    }

    if (is_file($dest)) {
        rename(realpath($src), "$dest");
    } else {
        // Open the source directory to read in files
        $i = new \DirectoryIterator($src);
        foreach ($i as $f) {
            if ($f->isFile()) {
                if ($f->getFilename() != 'setup.php') {
                    rename($f->getRealPath(), "$dest/" . $f->getFilename());
                }
            } else if (!$f->isDot() && $f->isDir()) {
                rmove($f->getRealPath(), "$dest/$f");
                if ($f->getRealPath() && !is_dir($f->getRealPath())) {
                    unlink($f->getRealPath());
                }
            }
        }
    }

    deleteAllFolderContent($src);
    return true;
}

/**
 * @param $src
 * @param $path
 * @param $firstFolderName
 * @param $replaceInPath
 * @param bool $deleteZip
 * @return bool
 */
function unzipSkipFirstFolder($src, $path, $firstFolderName, $replaceInPath, $deleteZip = true)
{
    $tempFolder = $path . 'unzip';
    $pluginsName = getPluginsName();

    $zip = new \ZipArchive;
    $res = $zip->open($src);
    if ($res === TRUE) {
        $zip->extractTo($tempFolder);
        $directories = scandir($tempFolder . '/' . $firstFolderName);
        foreach ($directories as $directory) {
            if ($directory != '.' and $directory != '..') {

                if (is_dir($tempFolder . '/' . $firstFolderName . '/' . $directory)) {

                    if (!is_dir($replaceInPath . $directory) && in_array($directory, $pluginsName)) {
                        createFolder($replaceInPath . $directory);
                    }

                    if (is_dir($replaceInPath . $directory)) {
                        rmove($tempFolder . '/' . $firstFolderName . '/' . $directory, $replaceInPath . $directory);
                    }

                } elseif (is_file($tempFolder . '/' . $firstFolderName . '/' . $directory)) {

                    if (!is_file($replaceInPath . $directory)) {
                        createFile($replaceInPath . $directory);
                    }

                    rmove($tempFolder . '/' . $firstFolderName . '/' . $directory, $replaceInPath . $directory);
                }
            }
        }

        $zip->close();
    }
    deleteAllFolderContent($tempFolder);
    if ($deleteZip) {
        unlink($src);
    }

    return true;
}

/**
 * @param $src
 * @param $path
 * @param bool $deleteZip
 * @return bool
 */
function unzip($src, $path, $deleteZip = true)
{
    $zip = new \ZipArchive;
    if (true === $zip->open($src)) {
        $zip->extractTo($path);
        $zip->close();
    }

    if ($deleteZip) {
        unlink($src);
    }

    return true;
}

/**
 * Remove dir with all files
 * @param $dirPath
 */
function deleteAllFolderContent($dirPath)
{
    if (is_dir($dirPath)) {
        $objects = scandir($dirPath);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dirPath . "/" . $object) == "dir") {
                    deleteAllFolderContent($dirPath . "/" . $object);
                } else {
                    unlink($dirPath . "/" . $object);
                }
            }
        }
        reset($objects);
        rmdir($dirPath);
    } elseif (is_file($dirPath)) {
        unlink($dirPath);
    }
}

/**
 * @return array
 */
function getAppTypes()
{
    //get plugin types
    $allTypes = array_map('strtoupper',
        array_flip(
            array_map('strtoupper',
                extractFromObjToSimpleArr(
                    json_decode(
                        json_encode(
                            getPlugins()), FALSE), 'name'))));

    //get app types
    $basesCategory = array();
    foreach (CATEGORY_TYPES as $key => $name) {
        $name = strtoupper($name);
        $basesCategory[$name] = $name;
    }

    return array_merge($allTypes, $basesCategory);
}

/**
 * @return array
 */
function listPays()
{
    return array
    (
        "ZA" => "Afrique du Sud",
        "AL" => "Albanie",
        "DZ" => "Algérie",
        "DE" => "Allemagne",
        "AD" => "Andorre",
        "AO" => "Angola",
        "AI" => "Anguilla",
        "AG" => "Antigua-et-Barbuda",
        "AN" => "Antilles néerlandaises",
        "SA" => "Arabie saoudite",
        "AR" => "Argentine",
        "AM" => "Arménie",
        "AW" => "Aruba",
        "AU" => "Australie",
        "AT" => "Autriche",
        "AZ" => "Azerbaïdjan",
        "BS" => "Bahamas",
        "BH" => "Bahreïn",
        "BB" => "Barbade",
        "BE" => "Belgique",
        "BZ" => "Belize",
        "BJ" => "Bénin",
        "BM" => "Bermudes",
        "BT" => "Bhoutan",
        "BY" => "Biélorussie",
        "BO" => "Bolivie",
        "BA" => "Bosnie-Herzégovine",
        "BW" => "Botswana",
        "BR" => "Brésil",
        "BN" => "Brunéi Darussalam",
        "BG" => "Bulgarie",
        "BF" => "Burkina Faso",
        "BI" => "Burundi",
        "KH" => "Cambodge",
        "CM" => "Cameroun",
        "CA" => "Canada",
        "CV" => "Cap-Vert",
        "CL" => "Chili",
        "C2" => "Chine",
        "CY" => "Chypre",
        "CO" => "Colombie",
        "KM" => "Comores",
        "CG" => "Congo-Brazzaville",
        "CD" => "Congo-Kinshasa",
        "KR" => "Corée du Sud",
        "CR" => "Costa Rica",
        "CI" => "Côte d’Ivoire",
        "HR" => "Croatie",
        "DK" => "Danemark",
        "DJ" => "Djibouti",
        "DM" => "Dominique",
        "EG" => "Égypte",
        "SV" => "El Salvador",
        "AE" => "Émirats arabes unis",
        "EC" => "Équateur",
        "ER" => "Érythrée",
        "ES" => "Espagne",
        "EE" => "Estonie",
        "VA" => "État de la Cité du Vatican",
        "FM" => "États fédérés de Micronésie",
        "US" => "États-Unis",
        "ET" => "Éthiopie",
        "FJ" => "Fidji",
        "FI" => "Finlande",
        "FR" => "France",
        "GA" => "Gabon",
        "GM" => "Gambie",
        "GE" => "Géorgie",
        "GI" => "Gibraltar",
        "GR" => "Grèce",
        "GD" => "Grenade",
        "GL" => "Groenland",
        "GP" => "Guadeloupe",
        "GT" => "Guatemala",
        "GN" => "Guinée",
        "GW" => "Guinée-Bissau",
        "GY" => "Guyana",
        "GF" => "Guyane française",
        "HN" => "Honduras",
        "HU" => "Hongrie",
        "NF" => "Île Norfolk",
        "KY" => "Îles Caïmans",
        "CK" => "Îles Cook",
        "FO" => "Îles Féroé",
        "FK" => "Îles Malouines",
        "MH" => "Îles Marshall",
        "PN" => "Îles Pitcairn",
        "SB" => "Îles Salomon",
        "TC" => "Îles Turques-et-Caïques",
        "VG" => "Îles Vierges britanniques",
        "IN" => "Inde",
        "ID" => "Indonésie",
        "IE" => "Irlande",
        "IS" => "Islande",
        "IL" => "Israël",
        "IT" => "Italie",
        "JM" => "Jamaïque",
        "JP" => "Japon",
        "JO" => "Jordanie",
        "KZ" => "Kazakhstan",
        "KE" => "Kenya",
        "KG" => "Kirghizistan",
        "KI" => "Kiribati",
        "KW" => "Koweït",
        "RE" => "La Réunion",
        "LA" => "Laos",
        "LS" => "Lesotho",
        "LV" => "Lettonie",
        "LI" => "Liechtenstein",
        "LT" => "Lituanie",
        "LU" => "Luxembourg",
        "LY" => "Libye",
        "MK" => "Macédoine",
        "MG" => "Madagascar",
        "MY" => "Malaisie",
        "MW" => "Malawi",
        "MV" => "Maldives",
        "ML" => "Mali",
        "MT" => "Malte",
        "MA" => "Maroc",
        "MQ" => "Martinique",
        "MU" => "Maurice",
        "MR" => "Mauritanie",
        "YT" => "Mayotte",
        "MX" => "Mexique",
        "MD" => "Moldavie",
        "MC" => "Monaco",
        "MN" => "Mongolie",
        "ME" => "Monténégro",
        "MS" => "Montserrat",
        "MZ" => "Mozambique",
        "NA" => "Namibie",
        "NR" => "Nauru",
        "NP" => "Népal",
        "NI" => "Nicaragua",
        "NE" => "Niger",
        "NG" => "Nigéria",
        "NU" => "Niue",
        "NO" => "Norvège",
        "NC" => "Nouvelle-Calédonie",
        "NZ" => "Nouvelle-Zélande",
        "OM" => "Oman",
        "UG" => "Ouganda",
        "PW" => "Palaos",
        "PA" => "Panama",
        "PG" => "Papouasie-Nouvelle-Guinée",
        "PY" => "Paraguay",
        "NL" => "Pays-Bas",
        "PE" => "Pérou",
        "PH" => "Philippines",
        "PL" => "Pologne",
        "PF" => "Polynésie française",
        "PT" => "Portugal",
        "QA" => "Qatar",
        "HK" => "R.A.S. chinoise de Hong Kong",
        "DO" => "République dominicaine",
        "CZ" => "République tchèque",
        "RO" => "Roumanie",
        "GB" => "Royaume-Uni",
        "RU" => "Russie",
        "RW" => "Rwanda",
        "KN" => "Saint-Christophe-et-Niévès",
        "SM" => "Saint-Marin",
        "PM" => "Saint-Pierre-et-Miquelon",
        "VC" => "Saint-Vincent-et-les-Grenadines",
        "SH" => "Sainte-Hélène",
        "LC" => "Sainte-Lucie",
        "WS" => "Samoa",
        "ST" => "Sao Tomé-et-Principe",
        "SN" => "Sénégal",
        "RS" => "Serbie",
        "SC" => "Seychelles",
        "SL" => "Sierra Leone",
        "SG" => "Singapour",
        "SK" => "Slovaquie",
        "SI" => "Slovénie",
        "SO" => "Somalie",
        "LK" => "Sri Lanka",
        "SE" => "Suède",
        "CH" => "Suisse",
        "SR" => "Suriname",
        "SJ" => "Svalbard et Jan Mayen",
        "SZ" => "Swaziland",
        "TJ" => "Tadjikistan",
        "TW" => "Taïwan",
        "TZ" => "Tanzanie",
        "TD" => "Tchad",
        "TH" => "Thaïlande",
        "TG" => "Togo",
        "TO" => "Tonga",
        "TT" => "Trinité-et-Tobago",
        "TN" => "Tunisie",
        "TM" => "Turkménistan",
        "TR" => "Turquie",
        "TV" => "Tuvalu",
        "UA" => "Ukraine",
        "UY" => "Uruguay",
        "VU" => "Vanuatu",
        "VE" => "Venezuela",
        "VN" => "Vietnam",
        "WF" => "Wallis-et-Futuna",
        "YE" => "Yémen",
        "ZM" => "Zambie",
        "ZW" => "Zimbabwe"
    );
}

/**
 * @param $iso
 *
 * @return mixed|string
 */
function getPaysName($iso)
{
    $countries = listPays();
    if (array_key_exists($iso, $countries)) {
        return $countries[$iso];
    } else {
        return 'Pays inconnu';
    }
}

/**
 * @param $paysName
 *
 * @return mixed|string
 */
function getIso($paysName)
{
    $countries = listPays();
    if (in_array($paysName, $countries)) {
        return array_search($paysName, $countries);
    } else {
        return 'Pays inconnu';
    }
}

/**
 * set new token
 */
function setToken()
{
    $string = "";
    $chaine = "a0b1c2d3e4f5g6h7i8j9klmnpqrstuvwxy123456789";
    srand((double)microtime() * 1000000);
    for ($i = 0; $i < 70; $i++) {
        $string .= $chaine[rand() % strlen($chaine)];
    }
    $_SESSION['_token'] = !bot_detected() ? $string : 'a1b2c3-d4e5f6';
}

/**
 * @return string
 */
function getTokenField()
{
    if (!isset($_SESSION['_token'])) {
        setToken();
    }

    return '<input type="hidden" name="_token" value="' . getToken() . '">';
}

/**
 * @return mixed
 */
function getToken()
{
    return $_SESSION['_token'];
}

/**
 * remove token session
 */
function unsetToken()
{
    if (isset($_SESSION['_token'])) {
        unset($_SESSION['_token']);
    }
}

/**
 * display notifications & alerts
 */
function getSessionNotifications()
{
    $sessionsNotifs = array(
        'notifications',
        'alert'
    );
    foreach ($sessionsNotifs as $sessionNotif) {
        if (!empty($_SESSION[$sessionNotif])) {
            foreach ($_SESSION[$sessionNotif] as $notif) {
                if (!empty($notif['alert'])) {
                    echo $notif['alert'];
                }
            }
        }
    }
}

/**
 * @param $assetName
 * @return bool|false|string
 */
function getAsset($assetName)
{
    if (file_exists(WEB_LIB_PATH . 'assets/' . $assetName . '.php')) {
        include_once(WEB_LIB_PATH . 'assets/' . $assetName . '.php');
    }

    return false;
}


/**
 * @param $filename
 * @param int $desired_width
 * @param int $quality
 * @return bool|int
 */
function thumb($filename, $desired_width = 100, $quality = 80)
{
    $src = FILE_DIR_PATH . $filename;
    $dest = FILE_DIR_PATH . 'thumb' . DIRECTORY_SEPARATOR . $desired_width . '_' . $filename;


    if (!file_exists(FILE_DIR_PATH . 'thumb/')) {
        mkdir(FILE_DIR_PATH . 'thumb', 0705);
    }

    if (is_file($src) && !is_file($dest) && isImage($src)) {

        list($src_width, $src_height, $src_type, $src_attr) = getimagesize($src);

        //check if thumb can be realized
        if ($desired_width < $src_width) {

            // Find format
            $ext = strtoupper(pathinfo($src, PATHINFO_EXTENSION));
            $source_image = '';

            /* read the source image */
            if ($ext == "JPG" OR $ext == "JPEG") {
                $source_image = imagecreatefromjpeg($src);
            } elseif ($ext == "PNG") {
                $source_image = ImageCreateFromPNG($src);
            } elseif ($ext == "GIF") {
                $source_image = ImageCreateFromGIF($src);
            }

            $width = imagesx($source_image);
            $height = imagesy($source_image);


            /* find the "desired height" of this thumbnail, relative to the desired width  */
            $desired_height = floor($height * ($desired_width / $width));

            /* create a new, "virtual" image */
            $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

            /* copy source image at a resized size */
            imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

            /* create the physical thumbnail image to its destination */
            if ($ext == "JPG" OR $ext == "JPEG") {
                imagejpeg($virtual_image, $dest, $quality);
            } elseif ($ext == "PNG") {
                imagePNG($virtual_image, $dest);
            } elseif ($ext == "GIF") {
                imageGIF($virtual_image, $dest);
            }

            return true;
        }
    }
    return false;
}

/**
 * @param $filename
 * @param $desired_width
 * @param $quality
 * @return string
 */
function getThumb($filename, $desired_width, $quality = 100)
{
    //Check if file exist
    if (is_file(FILE_DIR_PATH . 'thumb' . DIRECTORY_SEPARATOR . $desired_width . '_' . $filename)) {
        return WEB_DIR_INCLUDE . 'thumb' . DIRECTORY_SEPARATOR . $desired_width . '_' . $filename;
    } else {

        //Create thumb
        $thumb = thumb($filename, $desired_width, $quality);
        return $thumb ? getThumb($filename, $desired_width) : WEB_DIR_INCLUDE . $filename;
    }
}

/**
 * @param $filename
 * @param $desired_width
 * @return bool
 */
function deleteThumb($filename, $desired_width)
{
    $thumbPath = FILE_DIR_PATH . 'thumb' . DIRECTORY_SEPARATOR . $desired_width . '_' . $filename;
    if (file_exists($thumbPath)) {
        if (unlink($thumbPath)) {
            return true;
        }
    }
    return false;
}

/**
 * @param $url
 * @param array $params
 * @return mixed|string
 */
function postHttpRequest($url, array $params)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSLVERSION, 6);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, "TLSv1");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $errorRequest = curl_error($ch);

    curl_close($ch);

    return !empty($response) ? $response : $errorRequest;
}

/**
 * @param $path
 * @param $activeTraduction
 * @return string
 */
function getFileContent($path, $activeTraduction = true)
{
    ob_start();

    if ($activeTraduction && class_exists('App\Plugin\Traduction\Traduction')) {
        $Traduction = new \App\Plugin\Traduction\Traduction(APP_LANG);
    }

    if (file_exists($path)) {
        include $path;
    }
    $pageContent = ob_get_clean();

    return $pageContent;
}


/**
 * @param $object
 * @param $attribute
 * @return bool|string
 */
function getXmlAttribute($object, $attribute)
{
    if (isset($object[$attribute])) {
        return (string)$object[$attribute];
    }

    return false;
}

/**
 * Place fields for verification and control of the login form
 *
 * @return string
 */
function getFieldsControls()
{
    $html = '';

    $html .= getTokenField();
    $html .= '<noscript><input type="hidden" name="secure-connection" value="..."></noscript>';
    $html .= '<input type="hidden" name="identifiant" value="">';
    $html .= '<input type="hidden" id="checkPass" name="checkPass" value="">';
    $html .= '<script type="text/javascript">';
    $html .= 'window.setTimeout(function(){';
    $html .= 'document.getElementById("checkPass").value = "APPOE";';
    $html .= '}, 1000)</script>';

    return $html;
}

/**
 * @return array|bool
 */
function getMediaCategories()
{

    $Category = new \App\Category();
    $Category->setType('MEDIA');
    $allCategories = $Category->showByType();
    return $allCategories ? extractFromObjToSimpleArr($allCategories, 'id', 'name') : false;
}

/**
 * Deprecated
 * @param int $id
 * @param bool $parentId
 * @param string $categoryType
 * @return array
 */
function getSpecificMediaCategory($id, $parentId = false, $categoryType = 'MEDIA')
{
    $Media = new \App\Media();
    $Category = new \App\Category();

    $Category->setType($categoryType);
    $allCategories = $Category->showByType();

    $allMedia = array();
    foreach ($allCategories as $category) {

        if (false !== $parentId) {

            if ($category->parentId != $id && $category->id != $id) {
                continue;
            }

        } else {
            if ($category->id != $id) {
                continue;
            }
        }

        $Media->setTypeId($category->id);
        $allMedia[$category->id] = $Media->showFiles();
    }

    return $allMedia;
}

/**
 * @param int $id
 * @param bool $parentId
 * @param string $type
 * @return array
 */
function getMediaByCategory($id, $parentId = false, $type = 'MEDIA')
{
    $Media = new \App\Media();
    $Media->setType($type);

    $Category = new \App\Category();
    $Category->setType($type);
    $allCategories = $Category->showByType();

    $allMedia = array();
    foreach ($allCategories as $category) {

        $Media->setTypeId($category->id);

        if (false !== $parentId) {

            if ($category->parentId != $id && $category->id != $id) {
                continue;
            }
            $allMedia[$category->id] = $Media->showFiles();

        } else {
            if ($category->id != $id) {
                continue;
            }
            $allMedia = array_merge($allMedia, $Media->showFiles());
        }
    }

    return $allMedia;
}

/**
 * @param int|float $amount
 * @param bool $forDB
 * @param int $decimals
 * @param string $dec_point
 * @return string
 */
function financial($amount, $forDB = false, $decimals = 2, $dec_point = '.')
{
    return is_numeric($amount) ? number_format($amount, $decimals, $dec_point, (!$forDB ? ' ' : '')) : $amount;
}

/**
 * Raise an grandchildren array, at the level of children
 *
 * @param array|null $multipleArrays
 * @return array
 */
function transformMultipleArraysTo1(array $multipleArrays = null)
{
    foreach ($multipleArrays as $ckey => $child) {
        foreach ($child as $gckey => $grandchild) {
            if (is_array($grandchild)) {
                $multipleArrays[] = $grandchild;
                array_splice($multipleArrays[$ckey], $gckey);
            }
        }
    }
    $length = count($multipleArrays);
    for ($i = 0; $i < $length; $i++) {
        if (!empty($multipleArrays[$i][0]) && is_array($multipleArrays[$i][0])) {
            array_splice($multipleArrays, $i, 1);
            $i = -1;
            $length = count($multipleArrays);
        }
    }
    return $multipleArrays;
}

/**
 * Raise an children array, at the top level
 *
 * @param array| $array
 * @return array
 */
function flatten(array $array)
{
    $return = array();
    array_walk_recursive($array, function ($a) use (&$return) {
        $return[] = $a;
    });
    return array_unique($return);
}

/**
 * @param array $data
 * @param string $keyName
 * @return array
 */
function groupMultipleKeysObjectsArray(array $data, $keyName)
{
    $tmp = array();
    foreach ($data as $key => $arg) {
        $tmp[$arg->$keyName][$key] = $arg;
    }

    $output = array();
    foreach ($tmp as $type => $labels) {
        $output[cleanData($type)] = $labels;
    }

    return $output;
}

/**
 * @param array $data
 * @param string $keyName
 * @return array
 */
function groupMultipleKeysArray(array $data, $keyName)
{
    $tmp = array();
    foreach ($data as $key => $arg) {
        $tmp[$arg[$keyName]][$key] = $arg;
    }

    $output = array();
    foreach ($tmp as $type => $labels) {
        $output[cleanData($type)] = $labels;
    }

    return $output;
}

/**
 * @param $allContentArr
 * @param $key
 * @return array
 */
function extractFromObjArr($allContentArr, $key)
{
    $allContent = array();
    if (!empty($allContentArr)) {
        foreach ($allContentArr as $contentArr) {
            $allContent[$contentArr->$key] = $contentArr;
        }
    }
    return $allContent;
}

/**
 * @param $allContentArr
 * @param $key
 * @return array
 */
function extractFromObjToArrForList($allContentArr, $key)
{
    //extract object to array with key = id
    $newArray = extractFromObjArr($allContentArr, $key);

    //build tree id [parent id]
    $newArray = buildTree($newArray, 10);

    //order the list
    $ordonnedList = array();

    foreach ($newArray as $category) {
        $ordonnedList[$category->id] = $category->name;

        if (!empty($category->children)) {
            foreach ($category->children as $children) {
                $ordonnedList[$children->id] = '- ' . $children->name;

                if (!empty($children->children)) {

                    foreach ($children->children as $subChildren) {
                        $ordonnedList[$subChildren->id] = '-- ' . $subChildren->name;
                    }
                }
            }
        }
    }

    return $ordonnedList;
}

/**
 * @param array $allContentArr
 * @param $key
 * @param string $value
 * @param string $value2
 * @param string $separator
 * @return array
 */
function extractFromObjToSimpleArr(array $allContentArr, $key, $value = '', $value2 = '', $separator = ' ')
{
    $allContent = array();

    if ($allContentArr && !isArrayEmpty($allContentArr)) {

        if (!empty($value)) {

            foreach ($allContentArr as $contentArr) {

                if (!empty($value2)) {
                    $allContent[$contentArr->$key] = $contentArr->$value . $separator . $contentArr->$value2;
                } else {
                    $allContent[$contentArr->$key] = $contentArr->$value;
                }
            }

        } else {

            foreach ($allContentArr as $contentArr) {
                $allContent[$contentArr->$key] = $contentArr->$key;
            }
        }
    }

    return $allContent;
}

/**
 * @param array $elements
 * @param int $parentId
 * @return array
 */
function buildTree(array $elements, $parentId = 0)
{

    $branch = array();

    foreach ($elements as $element) {
        if ($element->parentId == $parentId) {
            $children = buildTree($elements, $element->id);

            if ($children) {
                $element->children = $children;
            }

            $branch[] = $element;
        }
    }

    return $branch;
}

/**
 * @param $dbname
 * @param string $groupBy
 * @param int $limit
 * @param string $column
 * @param string $order
 * @return array|bool
 */
function getLastFromDb($dbname, $groupBy = '', $limit = 2, $column = 'updated_at', $order = 'DESC')
{

    $dbh = \App\DB::connect();

    $sql = 'SELECT * FROM appoe_' . $dbname . ' ORDER BY ' . $column . ' ' . $order;
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $error = $stmt->errorInfo();

    if ($error[0] != '00000') {
        return false;
    }

    return
        empty($groupBy)
            ? $stmt->fetchAll(PDO::FETCH_OBJ)
            : array_slice(array_unique(extractFromObjToSimpleArr($stmt->fetchAll(PDO::FETCH_OBJ), $groupBy)), 0, $limit);
}

/**
 *
 */
function includePluginsDashboard()
{
    $dashboardDetails = array();
    $plugins = getPlugins();

    if (is_array($plugins) && !empty($plugins)) {

        foreach ($plugins as $plugin) {

            $filePath = $plugin['pluginPath'] . 'dashboard.php';
            if (file_exists($filePath)) {

                $dashboard = getFileContent($filePath);
                if ($dashboard && !isArrayEmpty($dashboard)) {
                    $dashboardDetails[] = json_decode($dashboard, true);
                }
            }

        }
    }

    return $dashboardDetails;
}

/**
 *
 */
function includePersoPluginsDashboard()
{
    $dashboardDetails = array();
    $plugins = getPlugins();

    if (is_array($plugins) && !empty($plugins)) {

        foreach ($plugins as $plugin) {

            $filePath = $plugin['pluginPath'] . 'perso_dashboard.php';
            if (file_exists($filePath)) {

                $dashboard = getFileContent($filePath);
                if (!empty($dashboard)) {
                    $dashboardDetails[] = $dashboard;
                }
            }

        }
    }

    return $dashboardDetails;
}

/**
 * @param $forApp
 */
function includePluginsFiles($forApp = false)
{
    $plugins = getPlugins();

    if (is_array($plugins) && !empty($plugins)) {

        foreach ($plugins as $plugin) {
            $filePath = WEB_PLUGIN_PATH . $plugin['name'] . DIRECTORY_SEPARATOR . 'include';
            if (file_exists($filePath)) {
                $phpFiles = getFilesFromDir($filePath);
                foreach ($phpFiles as $file) {
                    $src = $filePath . DIRECTORY_SEPARATOR . $file;
                    include_once($src);
                }
            }
        }
    }

    if ($forApp) {
        includePluginsFilesForApp();
    }
}


/**
 *
 */
function includePluginsFilesForApp()
{
    $plugins = getPlugins();

    if (is_array($plugins) && !empty($plugins)) {

        foreach ($plugins as $plugin) {
            $filePath = WEB_PLUGIN_PATH . $plugin['name'] . DIRECTORY_SEPARATOR . 'includeApp';
            if (file_exists($filePath)) {
                $phpFiles = getFilesFromDir($filePath);

                foreach ($phpFiles as $file) {
                    $src = $filePath . DIRECTORY_SEPARATOR . $file;
                    include_once($src);
                }
            }
        }
    }
}

/**
 *
 */
function includePluginsFilesForAppInFooter()
{
    $plugins = getPlugins();

    if (is_array($plugins) && !empty($plugins)) {

        foreach ($plugins as $plugin) {
            $filePath = WEB_PLUGIN_PATH . $plugin['name'] . DIRECTORY_SEPARATOR . 'includeFooter';
            if (file_exists($filePath)) {
                $phpFiles = getFilesFromDir($filePath);

                foreach ($phpFiles as $file) {
                    $src = $filePath . DIRECTORY_SEPARATOR . $file;
                    include_once($src);
                }
            }
        }
    }
}


/**
 *
 */
function includePluginsPrimaryMenu()
{
    $plugins = getPlugins();

    if (is_array($plugins) && !empty($plugins)) {

        foreach ($plugins as $plugin) {
            $filePath = WEB_PLUGIN_PATH . $plugin['name'] . DIRECTORY_SEPARATOR . 'menu';
            if (file_exists($filePath)) {
                $phpFiles = getFilesFromDir($filePath);
                foreach ($phpFiles as $file) {
                    $src = $filePath . DIRECTORY_SEPARATOR . $file;
                    include_once($src);
                }
            }
        }
    }
}


/**
 *
 */
function includePluginsSecondaryMenu()
{
    $plugins = getPlugins();

    if (is_array($plugins) && !empty($plugins)) {

        foreach ($plugins as $plugin) {
            $filePath = WEB_PLUGIN_PATH . $plugin['name'] . DIRECTORY_SEPARATOR . 'littleMenu';
            if (file_exists($filePath)) {
                $phpFiles = getFilesFromDir($filePath);
                foreach ($phpFiles as $file) {
                    $src = $filePath . DIRECTORY_SEPARATOR . $file;
                    include_once($src);
                }
            }
        }
    }
}


/**
 * @param $forApp
 */
function includePluginsJs($forApp = false)
{
    $plugins = getPlugins();

    if (is_array($plugins) && !empty($plugins)) {

        foreach ($plugins as $plugin) {
            $filePath = WEB_PLUGIN_PATH . $plugin['name'] . DIRECTORY_SEPARATOR . 'js';
            if (file_exists($filePath)) {
                $phpFiles = getFilesFromDir($filePath);
                foreach ($phpFiles as $file) {
                    $src = WEB_PLUGIN_URL . $plugin['name'] . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . $file;
                    echo '<script type="text/javascript" src="' . $src . '"></script>';

                }
            }
        }
    }

    echo '<script type="text/javascript" src="' . WEB_LIB_URL . 'js/functions.js"></script>';

    if ($forApp) {
        includePluginsJsForApp();
    }
}

/**
 *
 */
function includePluginsJsForApp()
{
    $plugins = getPlugins();

    if (is_array($plugins) && !empty($plugins)) {

        foreach ($plugins as $plugin) {
            $filePath = WEB_PLUGIN_PATH . $plugin['name'] . DIRECTORY_SEPARATOR . 'jsApp';
            if (file_exists($filePath)) {
                $phpFiles = getFilesFromDir($filePath);
                foreach ($phpFiles as $file) {
                    $src = WEB_PLUGIN_URL . $plugin['name'] . DIRECTORY_SEPARATOR . 'jsApp' . DIRECTORY_SEPARATOR . $file;
                    echo '<script type="text/javascript" src="' . $src . '"></script>';

                }
            }
        }
    }

    echo '<script type="text/javascript" src="' . WEB_TEMPLATE_URL . 'js/all.js"></script>';
}


/**
 *
 */
function includePluginsStyles()
{
    $plugins = getPlugins();

    if (is_array($plugins) && !empty($plugins)) {

        foreach ($plugins as $plugin) {
            $filePath = WEB_PLUGIN_PATH . $plugin['name'] . DIRECTORY_SEPARATOR . 'css';
            if (file_exists($filePath)) {
                $phpFiles = getFilesFromDir($filePath);
                foreach ($phpFiles as $file) {
                    $src = WEB_PLUGIN_URL . $plugin['name'] . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . $file;
                    echo '<link rel="stylesheet" href="' . $src . '" type="text/css">';
                }
            }
        }
    }
}

/**
 * @return bool
 */
function valideToken()
{
    if (!empty($_REQUEST['_token']) && !empty($_SESSION['_token']) && $_REQUEST['_token'] == $_SESSION['_token']) {

        unsetToken();
        return true;
    }
    return false;
}

/**
 * @return bool
 */
function valideAjaxToken()
{
    if (!empty($_REQUEST['_token']) && !empty($_SESSION['_token']) && $_REQUEST['_token'] == $_SESSION['_token']) {

        return true;
    }
    return false;
}

/**
 * @param $updateUserStatus
 * @return bool
 */
function checkPostAndTokenRequest($updateUserStatus = true)
{
    if (!empty($_POST['_token']) && !empty($_SESSION['_token']) && $_POST['_token'] == $_SESSION['_token']) {

        unsetToken();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($updateUserStatus) {
                if (function_exists('mehoubarim_connecteUser')) {
                    mehoubarim_connecteUser();
                }
            }

            return true;
        }
    }

    return false;
}

/**
 * @return bool
 */
function checkAjaxRequest()
{

    $page_slug = pageSlug();
    $excludeSlug = array(
        'notifications',
        'messagesMenu'
    );

    if (!in_array($page_slug, $excludeSlug)) {
        if (function_exists('mehoubarim_connecteUser')) {
            mehoubarim_connecteUser();
        }
    }

    if (
        !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
    ) {
        return true;
    }

    return false;
}

/**
 * Check if user are connected & return user id
 *
 * @return bool
 */
function getUserIdSession()
{
    $userConnexion = getUserConnexion();
    return $userConnexion ? $userConnexion['idUserConnexion'] : false;
}

/**
 * @param $slug
 * @return bool
 */
function isUserAuthorized($slug)
{

    $Menu = new \App\Menu();
    return $Menu->checkUserPermission(getUserRoleId(), $slug);
}

/**
 * @param string $idUser
 * @return bool|string
 */
function checkAndGetUserId($idUser = null)
{
    return $idUser ? $idUser : getUserIdSession();
}

/**
 * @return mixed
 */
function getAllUsers()
{
    return unserialize(ALLUSERS);
}

/**
 * @param $idUser
 * @return bool
 */
function isUserExist($idUser)
{

    $ALLUSERS = getAllUsers();

    if (is_array($ALLUSERS) && is_numeric($idUser)) {
        return array_key_exists($idUser, $ALLUSERS);
    }
    return false;
}

/**
 * @param $idUser
 * @return string|array
 */
function getUserData($idUser = null)
{
    $idUser = checkAndGetUserId($idUser);

    $ALLUSERS = getAllUsers();
    return isUserExist($idUser) ? $ALLUSERS[$idUser] : '';
}

/**
 * @param $idUser
 * @return string
 */
function getUserName($idUser = null)
{
    $idUser = checkAndGetUserId($idUser);

    $ALLUSERS = getAllUsers();
    return isUserExist($idUser) ? $ALLUSERS[$idUser]->nom : '';
}

/**
 * @param $idUser
 * @return string
 */
function getUserFirstName($idUser = null)
{
    $idUser = checkAndGetUserId($idUser);

    $ALLUSERS = getAllUsers();
    return isUserExist($idUser) ? $ALLUSERS[$idUser]->prenom : '';
}

/**
 * @param $idUser
 * @param string $separator
 * @return string
 */
function getUserEntitled($idUser = null, $separator = ' ')
{
    $idUser = checkAndGetUserId($idUser);

    $ALLUSERS = getAllUsers();
    return isUserExist($idUser) ? $ALLUSERS[$idUser]->nom . $separator . $ALLUSERS[$idUser]->prenom : '';
}

/**
 * @param $idUser
 * @return array|string
 */
function getUserLogin($idUser = null)
{
    $idUser = checkAndGetUserId($idUser);

    $ALLUSERS = getAllUsers();
    return isUserExist($idUser) ? $ALLUSERS[$idUser]->login : '';
}

/**
 * @param $idUser
 * @return array|string
 */
function getUserEmail($idUser = null)
{
    $idUser = checkAndGetUserId($idUser);

    $ALLUSERS = getAllUsers();
    return isUserExist($idUser) ? $ALLUSERS[$idUser]->email : '';
}

/**
 * @param $idUser
 * @return array|bool
 */
function getUserStatus($idUser = null)
{
    $idUser = checkAndGetUserId($idUser);

    $ALLUSERS = getAllUsers();
    return isUserExist($idUser) ? $ALLUSERS[$idUser]->statut : false;
}

/**
 * @param string $idUser
 * @return string
 */
function getUserRoleId($idUser = null)
{

    $idUser = checkAndGetUserId($idUser);

    $ALLUSERS = getAllUsers();
    return isUserExist($idUser) ? getRoleId($ALLUSERS[$idUser]->role) : false;
}

/**
 * @param string $idUser
 * @return mixed
 */
function getUserRoleName($idUser = null)
{

    $idUser = checkAndGetUserId($idUser);

    $ALLUSERS = getAllUsers();
    return isUserExist($idUser) ? getRoleName($ALLUSERS[$idUser]->role) : '';
}

/**
 * @return array
 */
function getAdminRoles()
{
    return array(11 => 'Technicien', 12 => 'King');
}

/**
 * @return array
 */
function getRoles()
{
    $usersRoles = getAdminRoles();
    if (defined('ROLES')) {

        $usersRoles = $usersRoles + ROLES;
        ksort($usersRoles);
        return $usersRoles;
    }
    return $usersRoles;
}

/**
 * @param $roleId
 * @return mixed
 */
function getRoleName($roleId)
{
    if (defined('ROLES')) {
        $roleId = getRoleId($roleId);
        return getRoles()[$roleId];
    }
    return $roleId;
}

/**
 * @param $cryptedRole
 * @return string
 */
function getRoleId($cryptedRole)
{
    return strlen($cryptedRole) < 3 ? $cryptedRole : \App\Shinoui::Decrypter($cryptedRole);
}


/**
 * @return false|int|string
 */
function getTechnicienRoleId()
{
    return array_search('Technicien', getRoles());
}

/**
 * @param $roleId
 * @return bool
 */
function isTechnicien($roleId)
{

    $userRole = getRoleId($roleId);
    if ($userRole >= 11) {
        return true;
    }

    return false;
}

/**
 * @param $roleId
 * @return bool
 */
function isKing($roleId)
{

    $userRole = getRoleId($roleId);
    if ($userRole == 12) {
        return true;
    }

    return false;
}

/**
 * Unset User Session & Cookie
 */
function deconnecteUser()
{
    unset($_SESSION['auth' . slugify($_SERVER['HTTP_HOST'])]);

    setcookie('hibour' . slugify($_SERVER['HTTP_HOST']), '', -3600, '/', '', false, true);
    unset($_COOKIE['hibour' . slugify($_SERVER['HTTP_HOST'])]);
}

/**
 * @return bool|string
 */
function getUserSession()
{
    if (!empty($_SESSION['auth' . slugify($_SERVER['HTTP_HOST'])])) {
        return \App\Shinoui::Decrypter($_SESSION['auth' . slugify($_SERVER['HTTP_HOST'])]);
    }
    return false;
}

/**
 * @return bool|string
 */
function getUserCookie()
{
    if (!empty($_COOKIE['hibour' . slugify($_SERVER['HTTP_HOST'])])) {
        return \App\Shinoui::Decrypter($_COOKIE['hibour' . slugify($_SERVER['HTTP_HOST'])]);
    }
    return false;
}

/**
 * Set user Session
 */
function setUserSession()
{
    $_SESSION['auth' . slugify($_SERVER['HTTP_HOST'])] = $_COOKIE['hibour' . slugify($_SERVER['HTTP_HOST'])];
}

/**
 * @return bool
 */
function isUserSessionExist()
{
    return isset($_SESSION['auth' . slugify($_SERVER['HTTP_HOST'])]);
}

/**
 * @return bool
 */
function isUserCookieExist()
{
    return isset($_COOKIE['hibour' . slugify($_SERVER['HTTP_HOST'])]);
}

/**
 * @return array|bool
 */
function getUserConnexion()
{

    $checkStr = '!a6fgcb!f152ddb3!';
    $pos = false;

    if (isUserSessionExist()) {

        $pos = strpos(getUserSession(), $checkStr);
        list($idUserConnexion, $loginUserConnexion) = explode($checkStr, getUserSession());

    } elseif (isUserCookieExist()) {

        $pos = strpos(getUserCookie(), $checkStr);
        setUserSession();
        list($idUserConnexion, $loginUserConnexion) = explode($checkStr, getUserSession());
    }

    return $pos !== false ? array('idUserConnexion' => $idUserConnexion, 'loginUserConnexion' => $loginUserConnexion) : false;
}

/**
 * get real file path
 *
 * @param $chemin
 * @param array $ext
 *
 * @return array|bool|mixed
 */
function getPathFiles($chemin, $ext = array())
{
    if (!empty($chemin) && is_array($ext)) {
        $files = glob($chemin . '*.{' . implode(',', $ext) . '}', GLOB_BRACE);

        return array_map('realpath', $files);
    } else {
        return false;
    }
}

/**
 * Try to detect bots
 * @return bool
 */
function bot_detected()
{
    return (
        isset($_SERVER['HTTP_USER_AGENT'])
        && preg_match('/bot|crawl|curl|dataprovider|search|get|spider|find|java|majesticsEO|google|yahoo
        |contaxe|libwww-perl|facebookexternalhit|mediapartners|baidu|bingbot|facebookexternalhit|googlebot|-google
        |ia_archiver|msnbot|naverbot|pingdom|seznambot|slurp|teoma|twitter|yandex|yeti/i', $_SERVER['HTTP_USER_AGENT'])
    );
}

/**
 * @param $url
 *
 * @return bool
 */
function url_exists($url)
{
    return (!$fp = curl_init($url)) ? false : true;
}

/**
 * get real web file url
 *
 * @param $file
 * @param null $param
 *
 * @return string
 */
function webUrl($file, $param = null)
{
    $url = '';
    if (!is_null($param)) {
        $url .= $param;
    }

    if (substr($file, 0, 4) === "http") {
        return $file;
    }
    return WEB_DIR_URL . $file . $url;
}

/**
 * get article web url
 *
 * @param $articleSlug
 * @param $articlePage
 *
 * @return string
 */
function articleUrl($articleSlug, $articlePage = '')
{
    $articlePage = !empty($articlePage) ? $articlePage :
        (defined('DEFAULT_ARTICLES_PAGE') ? DEFAULT_ARTICLES_PAGE . DIRECTORY_SEPARATOR : '/');
    return webUrl($articlePage, $articleSlug);
}

/**
 * @param $link
 * @return string
 */
function externalLink($link)
{
    if (substr($link, 0, 4) === "http") {
        return ' target="_blank" ';
    }
    return '';
}

/**
 * get real admin file url
 *
 * @param $file
 * @param null $param
 *
 * @return string
 */
function getUrl($file, $param = null)
{
    $url = '';
    if (!is_null($param)) {
        $url .= $param . '/';
    }

    return WEB_ADMIN_URL . $file . $url;
}

/**
 * @param $file
 * @param null $param
 *
 * @return string
 */
function getPluginUrl($file, $param = null)
{
    $url = '';
    if (!is_null($param)) {
        $url .= $param . '/';
    }

    return WEB_PLUGIN_URL . $file . $url;
}

/**
 * @param $type
 * @return bool|string
 */
function getPageTypes($type)
{
    if (in_array(mb_strtolower($type), array_keys(PAGE_TYPES))) {
        return PAGE_TYPES[mb_strtolower($type)];
    }

    return false;
}

/**
 * @param $path
 *
 * @return mixed
 */
function getFileName($path)
{
    $pathInfos = pathinfo($path);

    return $pathInfos['filename'];
}

/**
 * @param $fileOptions
 * @param string $key
 * @return array|mixed
 */
function getSerializedOptions($fileOptions, $key = '')
{
    $arrayOptions = array();
    if (!empty($fileOptions)) {

        $arrayOptions = @unserialize($fileOptions);

        if ($arrayOptions && !isArrayEmpty($arrayOptions)) {

            if (!empty($key) && array_key_exists($key, $arrayOptions)) {
                return $arrayOptions[$key];
            }
        }
    }

    return $arrayOptions;
}

/**
 * @param $filesArray
 * @param $position
 * @param int|bool $forcedPosition
 * @return array
 */
function getFileTemplatePosition($filesArray, $position, $forcedPosition = false)
{

    $newFilesArray = array();
    if ($filesArray && !isArrayEmpty($filesArray)) {
        foreach ($filesArray as $key => $file) {
            if (is_object($file) && $position == getSerializedOptions($file->options, 'templatePosition')) {
                array_push($newFilesArray, $file);
            }
        }

        if (isArrayEmpty($newFilesArray)) {

            if (true === $forcedPosition) {
                $newFilesArray = $filesArray;
            }

            if ($forcedPosition > 0) {
                $forcedFilesArray = getFileTemplatePosition($filesArray, $forcedPosition);
                $newFilesArray = !isArrayEmpty($forcedFilesArray) ? $forcedFilesArray : $filesArray;
            }
        }
    }

    return $newFilesArray;
}

/**
 * @param $imageArray
 * @param $otherClass
 * @param $thumbSize
 * @param $onlyUrl
 * @param $onlyPath
 * @return bool|string
 */
function getFirstImage($imageArray, $otherClass = '', $thumbSize = false, $onlyUrl = false, $onlyPath = false)
{
    if ($imageArray) {
        $firstImage = current($imageArray);
        if (isImage(FILE_DIR_PATH . $firstImage->name)) {
            if ($onlyUrl) {
                return WEB_DIR_INCLUDE . $firstImage->name;
            } else if ($onlyPath) {
                return FILE_DIR_PATH . $firstImage->name;
            } else {
                return '<img src="' .
                    (
                    !$thumbSize
                        ? WEB_DIR_INCLUDE . $firstImage->name
                        : getThumb($firstImage->name, $thumbSize)
                    )
                    . '" alt="' . $firstImage->title . '" data-originsrc="' . WEB_DIR_INCLUDE . $firstImage->name . '" class="' . $otherClass . '">';
            }
        } else {
            return getFirstImage(array_slice($imageArray, 1), $otherClass, $thumbSize);
        }
    }
    return false;
}

/**
 * @param $imageArray
 * @return bool|string
 */
function getLastImage($imageArray)
{
    if ($imageArray) {
        $lastImage = end($imageArray);
        if (isImage(FILE_DIR_PATH . $lastImage->name)) {
            return '<img src="' . WEB_DIR_INCLUDE . $lastImage->name . '"
                                 alt="' . $lastImage->title . '">';
        }
    }
    return false;
}

/**
 * @param $imageArray
 * @return bool|string
 */
function getLittleImage($imageArray)
{
    if ($imageArray) {

        $littleImage = current($imageArray);
        $littleImageSize = getimagesize(FILE_DIR_PATH . $littleImage->name);

        foreach ($imageArray as $key => $img) {
            if (isImage(FILE_DIR_PATH . $img->name)) {

                $imageSize = getimagesize(FILE_DIR_PATH . $img->name);

                if ($imageSize[0] < $littleImageSize[0] && $imageSize[1] < $littleImageSize[1]) {
                    $littleImage = $img;
                    $littleImageSize = $imageSize;
                } else {
                    $proportionW = $imageSize[0] - $littleImageSize[0];
                    $proportionY = $imageSize[1] - $littleImageSize[1];
                    $proportion = $proportionW + $proportionY;

                    if ($proportion < $littleImageSize[0] && $proportion < $littleImageSize[1]) {
                        $littleImage = $img;
                        $littleImageSize = $imageSize;
                    }
                }
            }
        }

        return '<img src="' . WEB_DIR_INCLUDE . $littleImage->name . '"
                                 alt="' . $littleImage->title . '">';
    }
    return false;
}

/**
 * @param $imageArray
 * @return array
 */
function getOnlyImages($imageArray)
{
    $imagesFiltredArray = array();
    if ($imageArray) {

        foreach ($imageArray as $image) {
            if (isImage(FILE_DIR_PATH . $image->name)) {
                array_push($imagesFiltredArray, WEB_DIR_INCLUDE . $image->name);
            }
        }
    }
    return $imagesFiltredArray;
}

/**
 * @param Object $media
 * @param $class
 * @param $attr
 * @return string
 */
function showImage($media, $class = '', $attr = '')
{
    if (property_exists($media, 'name') && property_exists($media, 'description')) {
        return '<img src="' . WEB_DIR_INCLUDE . $media->name . '" 
                alt="' . $media->title . '" class="' . $class . '" ' . $attr . '>';
    }
    return '';
}

/**
 * @param $path
 *
 * @return mixed
 */
function getFileExtension($path)
{
    $pathInfos = pathinfo($path);
    return isset($pathInfos['extension']) ? $pathInfos['extension'] : false;
}

/**
 * @param $extension
 *
 * @return string
 */
function getImgAccordingExtension($extension)
{

    $src = WEB_TEMPLATE_URL . 'images/';
    switch (strtolower($extension)) {
        case 'jpg':
        case 'jpeg':
        case 'gif':
        case 'png':
            return 'img';
            break;
        case 'pdf':
            return $src . 'Pdf.png';
            break;
        case 'doc':
        case 'docx':
            return $src . 'Word.png';
            break;
        case 'xls':
        case 'xlsx':
            return $src . 'Excel.png';
            break;
        case 'ppt':
        case 'pptx':
            return $src . 'PowerPoint.png';
            break;
        case 'ogg':
        case 'mp3':
        case 'wma':
        case 'wov':
            return $src . 'Music.png';
            break;
        default:
            return $src . 'AllFileType.png';
            break;
    }
}

/**
 * @param $msg
 *
 * @return string
 */
function getContainerErrorMsg($msg)
{
    return '<div class="container-fluid"><div class="row"><div class="col-12"><p>' . $msg . '</p></div></div></div>';
}

/**
 *
 */
function setPays()
{
    if (empty($_SESSION['pays'])) {
        $json = file_get_contents('http://getcitydetails.geobytes.com/GetCityDetails?fqcn=' . getIP());
        $json = json_decode($json);
        $_SESSION['pays'] = $json->geobytesinternet;
    }
}

/**
 * @param $numTel
 *
 * @return string
 */
function FormatTel($numTel)
{
    $i = 0;
    $j = 0;
    $format = "";
    while ($i < strlen($numTel)) {
        if ($j < 2) {
            if (preg_match('/^[0-9]$/', $numTel[$i])) {
                $format .= $numTel[$i];
                $j++;
            }
            $i++;
        } else {
            $format .= " ";
            $j = 0;
        }
    }

    return $format;
}

/**
 * @param $str
 * @param $encoding
 *
 * @return mixed
 */
function noaccent($str, $encoding = 'utf-8')
{
    // transformer les caractères accentués en entités HTML
    $str = htmlentities($str, ENT_NOQUOTES, $encoding);

    // remplacer les entités HTML pour avoir juste le premier caractères non accentués
    // Exemple : "&ecute;" => "e", "&Ecute;" => "E", "à" => "a" ...
    $str = preg_replace('#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', $str);

    // Remplacer les ligatures tel que : , Æ ...
    // Exemple "œ" => "oe"
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
    // Supprimer tout le reste
    $str = preg_replace('#&[^;]+;#', '', $str);

    return $str;
}

/**
 * @param $str
 * @param string $charset
 * @return null|string|string[]
 */
function removeAccents($str, $charset = 'utf-8')
{
    $str = htmlentities($str, ENT_NOQUOTES, $charset);

    $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères

    return $str;
}


/**
 * @param $start
 * @param null $end
 * @return string
 * @throws Exception
 */
function formatDateDiff($start, $end = null)
{
    if (!($start instanceof DateTime)) {
        $start = new \DateTime($start);
    }

    if ($end === null) {
        $end = new \DateTime();
    }

    if (!($end instanceof DateTime)) {
        $end = new \DateTime($start);
    }

    $interval = $start->diff($end);

    if ($start == $end) {
        return "Aujourd'hui";
    }
    $doPlural = function ($nb, $str) {
        return $nb > 1 && $str != 'mois' ? $str . 's' : $str;
    };

    $format = array();
    if ($interval->y !== 0) {
        $format[] = "%y " . $doPlural($interval->y, "année");
    }
    if ($interval->m !== 0) {
        $format[] = "%m " . $doPlural($interval->m, "mois");;
    }
    if ($interval->d !== 0) {
        $format[] = "%d " . $doPlural($interval->d, "jour");
    }
    if ($interval->h !== 0) {
        $format[] = "%h " . $doPlural($interval->h, "heure");
    }
    if ($interval->i !== 0) {
        $format[] = "%i " . $doPlural($interval->i, "minute");
    }
    if ($interval->s !== 0) {
        if (!count($format)) {
            return "moins d\'une minute";
        } else {
            $format[] = "%s " . $doPlural($interval->s, "seconde");
        }
    }
    if (!empty($interval->format('%r'))) {
        $time = 'il y a ';
    } else {
        $time = 'dans ';
    }

    if (count($format) > 1) {
        $format = array_shift($format) . " et " . array_shift($format);
    } else {
        $format = array_pop($format);
    }

    return $time . $interval->format($format);
}

/**
 * @param $nb
 *
 * @return string
 */
function formatBillingNB($nb)
{
    switch ($nb) {
        case $nb < 10:
            $nb = '0000' . $nb;
            break;
        case $nb < 100:
            $nb = '000' . $nb;
            break;
        case $nb < 1000:
            $nb = '00' . $nb;
            break;
        case $nb < 10000:
            $nb = '0' . $nb;
            break;
        default:
            break;
    }

    return $nb;
}

/**
 * Doc on https://www.kernel.org/doc/Documentation/filesystems/proc.txt (section 1.8)
 * Return CPU of :
 * user - normal processes executing in user mode
 * nice - niced processes executing in user mode
 * sys - processes executing in kernel mode
 * idle - twiddling thumbs
 * @return array
 */
function getServerPerformances()
{
    $cpu = array();
    $dif = array();

    $stat1 = file('/proc/stat');
    sleep(1);
    $stat2 = file('/proc/stat');

    $info1 = explode(" ", preg_replace("!cpu +!", "", $stat1[0]));
    $info2 = explode(" ", preg_replace("!cpu +!", "", $stat2[0]));

    $dif['user'] = $info2[0] - $info1[0];
    $dif['nice'] = $info2[1] - $info1[1];
    $dif['sys'] = $info2[2] - $info1[2];
    $dif['idle'] = $info2[3] - $info1[3];

    $total = array_sum($dif);

    foreach ($dif as $x => $y) {
        $cpu[$x] = str_replace(',', '.', round($y / $total * 100, 1));
    }
    return $cpu;
}

/**
 * @param $cpu
 * @return string
 */
function getServerPerformanceColor($cpu)
{

    $color = 'success';

    if ($cpu > 50) {
        $color = 'warning';
    } elseif ($cpu > 80) {
        $color = 'danger';
    }

    return $color;
}

/**
 * @param array $data
 * @param array|null $otherAddr
 * @return bool
 * @throws Exception
 */
function sendMail(array $data, array $otherAddr = null)
{
    $Mail = new PHPMailer();

    $Mail->CharSet = 'utf-8';
    $Mail->SMTPDebug = !empty($data['debug']) ? $data['debug'] : 0;

    if (empty($data['smtp'])) {

        $Mail->IsMail();

    } else {

        $Mail->isSMTP();

        $Mail->SMTPKeepAlive = !empty($data['keepAlive']) ? $data['keepAlive'] : false;
        $Mail->SMTPSecure = !empty($data['encryption']) ? $data['encryption'] : '';

        $Mail->Host = $data['smtp']['host'];
        $Mail->Port = $data['smtp']['port'];

        if (!empty($data['smtp']['auth'])) {
            $Mail->SMTPAuth = $data['smtp']['auth'];
            $Mail->Username = $data['smtp']['username'];
            $Mail->Password = $data['smtp']['password'];
        }
    }

    // Expéditeur
    $Mail->SetFrom($data['fromEmail'], $data['fromName']);

    // Destinataire
    $Mail->ClearAddresses();
    $Mail->AddAddress($data['toEmail'], $data['toName']);

    if (!is_null($otherAddr) && is_array($otherAddr)) {
        foreach ($otherAddr as $email => $name) {
            $Mail->AddAddress($email, $name);
        }
    }

    // Objet
    $Mail->Subject = $data['object'];

    // Votre message
    $Mail->MsgHTML($data['message']);

    //Attach files from form
    if (isset($data['files']) && !empty($data['files'])) {
        foreach ($data['files'] as $file) {
            $Mail->AddAttachment($file['tmp_name'], $file['name']);
        }
    }

    //Attach files from url
    if (!empty($data['docs'])) {
        foreach ($data['docs'] as $doc) {
            $Mail->AddAttachment($doc['src'], $doc['name']);
        }
    }

    //Attach files from string
    if (!empty($data['strAttach'])) {
        foreach ($data['strAttach'] as $str) {
            $Mail->addStringAttachment($str['src'], $str['name']);
        }
    }

    if ($Mail->Send()) {
        return true;
    } else {
        return false; //$Mail->ErrorInfo
    }
}

/**
 * @param $month
 *
 * @return array|boolean
 */
function getMonth($month = '')
{
    $month_arr[1] = "Janvier";
    $month_arr[2] = "Février";
    $month_arr[3] = "Mars";
    $month_arr[4] = "Avril";
    $month_arr[5] = "Mai";
    $month_arr[6] = "Juin";
    $month_arr[7] = "Juillet";
    $month_arr[8] = "Août";
    $month_arr[9] = "Septembre";
    $month_arr[10] = "Octobre";
    $month_arr[11] = "Novembre";
    $month_arr[12] = "Décembre";

    return !empty($month) && array_key_exists($month, $month_arr) ? $month_arr[$month] : $month_arr;
}

/**
 * @param $date
 *
 * @return false|string
 */
function age($date)
{
    $dna = strtotime($date);
    $now = time();
    $age = date('Y', $now) - date('Y', $dna);
    if (strcmp(date('md', $dna), date('md', $now)) > 0) {
        $age--;
    }

    return $age;
}


/**
 * @param $file
 */
function fichierType($file)
{

    $type = pathinfo(strtolower($file), PATHINFO_EXTENSION);
    if ($type == 'jpg' || $type == 'png' || $type == 'jpeg' || $type == 'gif') {
        echo '<a href="' . WEB_DIR_INCLUDE . $file . '" target="_blank"><img src="' . WEB_DIR_INCLUDE . $file . '" alt="' . $file . '" title="' . $file . '" class="img-responsive"></a>';
    } elseif ($type == 'doc' || $type == 'dot' || $type == 'docx') {
        echo '<a href="' . WEB_DIR_INCLUDE . $file . '" target="_blank"><img src="' . WEB_TEMPLATE_URL . 'images/Word.png" alt="' . $file . '" title="' . $file . '" class="img-responsive"></a>';
    } elseif ($type == 'xlsx' || $type == 'xlt' || $type == 'xls' || $type == 'xla') {
        echo '<a href="' . WEB_DIR_INCLUDE . $file . '" target="_blank"><img src="' . WEB_TEMPLATE_URL . 'images/Excel.png" alt="' . $file . '" title="' . $file . '" class="img-responsive"></a>';
    } elseif ($type == 'pptx' || $type == 'ppt' || $type == 'pot' || $type == 'pps' || $type == 'ppa') {
        echo '<a href="' . WEB_DIR_INCLUDE . $file . '" target="_blank"><img src="' . WEB_TEMPLATE_URL . 'images/PowerPoint.png" alt="' . $file . '" title="' . $file . '" class="img-responsive"></a>';
    } elseif ($type == 'pdf') {
        echo '<a href="' . WEB_DIR_INCLUDE . $file . '" target="_blank"><img src="' . WEB_TEMPLATE_URL . 'images/Pdf.png" alt="' . $file . '" title="' . $file . '" class="img-responsive"></a>';
    } elseif ($type == 'mp3') {
        echo '<audio src="' . WEB_DIR_INCLUDE . $file . '" type="audio/mpeg" controls></audio>';
    } else {
        echo '<a href="' . WEB_DIR_INCLUDE . $file . '" target="_blank"><img src="' . WEB_TEMPLATE_URL . 'images/AllFileType.png" alt="' . $file . '" title="' . $file . '" class="img-responsive"></a><span class="fileType">' . $type . '</span>';
    }

}

/**
 * Get APPOE logo. if $appoelogo is true, return only appoe logo
 * @param $appoeLogo
 * @return string
 */
function getLogo($appoeLogo = false)
{
    $src = APP_IMG_URL . 'appoe-logo-white.png';
    if ($appoeLogo) return $src;

    $urlFolder = WEB_DIR_IMG;
    $pathFolder = WEB_PUBLIC_PATH . 'images/';
    $name = 'appoe-logo';
    $extensions = array('png', 'jpg', 'jpeg', 'gif', 'svg');

    foreach ($extensions as $extension) {

        $logo = $name . '.' . $extension;
        if (file_exists($pathFolder . $logo)) {
            $src = $urlFolder . $logo;
        }
    }

    return '<img class="img-responsive logoNavbar" src="' . $src . '" alt="APPOE">';
}

/* --------------------------------------------------------------------------
 * fonction permettant de transformer une valeur numérique en valeur en lettre
 * @param int $Nombre le nombre a convertir
 * @param int $Devise (0 = aucune, 1 = Euro €, 2 = Dollar $)
 * @param int $Langue (0 = Français, 1 = Belgique, 2 = Suisse)
 * @return string la chaine
 */
/**
 * @param $Nombre
 * @param int $Devise
 * @param int $Langue
 *
 * @return string
 */
function moneyAsLetters($Nombre, $Devise = 1, $Langue = 0)
{
    $dblEnt = '';
    $byDec = '';
    $bNegatif = '';
    $strDev = '';
    $strCentimes = '';

    if ($Nombre < 0) {
        $bNegatif = true;
        $Nombre = abs($Nombre);

    }
    $dblEnt = intval($Nombre);
    $byDec = round(($Nombre - $dblEnt) * 100);
    if ($byDec == 0) {
        if ($dblEnt > 999999999999999) {
            return "#TropGrand";
        }
    } else {
        if ($dblEnt > 9999999999999.99) {
            return "#TropGrand";
        }
    }
    switch ($Devise) {
        case 0 :
            if ($byDec > 0) {
                $strDev = " virgule";
            }
            break;
        case 1 :
            $strDev = " Euro";
            if ($byDec > 0) {
                $strCentimes = $strCentimes . " Centimes";
            }
            break;
        case 2 :
            $strDev = " Dollar";
            if ($byDec > 0) {
                $strCentimes = $strCentimes . " Cent";
            }
            break;
    }
    if (($dblEnt > 1) && ($Devise != 0)) {
        $strDev = $strDev . "s";
    }
    if ($byDec > 0) {
        $NumberLetter = ConvNumEnt(floatval($dblEnt), $Langue) . $strDev . " et " . ConvNumDizaine($byDec, $Langue) . $strCentimes;
    } else {
        $NumberLetter = ConvNumEnt(floatval($dblEnt), $Langue) . $strDev;
    }

    return $NumberLetter;
}

/**
 * @param $Nombre
 * @param $Langue
 *
 * @return mixed|string
 */
function ConvNumEnt($Nombre, $Langue)
{
    $byNum = $iTmp = $dblReste = '';
    $StrTmp = '';
    $NumEnt = '';
    $iTmp = $Nombre - (intval($Nombre / 1000) * 1000);
    $NumEnt = ConvNumCent(intval($iTmp), $Langue);
    $dblReste = intval($Nombre / 1000);
    $iTmp = $dblReste - (intval($dblReste / 1000) * 1000);
    $StrTmp = ConvNumCent(intval($iTmp), $Langue);
    switch ($iTmp) {
        case 0 :
            break;
        case 1 :
            $StrTmp = "mille ";
            break;
        default :
            $StrTmp = $StrTmp . " mille ";
    }
    $NumEnt = $StrTmp . $NumEnt;
    $dblReste = intval($dblReste / 1000);
    $iTmp = $dblReste - (intval($dblReste / 1000) * 1000);
    $StrTmp = ConvNumCent(intval($iTmp), $Langue);
    switch ($iTmp) {
        case 0 :
            break;
        case 1 :
            $StrTmp = $StrTmp . " million ";
            break;
        default :
            $StrTmp = $StrTmp . " millions ";
    }
    $NumEnt = $StrTmp . $NumEnt;
    $dblReste = intval($dblReste / 1000);
    $iTmp = $dblReste - (intval($dblReste / 1000) * 1000);
    $StrTmp = ConvNumCent(intval($iTmp), $Langue);
    switch ($iTmp) {
        case 0 :
            break;
        case 1 :
            $StrTmp = $StrTmp . " milliard ";
            break;
        default :
            $StrTmp = $StrTmp . " milliards ";
    }
    $NumEnt = $StrTmp . $NumEnt;
    $dblReste = intval($dblReste / 1000);
    $iTmp = $dblReste - (intval($dblReste / 1000) * 1000);
    $StrTmp = ConvNumCent(intval($iTmp), $Langue);
    switch ($iTmp) {
        case 0 :
            break;
        case 1 :
            $StrTmp = $StrTmp . " billion ";
            break;
        default :
            $StrTmp = $StrTmp . " billions ";
    }
    $NumEnt = $StrTmp . $NumEnt;

    return $NumEnt;
}

/**
 * @param $Nombre
 * @param $Langue
 *
 * @return mixed|string
 */
function ConvNumDizaine($Nombre, $Langue)
{
    $TabUnit = $TabDiz = '';
    $byUnit = $byDiz = '';
    $strLiaison = '';

    $TabUnit = array(
        "", "un", "deux", "trois", "quatre", "cinq", "six", "sept", "huit", "neuf", "dix", "onze", "douze",
        "treize", "quatorze", "quinze", "seize", "dix-sept", "dix-huit", "dix-neuf"
    );
    $TabDiz = array(
        "", "", "vingt", "trente", "quarante", "cinquante", "soixante", "soixante", "quatre-vingt", "quatre-vingt"
    );
    if ($Langue == 1) {
        $TabDiz[7] = "septante";
        $TabDiz[9] = "nonante";
    } else if ($Langue == 2) {
        $TabDiz[7] = "septante";
        $TabDiz[8] = "huitante";
        $TabDiz[9] = "nonante";
    }
    $byDiz = intval($Nombre / 10);
    $byUnit = $Nombre - ($byDiz * 10);
    $strLiaison = "-";
    if ($byUnit == 1) {
        $strLiaison = " et ";
    }
    switch ($byDiz) {
        case 0 :
            $strLiaison = "";
            break;
        case 1 :
            $byUnit = $byUnit + 10;
            $strLiaison = "";
            break;
        case 7 :
            if ($Langue == 0) {
                $byUnit = $byUnit + 10;
            }
            break;
        case 8 :
            if ($Langue != 2) {
                $strLiaison = "-";
            }
            break;
        case 9 :
            if ($Langue == 0) {
                $byUnit = $byUnit + 10;
                $strLiaison = "-";
            }
            break;
    }
    $NumDizaine = $TabDiz[$byDiz];
    if ($byDiz == 8 && $Langue != 2 && $byUnit == 0) {
        $NumDizaine = $NumDizaine . "s";
    }
    if ($TabUnit[$byUnit] != "") {
        $NumDizaine = $NumDizaine . $strLiaison . $TabUnit[$byUnit];
    } else {
        $NumDizaine = $NumDizaine;
    }

    return $NumDizaine;
}

/**
 * @param $Nombre
 * @param $Langue
 *
 * @return mixed|string
 */
function ConvNumCent($Nombre, $Langue)
{
    $TabUnit = '';
    $byCent = $byReste = '';
    $strReste = '';
    $NumCent = '';
    $TabUnit = array("", "un", "deux", "trois", "quatre", "cinq", "six", "sept", "huit", "neuf", "dix");

    $byCent = intval($Nombre / 100);
    $byReste = $Nombre - ($byCent * 100);
    $strReste = ConvNumDizaine($byReste, $Langue);
    switch ($byCent) {
        case 0 :
            $NumCent = $strReste;
            break;
        case 1 :
            if ($byReste == 0) {
                $NumCent = "cent";
            } else {
                $NumCent = "cent " . $strReste;
            }
            break;
        default :
            if ($byReste == 0) {
                $NumCent = $TabUnit[$byCent] . " cents";
            } else {
                $NumCent = $TabUnit[$byCent] . " cent " . $strReste;
            }
    }

    return $NumCent;
}

/****** JOURS FERIES ******/
function dimanche_paques($annee)
{
    return date("Y-m-d", easter_date($annee));
}

/**
 * @param $annee
 * @return false|string
 */
function vendredi_saint($annee)
{
    $dimanche_paques = dimanche_paques($annee);
    return date("Y-m-d", strtotime("$dimanche_paques -2 day"));
}

/**
 * @param $annee
 * @return false|string
 */
function lundi_paques($annee)
{
    $dimanche_paques = dimanche_paques($annee);
    return date("Y-m-d", strtotime("$dimanche_paques +1 day"));
}

/**
 * @param $annee
 * @return false|string
 */
function jeudi_ascension($annee)
{
    $dimanche_paques = dimanche_paques($annee);
    return date("Y-m-d", strtotime("$dimanche_paques +39 day"));
}

/**
 * @param $annee
 * @return false|string
 */
function lundi_pentecote($annee)
{
    $dimanche_paques = dimanche_paques($annee);
    return date("Y-m-d", strtotime("$dimanche_paques +50 day"));
}


/**
 * @param $annee
 * @param bool $alsacemoselle
 * @return array
 */
function jours_feries($annee, $alsacemoselle = false)
{
    $jours_feries = array(
        dimanche_paques($annee),
        lundi_paques($annee),
        jeudi_ascension($annee),
        lundi_pentecote($annee),
        "$annee-01-01",
        "$annee-05-01",
        "$annee-05-08",
        "$annee-05-15",
        "$annee-07-14",
        "$annee-11-11",
        "$annee-11-01",
        "$annee-12-25"
    );
    if ($alsacemoselle) {
        $jours_feries[] = "$annee-12-26";
        $jours_feries[] = vendredi_saint($annee);
    }
    sort($jours_feries);
    return $jours_feries;
}

/**
 * @param $jour
 * @param bool $alsaceMoselle
 * @return bool
 */
function isferie($jour, $alsaceMoselle = false)
{
    $jour = date("Y-m-d", strtotime($jour));
    $annee = substr($jour, 0, 4);
    return in_array($jour, jours_feries($annee, $alsaceMoselle));
}