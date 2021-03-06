<?php

namespace App;

use PDO;
use PDOException;

class DB
{
    private static $instance;
    protected static $dbh = null;


    public function __construct()
    {
        self::$dbh = self::connect();
    }

    /**
     * @return null
     */
    public static function connect()
    {
        if (is_null(self::$dbh)) {

            $attempts = NUM_OF_ATTEMPTS;

            while ($attempts > 0) {
                try {
                    self::$dbh = new PDO(DBPATH, DBUSER, DBPASS);
                    $attempts = 0;

                } catch (PDOException $e) {

                    $attempts--;
                    sleep(1);
                }
            }
        }
        return self::$dbh;
    }

    /**
     * @return DB
     */
    public static function initialize()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param $sql
     * @param array $params
     * @return bool
     */
    public static function exec($sql, array $params = array())
    {
        self::$dbh = self::connect();

        $stmt = self::$dbh->prepare($sql);
        $stmt->execute($params);
        $error = $stmt->errorInfo();
        if ($error[0] != '00000') {
            setSqlError($error);
            return false;
        } else {
            $stmt->lastInsertId = self::$dbh->lastInsertId();
            return $stmt;
        }
    }

    /**
     * @param $tableName
     * @return bool
     */
    public static function isTableExist($tableName)
    {
        $sql = 'DESCRIBE ' . $tableName;
        return !DB::exec($sql) ? false : true;
    }

    /**
     * @param $tableName
     * @return bool
     */
    public static function checkTable($tableName)
    {
        $sql = 'SHOW TABLES LIKE :tableName';
        $return = self::exec($sql, array(':tableName' => '%' . $tableName . '%'));

        if ($return) {
            if ($return->rowCount() > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $tableName
     * @return bool
     */
    public static function deleteTable($tableName)
    {
        $sql = 'DROP TABLE IF EXISTS ' . $tableName;
        $return = self::exec($sql, array());

        if ($return) {
            return true;
        }
        return false;
    }

    /**
     * @return bool|array
     */
    public static function getTables()
    {
        $sql = 'SHOW TABLES';
        $return = self::exec($sql, array());

        if ($return->rowCount() > 0) {
            return $return->fetchAll(PDO::FETCH_OBJ);
        }

        return false;
    }

    /**
     * @param $folder
     * @param $name
     * DataBase BuckUp
     */
    public static function backup($folder, $name = 'db')
    {
        $file = getenv('DOCUMENT_ROOT') . '/app/backup/' . $folder . '/' . $name . '.sql.gz';
        system('mysqldump --no-tablespace --opt -h' . DBHOST . ' -u' . DBUSER . ' -p"' . DBPASS . '" ' . DBNAME . ' | gzip > ' . $file);
    }
}