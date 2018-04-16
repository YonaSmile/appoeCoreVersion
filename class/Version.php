<?php

namespace App;
class Version
{
    private static $file;
    private static $date;
    private static $version;
    private static $renew = false;
    private static $data;

    /**
     * @return mixed
     */
    public static function getFile()
    {
        return self::$file;
    }

    /**
     * @param mixed $file
     */
    public static function setFile($file)
    {
        self::$file = $file;
    }

    /**
     * @return mixed
     */
    public static function getDate()
    {
        return self::$date;
    }

    /**
     *
     */
    public static function initializeDate()
    {
        self::$date = date('Y-m-d H:i');
    }

    /**
     * @return mixed
     */
    public static function getVersion()
    {
        return self::$version;
    }

    /**
     * @param $version
     */
    public static function setVersion($version)
    {
        self::$version = $version;
    }

    /**
     * @return mixed
     */
    public static function getRenew()
    {
        return self::$renew;
    }

    /**
     * @param $renew
     */
    public static function setRenew($renew)
    {
        self::$renew = $renew;
    }

    /**
     * @return mixed
     */
    public static function getData()
    {
        return self::$data;
    }

    /**
     *
     */
    public static function initializeData()
    {
        self::$data = array('date' => self::$date, 'version' => self::$version, 'renew' => self::$renew);
    }

    /**
     *
     */
    public static function show()
    {
        if (!empty(self::$file)) {
            $json_file = file_get_contents(self::$file);
            $json = json_decode($json_file);

            if ($json_file && $json) {

                self::$date = $json->date;
                self::$version = $json->version;
                self::$renew = $json->renew;

                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public static function save()
    {
        $json_file = fopen(self::$file, 'w');

        if ($json_file) {
            self::initializeData();

            fwrite($json_file, json_encode(self::$data));
            fclose($json_file);
            return true;
        }

        return false;
    }

    // FUNCTIONS VERSIONS (4) x.x.x.x

    public static function updateXSversion()
    {
        $arr_versions = explode('.', self::$version);
        if (intval($arr_versions[3]) < 999) {
            $arr_versions[3] += 1;
        } elseif (intval($arr_versions[2]) < 999) {
            $arr_versions[2] += 1;
            $arr_versions[3] = 1;
        } elseif (intval($arr_versions[1]) < 999) {
            $arr_versions[1] += 1;
            $arr_versions[2] = 0;
            $arr_versions[3] = 1;
        } elseif (intval($arr_versions[0]) < 999) {
            $arr_versions[0] += 1;
            $arr_versions[1] = 0;
            $arr_versions[2] = 0;
            $arr_versions[3] = 1;
        }
        self::$version = implode('.', $arr_versions);
        self::$renew = true;
    }

    public static function updateSMversion()
    {
        $arr_versions = explode('.', self::$version);
        if (intval($arr_versions[2]) < 999) {
            $arr_versions[2] += 1;
            $arr_versions[3] = 1;
        } elseif (intval($arr_versions[1]) < 999) {
            $arr_versions[1] += 1;
            $arr_versions[2] = 0;
            $arr_versions[3] = 1;
        } elseif (intval($arr_versions[0]) < 999) {
            $arr_versions[0] += 1;
            $arr_versions[1] = 0;
            $arr_versions[2] = 0;
            $arr_versions[3] = 1;
        }
        self::$version = implode('.', $arr_versions);
        self::$renew = true;
    }

    public static function updateMDversion()
    {
        $arr_versions = explode('.', self::$version);
        if (intval($arr_versions[1]) < 999) {
            $arr_versions[1] += 1;
            $arr_versions[2] = 0;
            $arr_versions[3] = 1;
        } elseif (intval($arr_versions[0]) < 999) {
            $arr_versions[0] += 1;
            $arr_versions[1] = 0;
            $arr_versions[2] = 0;
            $arr_versions[3] = 1;
        }
        self::$version = implode('.', $arr_versions);
        self::$renew = true;
    }

    public static function updateLGversion()
    {
        $arr_versions = explode('.', self::$version);
        if (intval($arr_versions[0]) < 999) {
            $arr_versions[0] += 1;
            $arr_versions[1] = 0;
            $arr_versions[2] = 0;
            $arr_versions[3] = 1;
        }
        self::$version = implode('.', $arr_versions);
        self::$renew = true;
    }
}