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

        try {
            $stmt = self::$dbh->prepare($sql);
            $stmt->execute($params);
            $stmt->lastInsertId = self::$dbh->lastInsertId();
            return $stmt;
        } catch (Exception $e) {
            setSqlError($e->getMessage());
            return false;
        }
    }

    /**
     * @param $class
     * @return bool
     */
    public static function show($class)
    {
        $sql = 'SELECT * FROM ' . $class->tableName . ' WHERE `id` = :id';
        $params = array(':id' => $class->id);
        if ($return = self::exec($sql, $params)) {
            self::feed($class, $return->fetch(PDO::FETCH_OBJ));
            return true;
        }
        return false;
    }

    /**
     * @param $class
     * @return mixed
     */
    public static function showAll($class)
    {
        $sql = 'SELECT * FROM ' . $class->tableName . ' WHERE `status` = :status';
        $params = array(':status' => $class->status);
        if ($return = self::exec($sql, $params)) {
            return $return->fetchAll(PDO::FETCH_OBJ);
        }
        return false;
    }

    /**
     * @return bool
     */
    public static function save($class, $attr)
    {

        $params = array();
        $sql = 'INSERT INTO ' . $class->tableName . ' (' . implode(', ', $attr) . ') 
                VALUES (:' . implode(', :', $attr) . ')';
        foreach ($attr as $value) {
            $params[':' . $value] = $value ? $class->$value : null;
        }
        return self::exec($sql, $params);
    }

    /**
     * @return bool
     */
    public static function update($class, $attr)
    {

        $params = array();
        $sql = 'UPDATE ' . $class->tableName . ' SET ';
        foreach ($attr as $data) {
            $sql .= (current($attr) == $attr[0] ? '' : ', ') . $data . ' = :' . $data;
        }
        $sql .= 'WHERE id = :id';
        foreach ($attr as $value) {
            $params[':' . $value] = $value ? $class->$value : null;
        }
        return self::exec($sql, $params);
    }

    /**
     * Feed class attributs
     *
     * @param $class
     * @param $data
     */
    public static function feed($class, $data)
    {
        foreach ($data as $attribut => $value) {
            $method = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $attribut)));

            if (is_callable(array($class, $method))) {
                $class->$method($value);
            }
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