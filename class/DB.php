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
            return false;
        } else {
            return $stmt;
        }
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
     * @return array
     */
    public static function updateTable()
    {
        /*
         UPDATE appoe_plugin_cms_content
         SET `metaValue` = REPLACE(`metaValue`, 'http://', 'https://')
         WHERE `metaValue` LIKE '%http://%'

        INSERT INTO `appoe_plugin_cms_content` (`idCms`, `type`, `metaKey`, `metaValue`, `lang`, `created_at`)
        SELECT idCms, "HEADER", "name", metaValue, "en", CURDATE() FROM `appoe_plugin_cms_content` WHERE metaKey = "name" AND lang = "fr";
        INSERT INTO `appoe_plugin_cms_content` (`idCms`, `type`, `metaKey`, `metaValue`, `lang`, `created_at`)
        SELECT idCms, "HEADER", "description", metaValue, "en", CURDATE() FROM `appoe_plugin_cms_content` WHERE metaKey = "description" AND lang = "fr";
        INSERT INTO `appoe_plugin_cms_content` (`idCms`, `type`, `metaKey`, `metaValue`, `lang`, `created_at`)
        SELECT idCms, "HEADER", "slug", metaValue, "en", CURDATE() FROM `appoe_plugin_cms_content` WHERE metaKey = "slug" AND lang = "fr";
        INSERT INTO `appoe_plugin_cms_content` (`idCms`, `type`, `metaKey`, `metaValue`, `lang`, `created_at`)
        SELECT `idCms`, "HEADER", "menuName", `metaValue`, "en", CURDATE() FROM `appoe_plugin_cms_content` WHERE metaKey = "name" AND type = "HEADER" AND lang = "fr"

        DELETE n1 FROM appoe_plugin_itemGlue_articles_content n1,
        appoe_plugin_itemGlue_articles_content n2
        WHERE n1.id > n2.id
        AND n1.idArticle = n2.idArticle
        AND n1.type = n2.type
        AND n1.lang = n2.lang
        AND n2.id < 5000
        */

        /*
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
            'UPDATE `appoe_plugin_cms` SET `filename` = "index" WHERE `appoe_plugin_cms`.`id` = 11;',
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
        'DELETE FROM `appoe_menu` WHERE `id` = 603;'
        );
         */

        /*$testedLang = array(LANG);

        foreach (getLangs() as $minLang => $largeLang) {
            if (!in_array($minLang, $testedLang)) {
                $testedLang[] = $minLang;
                $sqlAdded[] = 'INSERT INTO `appoe_plugin_itemGlue_articles_content` (`idArticle`, `type`, `content`, `lang`, `updated_at`)
                SELECT idArticle, "NAME", content, "' . $minLang . '", NOW() FROM `appoe_plugin_itemGlue_articles_content` WHERE type = "NAME" AND lang = "fr";
                INSERT INTO `appoe_plugin_itemGlue_articles_content` (`idArticle`, `type`, `content`, `lang`, `updated_at`)
                SELECT idArticle, "DESCRIPTION", content, "' . $minLang . '", NOW() FROM `appoe_plugin_itemGlue_articles_content` WHERE type = "DESCRIPTION" AND lang = "fr";
                INSERT INTO `appoe_plugin_itemGlue_articles_content` (`idArticle`, `type`, `content`, `lang`, `updated_at`)
                SELECT idArticle, "SLUG", content, "' . $minLang . '", NOW() FROM `appoe_plugin_itemGlue_articles_content` WHERE type = "SLUG" AND lang = "fr";';

                $sqlAdded[] = 'INSERT INTO `appoe_plugin_itemGlue_articles_meta` (`idArticle`, `metaKey`, `metaValue`, `lang`, `updated_at`)
                SELECT idArticle, metaKey, metaValue, "' . $minLang . '", NOW()
                FROM `appoe_plugin_itemGlue_articles_meta` WHERE lang = "fr";';
            }
        }

        $sqlToUpdate = array(
            'ALTER TABLE `appoe_plugin_itemGlue_articles_meta` ADD `lang` VARCHAR(10) NOT NULL DEFAULT "fr" AFTER `metaValue`',
            'ALTER TABLE `appoe_plugin_itemGlue_articles_meta` DROP INDEX idArticle',
            'ALTER TABLE `appoe_plugin_itemGlue_articles_meta` ADD UNIQUE (`idArticle`, `metaKey`, `lang`)',
            'ALTER TABLE `appoe_plugin_itemGlue_articles_content` ADD `type` VARCHAR(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT "BODY" AFTER `idArticle`;',
            'ALTER TABLE `appoe_plugin_itemGlue_articles_content` DROP INDEX idArticle',
            'ALTER TABLE `appoe_plugin_itemGlue_articles_content` ADD UNIQUE (`idArticle`, `type`, `lang`)',
            'INSERT INTO `appoe_plugin_itemGlue_articles_content` (`idArticle`, `type`, `content`, `lang`, `updated_at`) SELECT id, "NAME", name, "fr", CURDATE() FROM `appoe_plugin_itemGlue_articles`;
            INSERT INTO `appoe_plugin_itemGlue_articles_content` (`idArticle`, `type`, `content`, `lang`, `updated_at`) SELECT id, "DESCRIPTION", description, "fr", CURDATE() FROM `appoe_plugin_itemGlue_articles`;
            INSERT INTO `appoe_plugin_itemGlue_articles_content` (`idArticle`, `type`, `content`, `lang`, `updated_at`) SELECT id, "SLUG", slug, "fr", CURDATE() FROM `appoe_plugin_itemGlue_articles`;',
            'ALTER TABLE `appoe_plugin_itemGlue_articles` DROP `name`, DROP `description`, DROP `slug`',
        );

        $sqlToUpdate = array_merge($sqlToUpdate, $sqlAdded);*/

        $sqlToUpdate = array(
            'RENAME TABLE `appoe_files_content` TO `appoe_filesContent`',

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