<?php

namespace App;

use PDO;

class Users
{
    private $id;
    private $login;
    private $password;
    private $role;
    private $email;
    private $nom;
    private $prenom;
    private $options = null;
    private $statut = 1;

    private $dbh = null;

    public function __construct($userId = null)
    {
        if (is_null($this->dbh)) {
            $this->dbh = DB::connect();
        }

        if (!is_null($userId)) {
            $this->id = $userId;
            $this->show();
        }
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = intval($id);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return strlen($this->role) < 3 ? $this->role : Shinoui::Decrypter($this->role);
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = strlen($role) > 3 ? $role : Shinoui::Crypter($role);
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return null
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param null $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return mixed
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * @param mixed $statut
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }


    public function createTable()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `appoe_users` (
  					`id` INT(11) NOT NULL AUTO_INCREMENT,
                	PRIMARY KEY (`id`),
                	`login` VARCHAR(70) NOT NULL,
                	UNIQUE KEY (`login`),
  					`password` VARCHAR(255) NOT NULL,
  					`role` VARCHAR(255) NOT NULL,
  					`email` VARCHAR(200) NULL DEFAULT NULL,
  					`nom` VARCHAR(100) NOT NULL,
  					`prenom` VARCHAR(100) NOT NULL,
  					`options` TEXT NULL DEFAULT NULL,
  					`statut` TINYINT(1) NOT NULL DEFAULT 1,
                    `created_at` DATE NULL,
                	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=173812;';
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $error = $stmt->errorInfo();
        if ($error[0] != '00000') {
            return false;
        }

        return true;
    }

    /**
     * Authenticate User
     * Require email & password
     * @return bool
     */
    public function authUser()
    {
        $sql = 'SELECT * FROM appoe_users WHERE BINARY login = :login AND statut = TRUE';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':login', $this->login);
        $stmt->execute();
        $count = $stmt->rowCount();
        $error = $stmt->errorInfo();
        if ($error[0] != '00000') {
            return false;
        } else {
            if ($count == 1) {
                $row = $stmt->fetch(PDO::FETCH_OBJ);
                if (password_verify($this->password, $row->password)) {
                    if (password_needs_rehash($row->password, PASSWORD_DEFAULT)) {
                        $this->updatePassword();
                    }
                    $this->feed($row);

                    return true;
                } else {
                    return false; // Le mot de passe n'est pas correct;
                }
            } else {
                return false; // L'utilisateur n'existe pas;
            }
        }
    }

    /**
     * Get User by Id
     *
     * @return bool
     */
    public function show()
    {
        $sql = 'SELECT * FROM appoe_users WHERE id = :id';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        $count = $stmt->rowCount();
        $error = $stmt->errorInfo();
        if ($error[0] != '00000') {
            return false;
        } else {
            if ($count == 1) {
                $row = $stmt->fetch(PDO::FETCH_OBJ);
                $this->feed($row);

                return true;

            } else {
                return false;
            }
        }
    }

    /**
     * @param $minStatus
     * @return bool|array
     */
    public function showAll($minStatus = true)
    {

        $sqlStatus = $minStatus ? ' statut >= :statut ' : ' statut = :statut ';
        $sql = 'SELECT * FROM appoe_users WHERE ' . $sqlStatus . ' ORDER BY statut DESC, created_at ASC';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':statut', $this->statut);
        $stmt->execute();
        $count = $stmt->rowCount();
        $error = $stmt->errorInfo();
        if ($error[0] != '00000') {
            return false;
        } else {
            if ($count > 0) {
                return $stmt->fetchAll(PDO::FETCH_OBJ);

            } else {
                return false;
            }
        }
    }

    /**
     * Insert User into DataBase
     * @return bool
     */
    public function save()
    {
        $hash_password = password_hash($this->password, PASSWORD_DEFAULT);
        $sql = 'INSERT INTO appoe_users (login, email, password, role,  nom, prenom, options, created_at) 
                    VALUES (:login, :email, :password, :role, :nom, :prenom, :options, CURDATE())';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':login', $this->login);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $hash_password);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':prenom', $this->prenom);
        $stmt->bindParam(':options', $this->options);
        $stmt->execute();

        $userId = $this->dbh->lastInsertId();

        $error = $stmt->errorInfo();
        if ($error[0] != '00000') {
            return false;
        } else {
            $this->setId($userId);
            appLog('Creating user -> login: ' . $this->login . ' email: ' . $this->email . ' role: ' . $this->role . ' nom: ' . $this->nom . ' prenom: ' . $this->prenom . ' options: ' . $this->options);
            return true;
        }

    }

    public function update()
    {
        $sql = 'UPDATE appoe_users 
        SET login = :login, email = :email, nom = :nom, prenom = :prenom, role = :role, statut = :statut 
        WHERE id = :id';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':login', $this->login);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':prenom', $this->prenom);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':statut', $this->statut);
        $stmt->execute();
        $error = $stmt->errorInfo();
        if ($error[0] != '00000') {
            return false;
        } else {
            appLog('Updating user -> id: ' . $this->id . ' login: ' . $this->login . ' email: ' . $this->email . ' role: ' . $this->role . ' nom: ' . $this->nom . ' prenom: ' . $this->prenom . ' statut: ' . $this->statut);
            return true;
        }
    }

    /**
     * @param bool $login
     * if $login is true, ignoring User login from results
     *
     * @return bool
     */
    public function exist($login = false)
    {
        $sql = 'SELECT login FROM appoe_users WHERE BINARY login = :login';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':login', $this->login);
        $stmt->execute();
        $count = $stmt->rowCount();
        $error = $stmt->errorInfo();
        if ($error[0] != '00000') {
            return false;
        } else {
            if ($count == 0) {
                return false;
            } else {
                if ($login && $count == 1) {
                    $row = $stmt->fetch(PDO::FETCH_OBJ);
                    if ($row->login == $this->login) {
                        return false;
                    }
                }

                return true;
            }
        }

    }

    /**
     * Update user Password with new hash algorithme
     * @return bool
     */
    public function updatePassword()
    {
        $hash_password = password_hash($this->password, PASSWORD_DEFAULT);
        $sql = 'UPDATE appoe_users SET password = :password WHERE BINARY login = :login';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':password', $hash_password);
        $stmt->bindParam(':login', $this->login);
        $stmt->execute();
        $error = $stmt->errorInfo();
        if ($error[0] != '00000') {
            return false;
        } else {
            appLog('Updating user password -> login: ' . $this->login);
            return true;
        }
    }

    public function delete()
    {
        $this->statut = false;
        if ($this->update()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Feed class attributs
     *
     * @param $data
     */
    public function feed($data)
    {
        foreach ($data as $attribut => $value) {
            $method = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $attribut)));

            if (is_callable(array($this, $method))) {
                $this->$method($value);
            }
        }
    }
}