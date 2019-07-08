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
        //self::updateTable();
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
     * @param $tableName
     * @return bool
     */
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

    /**
     * @return array
     */
    public static function updateTable()
    {
        /*
        * Put name, description, slug and menuName in other language
        * INSERT INTO `appoe_plugin_cms_content` (`idCms`, `type`, `metaKey`, `metaValue`, `lang`, `created_at`) SELECT idCms, "HEADER", "name", metaValue, "en", CURDATE() FROM `appoe_plugin_cms_content` WHERE metaKey = "name" AND lang = "fr";
        * INSERT INTO `appoe_plugin_cms_content` (`idCms`, `type`, `metaKey`, `metaValue`, `lang`, `created_at`) SELECT idCms, "HEADER", "description", metaValue, "en", CURDATE() FROM `appoe_plugin_cms_content` WHERE metaKey = "description" AND lang = "fr";
        * INSERT INTO `appoe_plugin_cms_content` (`idCms`, `type`, `metaKey`, `metaValue`, `lang`, `created_at`) SELECT idCms, "HEADER", "slug", metaValue, "en", CURDATE() FROM `appoe_plugin_cms_content` WHERE metaKey = "slug" AND lang = "fr";
       * INSERT INTO `appoe_plugin_cms_content` (`idCms`, `type`, `metaKey`, `metaValue`, `lang`, `created_at`) SELECT `idCms`, "HEADER", "menuName", `metaValue`, "fr", CURDATE() FROM `appoe_plugin_cms_content` WHERE metaKey = "name" AND type = "HEADER" AND lang = "fr"
       */

        $sqlToUpdate = array(
            'INSERT INTO `appoe_menu` (`id`, `slug`, `name`, `min_role_id`, `statut`, `parent_id`, `order_menu`, `pluginName`, `updated_at`) VALUES
                    (23, "preferences", "préférences", 3, 0, 10, 23, NULL, "2018-01-04 08:31:39")',
            'ALTER TABLE `appoe_plugin_cms_content` ADD `type` VARCHAR(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT "BODY" AFTER `idCms`',
            'ALTER TABLE `appoe_plugin_cms_content` CHANGE `metaKey` `metaKey` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL',
            'INSERT INTO `appoe_plugin_cms_content` (`idCms`, `type`, `metaKey`, `metaValue`, `lang`, `created_at`) SELECT id, "HEADER", "name", name, "fr", CURDATE() FROM `appoe_plugin_cms`;',
            'INSERT INTO `appoe_plugin_cms_content` (`idCms`, `type`, `metaKey`, `metaValue`, `lang`, `created_at`) SELECT `idCms`, "HEADER", "menuName", `metaValue`, "fr", CURDATE() FROM `appoe_plugin_cms_content` WHERE metaKey = "name" AND type = "HEADER" AND lang = "fr"',
            'INSERT INTO `appoe_plugin_cms_content` (`idCms`, `type`, `metaKey`, `metaValue`, `lang`, `created_at`) SELECT id, "HEADER", "description", description, "fr", CURDATE() FROM `appoe_plugin_cms`',
            'INSERT INTO `appoe_plugin_cms_content` (`idCms`, `type`, `metaKey`, `metaValue`, `lang`, `created_at`) SELECT id, "HEADER", "slug", slug, "fr", CURDATE() FROM `appoe_plugin_cms`;',
            'ALTER TABLE `appoe_plugin_cms_content` DROP INDEX idCms',
            'ALTER TABLE `appoe_plugin_cms_content` ADD UNIQUE (`idCms`, `type`, `metaKey`, `lang`)',
            'ALTER TABLE `appoe_plugin_cms` DROP `name`, DROP `description`, DROP `slug`, DROP `content`;',
            'ALTER TABLE `appoe_plugin_cms` ADD `filename` VARCHAR (255) NOT NULL AFTER `type`;',
            'UPDATE `appoe_plugin_cms` SET `filename` = "index" WHERE `id` = 11;',
            'ALTER TABLE `appoe_plugin_cms` DROP INDEX type',
            'ALTER TABLE `appoe_plugin_cms` ADD UNIQUE(`type`, `filename`);',
            'UPDATE `appoe_plugin_cms` AS C SET C.filename = (SELECT CC.metaValue FROM appoe_plugin_cms_content AS CC WHERE CC.idCms = C.id AND CC.type = "HEADER" AND CC.metaKey = "slug") WHERE 1;',
            'ALTER TABLE `appoe_plugin_cms_menu` CHANGE `name` `name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;',
            'CREATE TABLE IF NOT EXISTS `appoe_files_content` (
  				`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (`id`),
  				`fileId` INT(11) UNSIGNED NOT NULL,
  				`title` VARCHAR(255) NOT NULL,
  				`description` TEXT NULL DEFAULT NULL,
  				`lang` VARCHAR(10) NOT NULL,
  				UNIQUE (`fileId`, `lang`),
  				`userId` int(11) UNSIGNED NOT NULL,
                `created_at` date NOT NULL,
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;',
            'INSERT INTO appoe_files_content (fileId, title, description) 
            SELECT id, title, description FROM appoe_files;',
            'UPDATE appoe_files_content SET lang = "fr", userId = "0", created_at = NOW();',
            'ALTER TABLE `appoe_files` DROP `title`, DROP `description`;'
        );

        $results = array();
        foreach ($sqlToUpdate as $sql) {
            $stmt = self::$dbh->prepare($sql);
            $stmt->execute();
            $error = $stmt->errorInfo();
            if ($error[0] != '00000') {
                $results[] = false;
            }
        }

        return $results;
    }

    /**
     * @param $tableName
     * @return bool
     */
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

    /**
     * @return bool|array
     */
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
                return $stmt->fetchAll(PDO::FETCH_OBJ);
            }
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