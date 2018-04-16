<?php

require ROOT_PATH . 'ressources/PHPMailer/PHPMailerAutoload.php';

/**
 * @return string
 */
function pageName()
{
    $slug = pageSlug();
    $json = file_get_contents(WEB_SYSTEM_PATH . 'config.json');
    $parsed_json = json_decode($json, true);
    $page_name = isset($parsed_json['pages_name'][$slug]['title']) ? $parsed_json['pages_name'][$slug]['title'] : 'Non définie';

    return $page_name;
}

/**
 * @return string
 */
function pageDescription()
{
    $slug = pageSlug();
    $json = file_get_contents(WEB_SYSTEM_PATH . 'config.json');
    $parsed_json = json_decode($json, true);
    $page_desc = isset($parsed_json['pages_name'][$slug]['description']) ? $parsed_json['pages_name'][$slug]['description'] : 'Art Of Event - Communication.';

    return $page_desc;
}

/**
 * @return string
 */
function pageSlug()
{
    return htmlentities(substr(basename($_SERVER['PHP_SELF']), 0, -4));
}

/**
 * @param $url
 *
 * @return string
 */
function activePage($url)
{
    if (strstr(substr(basename($_SERVER['PHP_SELF']), 0, -4), $url)) {
        return ' active ';
    }

    return '';
}

/**
 * @param string $jsonKey
 *
 * @return mixed
 */
function getConfigContent($jsonKey = 'pages_name')
{
    $json = file_get_contents(WEB_SYSTEM_PATH . 'config.json');
    $parsed_json = json_decode($json, true);

    return $parsed_json[$jsonKey];
}

/**
 * @param $filename
 * @param string $jsonKey
 *
 * @return mixed
 */
function getJsonContent($filename, $jsonKey = '')
{
    $json = file_get_contents($filename);
    $parsed_json = json_decode($json, true);

    return (!empty($jsonKey)) ? $parsed_json[$jsonKey] : $parsed_json;
}

function isImage($mediaPath)
{
    return @is_array(getimagesize($mediaPath)) ? true : false;
}

