<?php

namespace App;
class Category
{
    private $id;
    private $type;
    private $name;
    private $parentId;
    private $status = 1;

    private $dbh = null;

    public function __construct($idCategory = null)
    {
        if (is_null($this->dbh)) {
            $this->dbh = \App\DB::connect();
        }

        if (!is_null($idCategory)) {
            $this->id = $idCategory;
            $this->show();
        }
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null $id
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
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param mixed $parentId
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }


    public function createTable()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `appoe_categories` (
  					`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                	PRIMARY KEY (`id`),
                    `type` VARCHAR(150) NOT NULL,
                    `name` VARCHAR(250) NOT NULL,
                    `parentId` INT(11) UNSIGNED NOT NULL,
                    UNIQUE (`type`, `name`, `parentId`),
                    `status` TINYINT(1) NOT NULL DEFAULT 1,
                	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=11;';

        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $error = $stmt->errorInfo();
        if ($error[0] != '00000') {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function show()
    {

        $sql = 'SELECT * FROM appoe_categories WHERE id = :id';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        $error = $stmt->errorInfo();
        if ($error[0] != '00000') {
            return false;
        } else {
            $row = $stmt->fetch(\PDO::FETCH_OBJ);
            $this->feed($row);

            return true;
        }
    }

    /**
     * @return array|bool
     */
    public function showByType()
    {

        $sql = 'SELECT * FROM appoe_categories WHERE type = :type AND status = 1 ORDER BY id ASC, parentId ASC';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':type', $this->type);

        $stmt->execute();
        $error = $stmt->errorInfo();

        if ($error[0] != '00000') {
            return false;
        } else {
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        }
    }

    /**
     * @param $categoriesCount
     * @return array|bool
     */
    public function showAll($categoriesCount = false)
    {

        $sql = 'SELECT * FROM appoe_categories WHERE status = 1 ORDER BY name ASC';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':type', $this->type);
        $stmt->execute();

        $count = $stmt->rowCount();
        $error = $stmt->errorInfo();

        if ($error[0] != '00000') {
            return false;
        } else {
            $data = $stmt->fetchAll(\PDO::FETCH_OBJ);

            return (!$categoriesCount) ? $data : $count;
        }
    }

    /**
     *
     * @return bool
     */
    public function save()
    {
        $sql = 'INSERT INTO appoe_categories (type, name, parentId) VALUES(:type, :name, :parentId)';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':parentId', $this->parentId);
        $stmt->execute();
        $error = $stmt->errorInfo();

        if ($error[0] != '00000') {
            return false;
        } else {
            return true;
        }
    }

    /**
     *
     * @return bool
     */
    public function update()
    {
        $sql = 'UPDATE appoe_categories SET name = :name, parentId = :parentId, status = :status WHERE id = :id';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':parentId', $this->parentId);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        $error = $stmt->errorInfo();

        if ($error[0] != '00000') {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param bool $forUpdate
     *
     * @return bool
     */
    public function notExist($forUpdate = false)
    {

        $sql = 'SELECT * FROM appoe_categories WHERE name = :name AND type = :type AND parentId = :parentId';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':parentId', $this->parentId);
        $stmt->execute();

        $count = $stmt->rowCount();
        $error = $stmt->errorInfo();
        if ($error[0] != '00000') {
            return false;
        } else {
            if ($count == 1) {

                $data = $stmt->fetch(\PDO::FETCH_OBJ);

                if ($forUpdate) {
                    if ($data->name == $this->name && $data->slug == $this->type) {
                        return true;
                    }
                }

                $this->feed($data);

                return false;
            } else {
                return true;
            }
        }
    }

    /**
     *
     * @return bool
     */
    public function delete()
    {
        $this->status = 0;
        return $this->update();
    }

    /**
     * Feed class attributs
     * @param $data
     */
    public function feed($data)
    {
        if (isset($data)) {
            foreach ($data as $attribut => $value) {
                $method = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $attribut)));

                if (is_callable(array($this, $method))) {
                    $this->$method($value);
                }
            }
        }
    }
}