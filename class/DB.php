<?php

namespace App;
class DB
{
    private static $instance;
    protected static $dbh = null;

    public function __construct()
    {
        self::$dbh = self::connect();
    }

    public static function connect()
    {

        if (is_null(self::$dbh)) {

            $attempts = NUM_OF_ATTEMPTS;

            while ($attempts > 0) {

                try {

                    self::$dbh = new \PDO(DBPATH, DBUSER, DBPASS);
                    $attempts = 0;

                } catch (\PDOException $e) {

                    $attempts--;
                    sleep(1);
                }
            }
        }

        return self::$dbh;
    }

    public static function initialize()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function checkTable($tableName)
    {
        $sql = 'SHOW TABLES LIKE :tableName';
        $stmt = self::$dbh->prepare($sql);
        $stmt->execute(array(':tableName' => '%' . $tableName . '%'));
        $error = $stmt->errorInfo();
        $count = $stmt->rowCount();
        if ($error[0] != '00000') {
            return false;
        } else {
            if ($count > 0) {
                return true;
            }
        }

        return false;
    }

    public static function deleteTable($tableName)
    {
        $sql = 'DROP TABLE IF EXISTS ' . $tableName;
        $stmt = self::$dbh->prepare($sql);
        $stmt->execute();
        $error = $stmt->errorInfo();
        if ($error[0] != '00000') {
            return $error;
        } else {
            return true;
        }
    }

    public static function getTables()
    {
        $sql = 'SHOW TABLES';
        $stmt = self::$dbh->prepare($sql);
        $stmt->execute();
        $error = $stmt->errorInfo();
        $count = $stmt->rowCount();
        if ($error[0] != '00000') {
            return false;
        } else {
            if ($count > 0) {
                return $stmt->fetchAll(\PDO::FETCH_OBJ);
            }
        }

        return false;
    }

    public static function backupTables()
    {
        system('mysqldump --no-tablespace --opt -h' . DBHOST . ' -u' . DBUSER . ' -p"' . DBPASS . '" ' . DBNAME . ' | gzip > ' . getenv('DOCUMENT_ROOT') . '/app/db-buckup-' . date('Y-m-d-H-i') . '.sql.gz');
    }
}