function shortenText($text, $size)
{
    return mb_strimwidth(
        strip_tags(
            html_entity_decode(
                htmlspecialchars_decode($text))), 0, $size, '...', 'utf-8');
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
    if (LANG != 'fr' && file_exists(FILE_LANG_PATH . LANG . DIRECTORY_SEPARATOR . $doc . '.json')) {

        //get lang file
        $json = file_get_contents(FILE_LANG_PATH . LANG . DIRECTORY_SEPARATOR . $doc . '.json');
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
 * @return array
 */
function getFilesFromDir($dirname)
{
    return array_diff(scandir($dirname), array('..', '.'));
}

/**
 * @return array
 */
function getLangs()
{
    return getFilesFromDir(WEB_SYSTEM_PATH . 'lang/');
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
 * @param $timestamp
 * @param bool $hour
 * @return string
 * @throws Exception
 */
function displayTimeStamp($timestamp, $hour = true)
{
    $Date = new DateTime($timestamp);
    $Date->add(new DateInterval('PT1H'));

    if ($hour) {
        return $Date->format('d/m/Y H:i');
    }

    return $Date->format('d/m/Y');
}


/**
 * @param $date
 * @param bool $hour
 * @return string
 * @throws Exception
 */
function displayCompleteDate($date, $hour = false)
{
    $Date = new DateTime($date);
    $Date->add(new DateInterval('PT1H'));
    $time = $hour ? $Date->format("Y-m-d H:i") : $Date->format("Y-m-d");
    $strFtime = $hour ? "%A %d %B %Y, %H:%M" : "%A %d %B %Y";
    return ucwords(strftime($strFtime, strtotime($time)));
}


/**
 * @param $dateDebut
 * @param $dateFin
 *
 * @return string
 */
function displayEventDates($dateDebut, $dateFin)
{

    //Initialise Dates
    $DateDebut = new DateTime($dateDebut);
    $DateFin = new DateTime($dateFin);

    $html = '';
    if ($DateDebut->format('d') == $DateFin->format('d')) {
        $html .= '<strong>' . trans('Le') . ' ' . ucwords(strftime("%A ", strtotime($DateDebut->format("Y-m-d")))) . '</strong>';
    } else {
        $html .= '<strong>' . trans('De') . ' ' . ucwords(strftime("%A ", strtotime($DateDebut->format("Y-m-d"))));
        $html .= trans('A ') . ' ' . ucwords(strftime("%A ", strtotime($DateFin->format("Y-m-d")))) . '</strong>';
    }

    $html .= ' ' . trans('de') . ' ' . $DateDebut->format('H:i') . ' ' . trans('à') . ' ' . $DateFin->format('H:i');


    return $html;
}

/**
 * @param $date
 *
 * @return string
 */
function getDayNameFromDate($date)
{
    $Date = new DateTime($date);

    return ucwords(strftime("%A ", strtotime($Date->format("Y-m-d"))));
}


/**
 * @param $date
 *
 * @return string
 */
function getDateFormatFR($date)
{
    list($annee, $mois, $jour) = explode('-', $date);

    return $jour . ' ' . getMonth($mois) . ' ' . $annee;
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
 * @return bool
 */
function checkMaintenance()
{
    if (MAINTENANCE) {
        if (in_array(getIP(), IP_ALLOWED)) {
            return true;
        }
        return false;
    }
    return true;
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
 * @param $setupPath
 * @return bool|string
 */
function activePlugin($setupPath)
{
    return file_get_contents($setupPath);
}

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
    $i = new DirectoryIterator($src);
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
 * Recursively move files from one directory to another
 *
 * @param String $src - Source of files being moved
 * @param String $dest - Destination of files being moved
 * @return bool
 */
function rmove($src, $dest)
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
    $i = new DirectoryIterator($src);
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
    rrmdir($src);
    return true;
}

function unzipSkipFirstFolder($src, $path, $firstFolderName, $deleteZip = true)
{
    $tempFolder = $path . 'unzip';
    $zip = new ZipArchive;
    $res = $zip->open($src);
    if ($res === TRUE) {
        $zip->extractTo($tempFolder);
        $directories = scandir($tempFolder . '/' . $firstFolderName);
        foreach ($directories as $directory) {
            if ($directory != '.' and $directory != '..') {
                if (is_dir($tempFolder . '/' . $firstFolderName . '/' . $directory) && is_dir(WEB_PLUGIN_PATH . $directory)) {
                    rmove($tempFolder . '/' . $firstFolderName . '/' . $directory, WEB_PLUGIN_PATH . $directory);
                }
            }
        }

        $zip->close();
    }
    rrmdir($tempFolder);
    if ($deleteZip) {
        unlink($src);
    }

    return true;
}

function unzip($src, $path, $deleteZip = true)
{
    $zip = new ZipArchive;
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
 * @param $dir
 */
function rrmdir($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir . "/" . $object) == "dir") rrmdir($dir . "/" . $object); else unlink($dir . "/" . $object);
            }
        }
        reset($objects);
        rmdir($dir);
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
 */
function getHoursFromDate($date1, $date2 = '')
{
    $Date1 = new DateTime($date1);

    if (empty($date2)) {
        return $Date1->format('H:i');
    } else {
        $Date2 = new DateTime($date2);

        return trans('De') . ' ' . $Date1->format('H:i') . ' ' . trans('à') . ' ' . $Date2->format('H:i');
    }
}

/**
 * @param $date
 *
 * @return string
 */
function displayFrDate($date)
{
    list($annee, $mois, $jour) = explode('-', $date);

    return $jour . '/' . $mois . '/' . $annee;
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
    $_SESSION['_token'] = $string;
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
 * @param $filename
 * @param $desired_width
 * @param $quality
 */
function thumb($filename, $desired_width = 100, $quality = 80)
{
    $src = FILE_DIR_PATH . $filename;
    $dest = FILE_DIR_PATH . 'thumb' . DIRECTORY_SEPARATOR . $desired_width . '_' . $filename;

    if (!file_exists(FILE_DIR_PATH . 'thumb/')) {
        mkdir(FILE_DIR_PATH . 'thumb', 0705);
    }

    if (is_file($src) && !is_file($dest)) {

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
    }
}

function getThumb($thumb_name, $desired_width)
{
    if (is_file(FILE_DIR_PATH . 'thumb' . DIRECTORY_SEPARATOR . $desired_width . '_' . $thumb_name)) {
        return WEB_DIR_INCLUDE . 'thumb' . DIRECTORY_SEPARATOR . $desired_width . '_' . $thumb_name;
    } else {
        thumb($thumb_name, $desired_width, 100);
        return getThumb($thumb_name, $desired_width);
    }
}

/**
 * @param $path
 * @return string
 */
function getFileContent($path)
{
    ob_start();

    $Traduction = new App\Plugin\Traduction\Traduction(LANG);

    if (file_exists($path)) {
        include $path;
    }
    $pageContent = ob_get_clean();

    return $pageContent;
}

/**
 * @param $name
 * @param null $parentId
 * @param string $categoryType
 * @return array
 */
function getSpecificMediaCategory($name, $parentId = null, $categoryType = 'MEDIA')
{
    $Category = new App\Category();
    $Category->setType($categoryType);

    $allCategories = extractFromObjArr($Category->showByType(), 'id');

    $Media = new App\Media();
    $name = removeAccents(html_entity_decode($name));

    $allMedia = array();
    foreach ($allCategories as $key => $category) {
        $access = false;

        if (!is_null($parentId)) {

            if ($category->parentId != $parentId
                && removeAccents(html_entity_decode($allCategories[$category->parentId]->name)) === $name
            ) {
                $access = true;
            }

        } else {
            if (removeAccents(html_entity_decode($allCategories[$category->id]->name)) === $name) {
                $access = true;
            }
        }

        if ($access) {
            $Media->setTypeId($category->id);
            $allMedia[$category->id] = $Media->showFiles();
        }
    }

    return $allMedia;
}

/**
 * @param $allContentArr
 * @param $key
 * @return array
 */
function extractFromObjArr($allContentArr, $key)
{
    $allContent = array();
    foreach ($allContentArr as $contentArr) {
        $allContent[$contentArr->$key] = $contentArr;
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
 * @param $allContentArr
 * @param $key
 * @param string $value
 * @return array
 */
function extractFromObjToSimpleArr($allContentArr, $key, $value = '')
{
    $allContent = array();

    if ($allContentArr) {

        if (!empty($value)) {

            foreach ($allContentArr as $contentArr) {
                $allContent[$contentArr->$key] = $contentArr->$value;
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
 * @param $filename
 * @param $allContent
 * @return mixed|string
 */
function showTemplateContent($filename, $allContent)
{
    $pageContent = getFileContent($filename);
    $templateContent = explode('%%', trim(strip_tags($pageContent)));
    $templateContent = array_filter($templateContent, 'trim');

    foreach ($templateContent as $content) {
        if (strpos($content, '_')) {

            list($metaKey, $formType) = explode('_', $content);
            $pageContent = str_replace('%%' . $content . '%%', !empty($allContent[$metaKey]) ? html_entity_decode($allContent[$metaKey]->metaValue) : $metaKey, $pageContent);
        }
    }

    return $pageContent;
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
                if ($dashboard) {
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
function includePluginsFiles()
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
function includePluginsJs()
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
function checkPostAndTokenRequest()
{
    if (
        $_SERVER["REQUEST_METHOD"] == "POST"
        && !empty($_POST['_token'])
        && !empty($_SESSION['_token'])
        && $_POST['_token'] == $_SESSION['_token']
    ) {
        unsetToken();

        $excludeSlug = array(
            'hibour'
        );

        if (!in_array(pageSlug(), $excludeSlug)) {
            if (function_exists('mehoubarim_connecteUser')) {
                mehoubarim_connecteUser();
            }
        }

        return true;
    }

    unsetToken();

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
    if (!empty($_SESSION['auth'])) {
        list($idUser, $emailSession) = explode('351ab51c2d33efb942cab11f25cdc517a84df66bc51ffe1f2beb!a6fgcb!f152ddb3!6ff2cd41abd35df42cbb21a', $_SESSION['auth']);

        return $idUser;
    }

    return false;
}

/**
 *
 */
function deconnecteUser()
{
    unset($_SESSION['auth']);
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
        && preg_match('/bot|crawl|curl|dataprovider|search|get|spider|find|java|majesticsEO|google|yahoo|contaxe|libwww-perl|facebookexternalhit|mediapartners|baidu|bingbot|facebookexternalhit|googlebot|-google|ia_archiver|msnbot|naverbot|pingdom|seznambot|slurp|teoma|twitter|yandex|yeti/i', $_SERVER['HTTP_USER_AGENT'])
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

    return WEB_DIR_URL . $file . $url;
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
 * @param $path
 *
 * @return mixed
 */
function getFileName($path)
{
    $pathInfos = pathinfo($path);

    return $pathInfos['filename'];
}

function getFirstImage($imageArray)
{
    if ($imageArray) {
        foreach ($imageArray as $image) {
            if (isImage(FILE_DIR_PATH . $image->name)) {
                return '<img src="' . WEB_DIR_INCLUDE . $image->name . '"
                                 alt="' . $image->description . '">';
            }
        }
    }
    return false;
}

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

    $src = WEB_APP_URL . 'images/';
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

            //First level menu sorting by location and second level by parent Id.
            $menu[$menuPage->location][$menuPage->parentId][] = $menuPage;
        }
    }
    return $menu;
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
 *
 * @param DateTime $start
 * @param DateTime|null $end
 *
 * @return string
 */
function formatDateDiff($start, $end = null)
{
    if (!($start instanceof DateTime)) {
        $start = new DateTime($start);
    }

    if ($end === null) {
        $end = new DateTime();
    }

    if (!($end instanceof DateTime)) {
        $end = new DateTime($start);
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
 * @param array $data
 * @param array $otherAddr
 *
 * @return bool
 */
function sendMail(array $data, array $otherAddr = null)
{
    $mail = new PHPMailer();
    $mail->IsMail();
    $mail->IsHTML(true);
    $mail->setLanguage('fr', '/optional/path/to/language/directory/');
    $mail->CharSet = 'utf-8';

    // Expéditeur
    $mail->SetFrom($data['fromEmail'], $data['fromName']);

    // Destinataire
    $mail->ClearAddresses();
    $mail->AddAddress($data['toEmail'], $data['toName']);
    if (!is_null($otherAddr) && is_array($otherAddr)) {
        foreach ($otherAddr as $email => $name) {
            $mail->AddAddress($email, $name);
        }
    }

    // Objet
    $mail->Subject = $data['object'];

    // Votre message
    $mail->MsgHTML($data['message']);

    if (isset($data['files']) && !empty($data['files'])) {
        foreach ($data['files'] as $photo) {
            $mail->AddAttachment($photo['tmp_name'], $photo['name']);
        }
    }

    if (!$mail->Send()) {
        return false; //'Erreur : ' . $mail->ErrorInfo;
    } else {
        return true;
    }
}

/**
 * @param $month
 *
 * @return mixed
 */
function getMonth($month)
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

    return $month_arr[$month];
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
        echo '<a href="' . FILE_DIR_URL . $file . '" target="_blank"><img src="' . FILE_DIR_URL . $file . '" alt="' . $file . '" title="' . $file . '" class="img-responsive"></a>';
    } elseif ($type == 'doc' || $type == 'dot' || $type == 'docx') {
        echo '<a href="' . FILE_DIR_URL . $file . '" target="_blank"><img src="' . WEB_DIR_URL . 'images/Word.png" alt="' . $file . '" title="' . $file . '" class="img-responsive"></a>';
    } elseif ($type == 'xlsx' || $type == 'xlt' || $type == 'xls' || $type == 'xla') {
        echo '<a href="' . FILE_DIR_URL . $file . '" target="_blank"><img src="' . WEB_DIR_URL . 'images/Excel.png" alt="' . $file . '" title="' . $file . '" class="img-responsive"></a>';
    } elseif ($type == 'pptx' || $type == 'ppt' || $type == 'pot' || $type == 'pps' || $type == 'ppa') {
        echo '<a href="' . FILE_DIR_URL . $file . '" target="_blank"><img src="' . WEB_DIR_URL . 'images/PowerPoint.png" alt="' . $file . '" title="' . $file . '" class="img-responsive"></a>';
    } elseif ($type == 'pdf') {
        echo '<a href="' . FILE_DIR_URL . $file . '" target="_blank"><img src="' . WEB_DIR_URL . 'images/Pdf.png" alt="' . $file . '" title="' . $file . '" class="img-responsive"></a>';
    } elseif ($type == 'mp3') {
        echo '<audio src="' . FILE_DIR_URL . $file . '" type="audio/mpeg" controls></audio>';
    } else {
        echo '<a href="' . FILE_DIR_URL . $file . '" target="_blank"><img src="' . WEB_DIR_URL . 'images/AllFileType.png" alt="' . $file . '" title="' . $file . '" class="img-responsive"></a><span class="fileType">' . $type . '</span>';
    }

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
        "",
        "un",
        "deux",
        "trois",
        "quatre",
        "cinq",
        "six",
        "sept",
        "huit",
        "neuf",
        "dix",
        "onze",
        "douze",
        "treize",
        "quatorze",
        "quinze",
        "seize",
        "dix-sept",
        "dix-huit",
        "dix-neuf"
    );
    $TabDiz = array(
        "",
        "",
        "vingt",
        "trente",
        "quarante",
        "cinquante",
        "soixante",
        "soixante",
        "quatre-vingt",
        "quatre-vingt"
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