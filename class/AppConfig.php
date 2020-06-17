<?php

namespace App;
class AppConfig
{

    /**
     * @var string
     */
    private $configFile = 'appConfig.json';

    /**
     * @var string
     */
    private $pathConfigFile = null;

    /**
     * @var array
     */
    private $config = array();

    /**
     * @var array
     */
    private $defaultConfig = array(
        'options' => array(
            'maintenance' => false,
            'forceHTTPS' => false,
            'cacheProcess' => false,
            'sharingWork' => false,
            'allowApi' => false
        ),
        'data' => array(
	        'defaultEmail' => '',
            'apiToken' => ''
        ),
        'accessPermissions' => array(),
        'user' => array(
            'id' => '',
            'name' => '',
            'date' => ''
        )
    );

    /**
     * @var array
     */
    public $configExplanation = array(
        'maintenance' => 'Mode maintenance',
        'forceHTTPS' => 'Forcer le site en HTTPS',
        'cacheProcess' => 'Autoriser la mise en cache des fichiers',
        'sharingWork' => 'Autoriser le travail sur la même page',
        'allowApi' => 'Autoriser l\'API',
        'apiToken' => 'Clé API',
	    'defaultEmail' => 'Adresse Email par défaut'
    );

    /**
     * AppLogging constructor.
     */
    public function __construct()
    {

        if ($this->checkConfigFile()) {

            $this->pathConfigFile = WEB_SYSTEM_PATH . $this->configFile;
            $this->config = array_replace_recursive($this->defaultConfig, getJsonContent($this->pathConfigFile));

            //User data
            $this->config['user'] = array(
                'id' => getUserIdSession(),
                'name' => getUserName() . ' ' . getUserFirstName(),
                'date' => date('Y-m-d H:i:s')
            );
        }
    }

    /**
     * @return bool
     */
    private function checkConfigFile()
    {

        if (!file_exists(WEB_SYSTEM_PATH . $this->configFile)) {
            if (false === fopen(WEB_SYSTEM_PATH . $this->configFile, 'a+')) {
                return false;
            }

            return putJsonContent(WEB_SYSTEM_PATH . $this->configFile, $this->defaultConfig);
        }
        return true;
    }

    /**
     * @param $key
     * @param array $data
     * @return bool
     */
    public function write($key, array $data = array())
    {
        if (!is_null($this->pathConfigFile)) {

            foreach ($data as $cmd => $value) {
                $this->config[$key][$cmd] = $value;
            }
            $this->dataOperations($data);
            appLog('Update APPOE preferences: ' . implode(', ', array_keys($data)));
            return putJsonContent($this->pathConfigFile, $this->config);
        }
        return false;
    }

    /**
     * @param $ip
     * @return bool
     */
    public function addPermissionAccess($ip)
    {
        if (!is_null($this->pathConfigFile) && !in_array($ip, $this->config['accessPermissions'])) {

            array_push($this->config['accessPermissions'], $ip);
            appLog('Add APPOE access permission to: ' . $ip);
            return putJsonContent($this->pathConfigFile, $this->config);

        }
        return false;
    }

    /**
     * @param $ip
     * @return bool
     */
    public function deletePermissionAccess($ip)
    {
        if (!is_null($this->pathConfigFile)) {

            if (($key = array_search($ip, $this->config['accessPermissions'])) !== false) {
                unset($this->config['accessPermissions'][$key]);

                appLog('Remove APPOE access permission to: ' . $ip);
                return putJsonContent($this->pathConfigFile, $this->config);
            }
        }
        return false;
    }

    /**
     * @param bool $key
     * @param bool $subKey
     * @return array|bool
     */
    public function get($key = false, $subKey = false)
    {
        if ($key && array_key_exists($key, $this->config)) {

            if ($subKey && array_key_exists($subKey, $this->config[$key])) {
                return $this->config[$key][$subKey];
            }

            return $this->config[$key];
        }
        return $this->config;
    }

    /**
     * @return bool
     */
    public function restoreConfig()
    {
        if (file_exists(WEB_SYSTEM_PATH . $this->configFile)) {
            return putJsonContent(WEB_SYSTEM_PATH . $this->configFile, $this->defaultConfig);
        } else {
            return $this->checkConfigFile();
        }
    }

    /**
     * @param $data
     */
    private function dataOperations($data)
    {

        if (array_key_exists('allowApi', $data)) {
            if ($data['allowApi'] === 'true') {
                $this->config['data']['apiToken'] = setToken(false);
            } else {
                $this->config['data']['apiToken'] = '';
            }
        }
    }
}