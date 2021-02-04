<?php

namespace App;

use PDO;

class Option
{
    private $tableName = '`' . TABLEPREFIX . 'appoe_options`';
    private $id;
    private $type;
    private $description = null;
    private $key;
    private $val;
    private $created_at;
    private $updated_at;

    /**
     * Option constructor.
     * @param array $data
     * @return bool|array|void
     */
    public function __construct(array $data = array())
    {
        if (!DB::isTableExist($this->tableName)) {
            $this->createTable();
        }

        if (!empty($data['type']) && !empty($data['key']) && !empty($data['val'])) {

            $this->type = $data['type'];
            $this->key = $data['key'];
            $this->val = $data['val'];

            if (!$this->exist()) {
                return $this->save();
            }
            return false;
        }
    }

    /**
     * @return bool
     */
    public function createTable()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS ' . $this->tableName . ' (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (`id`),
                `type` varchar(100) NOT NULL,
                `description` varchar(255) NULL DEFAULT NULL,
                `key` varchar(255) NOT NULL,
                `val` TEXT NOT NULL,
                UNIQUE (`type`, `key`),
                `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
				INSERT INTO ' . $this->tableName . ' (`id`, `type`, `description`, `key`, `val`, `created_at`, `updated_at`) VALUES
                (1, "PREFERENCE", "Mode maintenance", "maintenance", "false", NOW(), NOW()),
                (2, "PREFERENCE", "Forcer le site en HTTPS", "forceHTTPS", "false", NOW(), NOW()),
                (3, "PREFERENCE", "Autoriser la mise en cache des fichiers", "cacheProcess", "false", NOW(), NOW()),
                (4, "PREFERENCE", "Autoriser le travail sur la même page", "sharingWork", "false", NOW(), NOW()),
                (5, "PREFERENCE", "Autoriser l\'API", "allowApi", "", NOW(), NOW()),
                (6, "PREFERENCE", "Clé API", "apiToken", "", NOW(), NOW()),
                (7, "PREFERENCE", "Adresse Email par défaut", "defaultEmail", "", NOW(), NOW());';
        return !DB::exec($sql) ? false : true;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param null $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return null
     */
    public function getVal()
    {
        return $this->val;
    }

    /**
     * @param null $val
     */
    public function setVal($val)
    {
        $this->val = $val;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return bool|array
     */
    public function show()
    {
        $sql = 'SELECT * FROM ' . $this->tableName . ' WHERE `id` = :id';
        $params = array(':id' => $this->id);
        return (DB::exec($sql, $params))->fetch(PDO::FETCH_OBJ);
    }

    /**
     * @return bool|array
     */
    public function showByType()
    {
        $sql = 'SELECT * FROM ' . $this->tableName . ' WHERE `type` = :type';
        $params = array(':type' => $this->type);
        return (DB::exec($sql, $params))->fetch(PDO::FETCH_OBJ);
    }

    /**
     * @return bool|array
     */
    public function showByKey()
    {
        $sql = 'SELECT * FROM ' . $this->tableName . ' WHERE `type` = :type AND `key` = :key';
        $params = array(':type' => $this->type, ':key' => $this->key);
        return (DB::exec($sql, $params))->fetch(PDO::FETCH_OBJ);
    }

    /**
     * @return bool
     */
    public function save()
    {
        $sql = 'INSERT INTO ' . $this->tableName . ' (`type`, `description`, `key`, `val`) VALUES (:type, :description, :key, :val)';
        $params = array(':type' => $this->type, ':description' => $this->description, ':key' => $this->key, ':val' => $this->val);
        if (DB::exec($sql, $params)) {
            appLog('Add option -> user: ' . getUserLogin() . ' type: ' . $this->type . ' key:' . $this->key . ' val:' . $this->val);
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function update()
    {
        $sql = 'UPDATE ' . $this->tableName . ' SET `val` = :val WHERE `type` = :type AND `key` = :key';
        $params = array(':type' => $this->type, ':key' => $this->key, ':val' => $this->val);
        if (DB::exec($sql, $params)) {
            appLog('Update option -> user: ' . getUserLogin() . ' type: ' . $this->type . ' key:' . $this->key . ' val:' . $this->val);
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function exist()
    {
        $sql = 'SELECT * FROM ' . $this->tableName . ' WHERE `type` = :type AND `key` = :key';
        $params = array(':type' => $this->type, ':key' => $this->key);
        return (DB::exec($sql, $params))->fetch(PDO::FETCH_OBJ);
    }

    /**
     * @return bool
     */
    public function delete()
    {
        $sql = 'DELETE FROM ' . $this->tableName . ' WHERE `id` = :id';
        if (DB::exec($sql, [':id' => $this->id])) {
            appLog('Delete option -> user: ' . getUserLogin() . ' id: ' . $this->id);
            return true;
        }
        return false;
    }
}