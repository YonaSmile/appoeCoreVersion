<?php
/**
 * DataBase config
 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/ini.main.php');

/**
 * Charset
 */
ini_set('default_charset', 'UTF-8');

/**
 * App paths
 */
define('FILE_DIR_NAME', 'include/');
define('APP_ROOT', WEB_DIR . 'app/');
define('WEB_APP_PATH', ROOT_PATH . 'app/');
define('WEB_PUBLIC_PATH', ROOT_PATH . 'public/');
define('TEMPLATES_PATH', WEB_PUBLIC_PATH . 'html/');
define('WEB_TEMPLATE_PATH', WEB_APP_PATH . 'template/');
define('WEB_AJAX_PATH', WEB_APP_PATH . 'ajax/');
define('WEB_PLUGIN_PATH', WEB_APP_PATH . 'plugin/');
define('WEB_PROCESS_PATH', WEB_APP_PATH . 'process/');
define('WEB_SYSTEM_PATH', WEB_APP_PATH . 'system/');
define('FILE_DIR_PATH', ROOT_PATH . FILE_DIR_NAME);
define('MAIL_DIR_PATH', ROOT_PATH . '/ressources/mail/');
define('FILE_LANG_PATH', WEB_SYSTEM_PATH . 'lang/');
define('INCLUDE_PLUGIN_PATH', WEB_DIR . 'app/plugin/');

/**
 * App urls
 */
define('WEB_APP_URL', WEB_DIR_URL . 'app/');
define('WEB_ADMIN_URL', WEB_APP_URL . 'page/');
define('WEB_PLUGIN_URL', WEB_APP_URL . 'plugin/');
define('WEB_PUBLIC_URL', WEB_DIR_URL . 'public/');
define('WEB_DIR_IMG', WEB_PUBLIC_URL . 'images/');
define('WEB_DIR_INCLUDE', WEB_DIR_URL . 'include/');
define('WEB_DIR_SYSTEM', WEB_APP_URL . 'system/');
define('WEB_DIR_MAIL', WEB_DIR_URL . '/ressources/mail/');
define('FILE_DIR_URL', WEB_DIR_URL . FILE_DIR_NAME);

/**
 * Errors config
 */
error_reporting(E_ALL);
ini_set('display_errors', MAINTENANCE ? 1 : 0);
ini_set('log_errors', 1);
ini_set('error_log', ROOT_PATH . 'error.log');

/**
 * Defined App const
 */
define('NUM_OF_ATTEMPTS', 30);

const ROLES = array(
    1 => 'Rédacteur',
    2 => 'Responsable',
    3 => 'Administrateur',
    4 => 'Technicien',
    5 => 'King'
);

const APP_TABLES = array(
    'appoe_users',
    'appoe_menu',
    'appoe_files',
    'appoe_categories',
    'appoe_categoryRelations'
);

const CATEGORY_TYPES = array(
    'APPOE',
    'MEDIA',
    'AUTRE'
);