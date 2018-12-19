<?php
require_once('header.php');

if (checkAjaxRequest()) {

    if (!empty($_REQUEST['setupPath'])) {
        activePlugin($_REQUEST['setupPath']);
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
    }

    if (!empty($_POST['checkVersion'])) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://raw.githubusercontent.com/YonaSmile/appoePluginsVersions/master/' . $_POST['checkVersion'] . '/version.json');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);

        echo $data;
    }

    if (!empty($_POST['checkSystemVersion'])) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://raw.githubusercontent.com/YonaSmile/appoeCoreVersion/master/version.json');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);

        echo $data;
    }

    if (!empty($_POST['downloadPlugins'])) {

        if (downloadZip(ROOT_PATH . 'plugins.zip', 'https://github.com/YonaSmile/appoePluginsVersions/archive/master.zip')) {
            if (unzipSkipFirstFolder(ROOT_PATH . 'plugins.zip', ROOT_PATH, 'appoePluginsVersions-master', WEB_PLUGIN_PATH)) {
                echo 'true';
            }
        }
    }

    if (!empty($_POST['downloadSystemCore'])) {

        if (downloadZip(ROOT_PATH . 'appoeCore.zip', 'https://github.com/YonaSmile/appoeCoreVersion/archive/master.zip')) {
            if (unzipSkipFirstFolder(ROOT_PATH . 'appoeCore.zip', ROOT_PATH, 'appoeCoreVersion-master', WEB_APP_PATH)) {
                if (downloadZip(ROOT_PATH . 'rooter.zip', 'https://github.com/YonaSmile/appoeRooterVersion/archive/master.zip')) {
                    if (unzipSkipFirstFolder(ROOT_PATH . 'rooter.zip', ROOT_PATH, 'appoeRooterVersion-master', ROOT_PATH)) {
                        echo 'true';
                    }
                }
            }
        }
    }

    if (!empty($_POST['updateSitemap'])) {

        $CmsMenu = new \App\Plugin\Cms\CmsMenu();
        $data = extractFromObjArr($CmsMenu->showAll(), 'slug');

        if (generateSitemap($data)) {
            echo 'true';
        }
    }

    if (!empty($_POST['optimizeDb'])) {

        $tables = \App\DB::initialize()->getTables();
        \App\DB::backup(date('Y-m-d'), 'db-' . date('H_i'));
        $Menu = new \App\Menu();
        $optimizeTables = 0;
        foreach ($tables as $table) {
            $tableName = array_values((array)$table);
            if (!in_array($tableName[0], APP_TABLES)) {
                list($pre, $plugin, $pluginName, $pluginExtension) = array_pad(explode('_', $tableName[0], 4), 4, null);
                if (!is_null($pluginName) && !file_exists(WEB_PLUGIN_PATH . $pluginName . '/ini.php')) {
                    if (\App\DB::deleteTable($tableName[0])) {
                        $Menu->deletePluginMenu($pluginName);
                        $optimizeTables++;
                    }
                }
            }
        }
        echo trans('Tables enregistrées') . '. Tables Optimisés: ' . $optimizeTables;
    }

    if (!empty($_POST['saveFile'])) {

        $saveFiles = saveFiles();
        if (false !== $saveFiles) {
            echo getSizeName($saveFiles['copySize']) . trans(' Fichiers enregistrés'), '. <a href="' . $saveFiles['downloadLink'] . '"> ' . trans('Télécharger les fichiers') . ' (' . getSizeName($saveFiles['zipSize']) . ')</a>';
        }
    }
}
