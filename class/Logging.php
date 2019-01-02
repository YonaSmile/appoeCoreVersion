<?php

namespace App;
class Logging
{
    private $id;
    private $date;
    private $user;
    private $userName;
    private $type = 'APP';
    private $status = 'info';
    private $context;
    private $message;

    private $dbh = null;

    public function __construct($type = null, $status = null, $context = null, $message = null)
    {
        if (is_null($this->dbh)) {
            $this->dbh = \App\DB::connect();
        }

        $this->date = date('Y-m-d H:i:s');
        $this->user = getUserIdSession();
        $this->userName = getUserEntitled();

        if ($type && $status && $context && $message) {

            $this->type = $type;
            $this->status = $status;
            $this->context = $context;
            $this->message = $message;
            $this->save();
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param int $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param string $context
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * @return null
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param null $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function createTable()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `appoe_logging` (
  					`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                	PRIMARY KEY (`id`),
                	`date` DATETIME NOT NULL,
                	`user` INT(11) UNSIGNED NOT NULL,
                    `userName` VARCHAR(250) NOT NULL,
                    `type` VARCHAR(150) NOT NULL,
                    `status` VARCHAR(150) NOT NULL,
                    `context` VARCHAR(255) NULL DEFAULT NULL,
                    `message` TEXT NOT NULL,
                    UNIQUE (`date`, `user`, `context`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

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

        $sql = 'SELECT * FROM appoe_logging WHERE id = :id';

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
     * @return bool
     */
    public function save()
    {
        $sql = 'INSERT INTO appoe_logging (date, user, userName, type, status, context, message) 
        VALUES(:date, :user, :userName, :type, :status, :context, :message)';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':date', $this->date);
        $stmt->bindParam(':user', $this->user);
        $stmt->bindParam(':userName', $this->userName);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':context', $this->context);
        $stmt->bindParam(':message', $this->message);
        $stmt->execute();
        $error = $stmt->errorInfo();

        if ($error[0] != '00000') {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @return bool
     */
    public function update()
    {
        $sql = 'UPDATE appoe_logging 
        SET date = :date, user = :user, userName = :userName, type = :type, status = :status, context = :context, message = :message 
        WHERE id = :id';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':date', $this->date);
        $stmt->bindParam(':user', $this->user);
        $stmt->bindParam(':userName', $this->userName);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':context', $this->context);
        $stmt->bindParam(':message', $this->message);
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
     * @return bool
     */
    public function delete()
    {
        $sql = 'DELETE FROM appoe_logging WHERE id = :id';

        $stmt = $this->dbh->prepare($sql);
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