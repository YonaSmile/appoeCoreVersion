<?php

namespace App;
class AppLogging
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
    private $userName;
    /**
     * @var string
     */
    private $message;
    /**
     * @var string
     */
    private $logFile = 'applog.log';
    /**
     * @var string
     */
    private $pathLogFile = null;


    /**
     * AppLogging constructor.
     */
    public function __construct()
    {
        $this->date = date('Y-m-d H:i:s');
        $this->user = getUserIdSession();
        $this->userName = getUserEntitled();

        if ($this->checkLogFile()) {
            $this->pathLogFile = WEB_SYSTEM_PATH . $this->logFile;
        }
    }

    /**
     * @return bool
     */
    private function checkLogFile()
    {

        if (!file_exists(WEB_SYSTEM_PATH . $this->logFile)) {
            if (false === fopen(WEB_SYSTEM_PATH . $this->logFile, 'a+')) {
                return false;
            }
        }
        return true;
    }


    /**
     * @param $text
     */
    public function write($text)
    {
        if (!is_null($this->pathLogFile)) {
            $this->message = '[' . $this->date . '] | ' . $this->user . ' | ' . $this->userName . ' | ' . $text . ';' . PHP_EOL;

            $appLogFile = fopen($this->pathLogFile, 'a');
            fwrite($appLogFile, $this->message);
            fclose($appLogFile);
        }
    }
}