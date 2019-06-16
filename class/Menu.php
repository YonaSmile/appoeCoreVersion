<?php

namespace App;

use PDO;

class Menu
{
    private $dbh = null;

    public function __construct()
    {
        if (is_null($this->dbh)) {
            $this->dbh = DB::connect();
        }
    }

    public function createTable()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `appoe_menu` (
  					`id` INT(11) NOT NULL AUTO_INCREMENT,
                	PRIMARY KEY (`id`),
                	`slug` VARCHAR(40) NOT NULL,
                	UNIQUE KEY (`slug`),
  					`name` VARCHAR(50) NOT NULL,
  					`min_role_id` INT(11) NOT NULL,
  					`statut` INT(11) NOT NULL,
  					`parent_id` INT(11) NOT NULL,
  					`order_menu` INT(11) DEFAULT NULL,
  					`pluginName` VARCHAR(200) DEFAULT NULL,
                	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
				    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=30;
				    INSERT INTO `appoe_menu` (`id`, `slug`, `name`, `min_role_id`, `statut`, `parent_id`, `order_menu`, `pluginName`, `updated_at`) VALUES
                    (11, "index", "Tableau de bord", 1, 1, 10, 1, NULL, "2018-01-05 11:28:14"),
                    (12, "users", "Utilisateurs", 1, 1, 10, 19, NULL, "2018-01-04 08:31:39"),
                    (13, "setting", "Réglages", 11, 0, 10, 13, NULL, "2018-01-04 09:04:00"),
                    (14, "updateCategories", "Catégories", 11, 1, 10, 2, NULL, "2018-01-05 11:28:14"),
                    (15, "updateMedia", "Média", 1, 1, 10, 3, NULL, "2018-01-05 11:28:14"),
                    (16, "updatePermissions", "Permissions", 11, 0, 10, 16, NULL, "2018-01-05 11:28:14"),
                    (20, "allUsers", "Tous les utilisateurs", 1, 1, 12, 20, NULL, "2018-01-04 08:31:39"),
                    (21, "addUser", "Nouvel utilisateur", 2, 1, 12, 21, NULL, "2018-01-04 08:31:39"),
                    (22, "updateUser", "Mise à jour de l\'utilisateur", 1, 0, 12, 22, NULL, "2018-01-04 08:31:39"),
                    (23, "preferences", "préférences", 3, 0, 10, 23, NULL, "2018-01-04 08:31:39");';

        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $error = $stmt->errorInfo();
        if ($error[0] != '00000') {
            return false;
        }

        return true;
    }


