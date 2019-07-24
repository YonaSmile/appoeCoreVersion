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
       */

        $sqlAdded = array('DELETE FROM `appoe_menu` WHERE `id` = 603');
        $testedLang = array(LANG);

        foreach (getLangs() as $minLang => $largeLang) {
            if (!in_array($minLang, $testedLang)) {
                $testedLang[] = $minLang;
                $sqlAdded[] = 'INSERT INTO `appoe_plugin_itemGlue_articles_content` (`idArticle`, `type`, `content`, `lang`, `updated_at`)
                SELECT idArticle, "NAME", content, "' . $minLang . '", CURDATE() FROM `appoe_plugin_itemGlue_articles_content` WHERE type = "NAME" AND lang = "fr";
                INSERT INTO `appoe_plugin_itemGlue_articles_content` (`idArticle`, `type`, `content`, `lang`, `updated_at`)
                SELECT idArticle, "DESCRIPTION", content, "' . $minLang . '", CURDATE() FROM `appoe_plugin_itemGlue_articles_content` WHERE type = "DESCRIPTION" AND lang = "fr";
                INSERT INTO `appoe_plugin_itemGlue_articles_content` (`idArticle`, `type`, `content`, `lang`, `updated_at`)
                SELECT idArticle, "SLUG", content, "' . $minLang . '", CURDATE() FROM `appoe_plugin_itemGlue_articles_content` WHERE type = "SLUG" AND lang = "fr";';
            }
        }

        $sqlToUpdate = array(
            'ALTER TABLE `appoe_plugin_itemGlue_articles_content` ADD `type` VARCHAR(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT "BODY" AFTER `idArticle`;',
            'ALTER TABLE `appoe_plugin_itemGlue_articles_content` DROP INDEX idArticle',
            'ALTER TABLE `appoe_plugin_itemGlue_articles_content` ADD UNIQUE (`idArticle`, `type`, `lang`)',
            'INSERT INTO `appoe_plugin_itemGlue_articles_content` (`idArticle`, `type`, `content`, `lang`, `updated_at`) SELECT id, "NAME", name, "fr", CURDATE() FROM `appoe_plugin_itemGlue_articles`;
            INSERT INTO `appoe_plugin_itemGlue_articles_content` (`idArticle`, `type`, `content`, `lang`, `updated_at`) SELECT id, "DESCRIPTION", description, "fr", CURDATE() FROM `appoe_plugin_itemGlue_articles`;
            INSERT INTO `appoe_plugin_itemGlue_articles_content` (`idArticle`, `type`, `content`, `lang`, `updated_at`) SELECT id, "SLUG", slug, "fr", CURDATE() FROM `appoe_plugin_itemGlue_articles`;',
            'ALTER TABLE `appoe_plugin_itemGlue_articles` DROP `name`, DROP `description`, DROP `slug`',
        );

        $sqlToUpdate = array_merge($sqlToUpdate, $sqlAdded);
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