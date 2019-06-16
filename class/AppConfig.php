<?php

namespace App;
class AppConfig
{
    /**
     * @var false|string
     */
    private $date;

    /**
     * @var bool
     */
    private $user;

    /**
     * @var string
     */
    private $configFile = 'appConfig.json';

    /**
     * @var string
     */
    private $pathConfigFile = null;

    /******************************** CONFIG DATA ********************************/

    public $forceHTTPS = false;

    /**
     * AppLogging constructor.
     */
    public function __construct()
    {
        $this->date = date('Y-m-d H:i:s');
        $this->user = getUserIdSession();

        if ($this->checkConfigFile()) {
            $this->pathConfigFile = WEB_SYSTEM_PATH . $this->configFile;
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

            $defaultData = array('back' => array(), 'front' => array());
            return putJsonContent(WEB_SYSTEM_PATH . $this->configFile, $defaultData);
        }
        return true;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function write(array $data = array())
    {
        if (!is_null($this->pathConfigFile)) {

            $condifData = array_merge(getJsonContent($this->pathConfigFile), $data);

            return putJsonContent($this->pathConfigFile, $condifData);
        }
        return false;
    }

    /**
     * @return bool
     */
    public function get()
    {
        if (!is_null($this->pathConfigFile)) {
            return getJsonContent($this->pathConfigFile);
        }
        return false;
    }
}