    public function displayMenuAll($id = '')
    {

        if (empty($id)) {
            $sql = 'SELECT * FROM appoe_menu ORDER BY order_menu ASC, parent_id ASC';
            $stmt = $this->dbh->prepare($sql);
        } else {
            $sql = 'SELECT * FROM appoe_menu WHERE id = :id';
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(':id', $id);
        }

        $stmt->execute();
        $error = $stmt->errorInfo();
        if ($error[0] != '00000') {
            return false;
        } else {

            while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                $data[] = $row;
            }
            if (isset($data)) {
                return $data;
            } else {
                return false;
            }
        }
    }


    public function displayMenu($role, $id = '')
    {
        if (is_numeric($role)) {

            if (!empty($id)) {
                $sql = 'SELECT * FROM appoe_menu WHERE min_role_id <= :role AND statut = 1 AND parent_id = :id ORDER BY order_menu ASC';
                $stmt = $this->dbh->prepare($sql);
                $stmt->bindParam(':id', $id);

            } else {
                $sql = 'SELECT * FROM appoe_menu WHERE min_role_id <= :role AND statut = 1 ORDER BY order_menu ASC';
                $stmt = $this->dbh->prepare($sql);
            }
            $stmt->bindParam(':role', $role);
            $stmt->execute();
            $error = $stmt->errorInfo();

            if ($error[0] == '00000') {

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $data[] = $row;
                }

                if (isset($data)) {
                    return $data;
                }
            }
        }
        return false;
    }


    public function displayMenuBySlug($slug)
    {

        $sql = 'SELECT * FROM appoe_menu WHERE slug = :slug';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':slug', $slug);

        $stmt->execute();
        $error = $stmt->errorInfo();
        if ($error[0] != '00000') {
            return false;
        } else {
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
    }

    public function insertMenu($id, $slug, $name, $minRole, $statut, $parent, $pluginName = NULL, $order_menu = '')
    {

        if (empty($order_menu)) {
            $order_menu = null;
        } elseif ($parent == 10) {
            $order_menu = $this->ordonnerMenu();
        }

        $sql = 'INSERT INTO appoe_menu (id, slug, name, min_role_id, statut, parent_id, order_menu, pluginName) 
        VALUES (:id, :slug, :name, :min_role_id, :statut, :parent_id, :order_menu, :pluginName)';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':slug', $slug);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':min_role_id', $minRole);
        $stmt->bindParam(':statut', $statut);
        $stmt->bindParam(':parent_id', $parent);
        $stmt->bindParam(':order_menu', $order_menu);
        $stmt->bindParam(':pluginName', $pluginName);
        $stmt->execute();
        $error = $stmt->errorInfo();

        if ($error[0] != '00000') {
            return false;
        } else {
            appLog('Creating menu -> id: ' . $id . ' slug: ' . $slug . ' name: ' . $name . ' min role id: ' . $minRole . ' statut: ' . $statut . ' parent id: ' . $parent . ' order: ' . $order_menu . ' plugin: ' . $pluginName);
            return true;
        }

    }

    public function updateMenu($id, $name, $slug, $minRole, $statut, $parent, $order_menu = null, $pluginName = null)
    {

        $sql = 'UPDATE appoe_menu 
        SET name = :name, slug = :slug, min_role_id = :min_role_id, statut = :statut, parent_id = :parent_id, order_menu = :order_menu, pluginName = :pluginName 
        WHERE id = :id';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':slug', $slug);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':min_role_id', $minRole);
        $stmt->bindParam(':statut', $statut);
        $stmt->bindParam(':parent_id', $parent);
        $stmt->bindParam(':order_menu', $order_menu);
        $stmt->bindParam(':pluginName', $pluginName);
        $stmt->execute();
        $error = $stmt->errorInfo();

        if ($error[0] != '00000') {
            return false;
        } else {
            appLog('Updating menu -> id: ' . $id . ' slug: ' . $slug . ' name: ' . $name . ' min role id: ' . $minRole . ' statut: ' . $statut . ' parent id: ' . $parent . ' order: ' . $order_menu . ' plugin: ' . $pluginName);
            return true;
        }

    }


    public function deleteMenu($id)
    {
        $sql = 'DELETE FROM appoe_menu WHERE id = :id';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $error = $stmt->errorInfo();

        if ($error[0] != '00000') {
            return false;
        } else {
            appLog('Delete menu -> id: ' . $id);
            return true;
        }
    }

    public function deletePluginMenu($pluginName)
    {

        $sql = 'DELETE FROM appoe_menu WHERE pluginName = :pluginName';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':pluginName', $pluginName);
        $stmt->execute();
        $error = $stmt->errorInfo();

        if ($error[0] != '00000') {
            return false;
        } else {
            appLog('Delete menu -> plugin name: ' . $pluginName);

            return true;
        }
    }


    public function checkUserPermission($user_session_role, $slug)
    {
        $sql = 'SELECT slug, min_role_id FROM appoe_menu WHERE slug = :slug';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();

        $count = $stmt->rowCount();
        $error = $stmt->errorInfo();
        if ($error[0] != '00000') {
            return false;
        } else {
            if ($count > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row['min_role_id'] <= $user_session_role) {
                    return true;
                }
            }
            return false;
        }
    }

    public function ordonnerMenu()
    {
        $num = 3;
        $sql = 'SELECT order_menu FROM appoe_menu WHERE parent_id = 10 ORDER BY order_menu ASC';
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $error = $stmt->errorInfo();

        if ($error[0] != '00000') {
            return false;
        } else {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row['order_menu'] >= $num) {
                    $num = $row['order_menu'] + 1;
                }
            }

            return $num;
        }
    }

    public function cleanText($filename)
    {

        $special = array(
            ' ', '\'', '"', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ',
            'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç',
            'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý'
        );

        $normal = array(
            '-', '-', '-', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n',
            'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C',
            'E', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y'
        );

        $filename = str_replace($special, $normal, $filename);

        return strtolower($filename);
    }
}