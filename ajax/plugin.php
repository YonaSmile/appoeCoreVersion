<?php
require_once('header.php');

if (checkAjaxRequest()) {

    if (!empty($_POST['checkUserSession'])) {
        echo isUserSessionExist() ? 'true' : 'false';
        exit();
    }

    if (!empty($_REQUEST['setupPath'])) {
        activePlugin($_REQUEST['setupPath']);
        exit();
    }

    if (!empty($_REQUEST['deletePluginName'])) {

        $pluginName = $_REQUEST['deletePluginName'];

        //Backup
        \App\DB::backup(date('Y-m-d'), 'db-' . date('H_i_s'));
        saveFiles('app/plugin/' . $pluginName);

        //Delete tables
        if (file_exists(WEB_PLUGIN_PATH . $pluginName . DIRECTORY_SEPARATOR . 'ini.php')) {
            include_once(WEB_PLUGIN_PATH . $pluginName . DIRECTORY_SEPARATOR . 'ini.php');

            if (!empty(PLUGIN_TABLES)) {
                foreach (PLUGIN_TABLES as $key => $table) {
                    \App\DB::initialize()->deleteTable($table);
                }
            }
        }

        //Delete Menu
        $Menu = new \App\Menu();
        $Menu->deletePluginMenu($pluginName);

        //Delete Directories & Files
        deleteAllFolderContent(WEB_PLUGIN_PATH . $pluginName);

        echo json_encode(true);
        exit();
    }

    if (!empty($_POST['checkTable'])) {

        $pluginName = $_POST['checkTable'];
        $existTable = 0;

        if (file_exists(WEB_PLUGIN_PATH . $pluginName . DIRECTORY_SEPARATOR . 'ini.php')) {
            include_once(WEB_PLUGIN_PATH . $pluginName . DIRECTORY_SEPARATOR . 'ini.php');

            if (!empty(PLUGIN_TABLES)) {
                foreach (PLUGIN_TABLES as $key => $table) {
                    if (\App\DB::initialize()->checkTable($table)) {
                        $existTable++;
                    }
                }
            }
        }

        echo $existTable;
        exit();
    }

    if (!empty($_POST['checkVersion'])) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://raw.githubusercontent.com/YonaSmile/appoePluginsVersions/master/' . $_POST['checkVersion'] . '/version.json');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);

        echo $data;
        exit();
    }

    if (!empty($_POST['checkSystemVersion'])) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://raw.githubusercontent.com/YonaSmile/appoeCoreVersion/master/version.json');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);

        echo $data;
        exit();
    }

    if (!empty($_POST['checkLibVersion'])) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://raw.githubusercontent.com/YonaSmile/appoeLibVersion/master/version.json');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);

        echo $data;
        exit();
    }

    if (!empty($_POST['downloadPlugins'])) {

        if (downloadZip(ROOT_PATH . 'plugins.zip', 'https://github.com/YonaSmile/appoePluginsVersions/archive/master.zip')) {
            if (unzipSkipFirstFolder(ROOT_PATH . 'plugins.zip', ROOT_PATH, 'appoePluginsVersions-master', WEB_PLUGIN_PATH)) {
                echo 'true';
            }
        }
        exit();
    }

    if (!empty($_POST['downloadLib'])) {

        if (downloadZip(ROOT_PATH . 'appoeLib.zip', 'https://github.com/YonaSmile/appoeLibVersion/archive/master.zip')) {
            if (unzipSkipFirstFolder(ROOT_PATH . 'appoeLib.zip', ROOT_PATH, 'appoeLibVersion-master', WEB_LIB_PATH)) {
                echo 'true';
            }
        }
        exit();
    }

    if (!empty($_POST['downloadSystemCore'])) {

        if (downloadZip(ROOT_PATH . 'appoeCore.zip', 'https://github.com/YonaSmile/appoeCoreVersion/archive/master.zip')) {
            if (unzipSkipFirstFolder(ROOT_PATH . 'appoeCore.zip', ROOT_PATH, 'appoeCoreVersion-master', WEB_APP_PATH)) {
                if (downloadZip(ROOT_PATH . 'rooter.zip', 'https://github.com/YonaSmile/appoeRooterVersion/archive/master.zip')) {
                    if (unzipSkipFirstFolder(ROOT_PATH . 'rooter.zip', ROOT_PATH, 'appoeRooterVersion-master', ROOT_PATH)) {

                        updateDB();
                        echo 'true';
                    }
                }
            }
        }
        exit();
    }

    if (!empty($_POST['updateSitemap'])) {

        $CmsMenu = new \App\Plugin\Cms\CmsMenu();
        $data = extractFromObjArr($CmsMenu->showAll(), 'slug');

        if (generateSitemap($data)) {
            echo 'true';
        }
        exit();
    }

    if (!empty($_POST['optimizeDb'])) {

        $tables = \App\DB::initialize()->getTables();
        \App\DB::backup(date('Y-m-d'), 'db-' . date('H_i'));
        $Menu = new \App\Menu();
        $optimizeTables = array();
        foreach ($tables as $table) {
            $tableName = array_values((array)$table);
            if (!in_array($tableName[0], APP_TABLES) && false !== strpos($tableName[0], 'appoe')) {
                list($pre, $plugin, $pluginName, $pluginExtension) = array_pad(explode('_', $tableName[0], 4), 4, null);
                if (!is_null($pluginName) && !is_dir(WEB_PLUGIN_PATH . $pluginName)) {
                    if (\App\DB::deleteTable($tableName[0])) {
                        $Menu->deletePluginMenu($pluginName);
                        $optimizeTables[] = $tableName[0];
                    }
                }
            }
        }
        echo trans('Tables enregistrées') . '.' . (count($optimizeTables) > 0 ? '<br><strong>Tables supprimées:</strong> ' . implode(', ', $optimizeTables) : '');
        exit();
    }

    if (!empty($_POST['saveFile'])) {

        $folder = !empty($_POST['folder']) ? $_POST['folder'] : 'public';
        $saveFiles = saveFiles($folder);
        if (false !== $saveFiles) {
            echo $folder . ' / ' . getSizeName($saveFiles['copySize']) . trans(' Fichiers enregistrés'), '. <a href="' . $saveFiles['downloadLink'] . '"> ' . trans('Télécharger') . ' (' . getSizeName($saveFiles['zipSize']) . ')</a>';
        }
        exit();
    }

    if (!empty($_POST['pageInfos']) && !empty($_POST['filename'])) {

        $helpPage = getPageHelp($_POST['filename']);
        echo \App\Form::textarea('Information', 'pageInfo', $helpPage, 5, true, '', 'ckeditor');
    }

    if (!empty($_POST['ADDPAGEINFO']) && !empty($_POST['page']) && !empty($_POST['pageInfo'])) {

        if (setHelpPage($_POST['page'], $_POST['pageInfo'])) {
            echo 'true';
        } else {
            trans('Une erreur s\'est produite lors de l\'enregistrement des informations');
        }
    }
}
