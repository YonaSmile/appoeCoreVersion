<?php
/**
 * DataBase config
 */
require_once( $_SERVER['DOCUMENT_ROOT'] . '/ini.main.php' );

/**
 * Charset
 */
ini_set( 'default_charset', 'UTF-8' );

/**
 * App paths
 */
define( 'FILE_DIR_NAME', 'include/' );
define( 'APP_ROOT', WEB_DIR . 'app/' );
define( 'WEB_APP_PATH', ROOT_PATH . 'app/' );
define( 'WEB_PUBLIC_PATH', ROOT_PATH . 'public/' );
define( 'WEB_PATH', WEB_PUBLIC_PATH . 'html/' );
define( 'WEB_LIB_PATH', WEB_APP_PATH . 'lib/' );
define( 'WEB_PHPMAILER_PATH', WEB_LIB_PATH . 'php/PHPMailer/' );
define( 'WEB_TEMPLATE_PATH', WEB_LIB_PATH . 'template/' );
define( 'WEB_AJAX_PATH', WEB_APP_PATH . 'ajax/' );
define( 'WEB_PLUGIN_PATH', WEB_APP_PATH . 'plugin/' );
define( 'WEB_PROCESS_PATH', WEB_APP_PATH . 'process/' );
define( 'WEB_SYSTEM_PATH', WEB_APP_PATH . 'system/' );
define( 'WEB_BACKUP_PATH', WEB_APP_PATH . 'backup/' );
define( 'FILE_DIR_PATH', ROOT_PATH . FILE_DIR_NAME );
define( 'THUMB_DIR_PATH', FILE_DIR_PATH . 'thumb/' );
define( 'MAIL_DIR_PATH', ROOT_PATH . '/ressources/mail/' );
define( 'FILE_LANG_PATH', WEB_SYSTEM_PATH . 'lang/' );
define( 'INCLUDE_PLUGIN_PATH', WEB_DIR . 'app/plugin/' );

/**
 * App urls
 */
define( 'WEB_APP_URL', WEB_DIR_URL . 'app/' );
define( 'WEB_ADMIN_URL', WEB_APP_URL . 'page/' );
define( 'WEB_LIB_URL', WEB_APP_URL . 'lib/' );
define( 'WEB_TEMPLATE_URL', WEB_LIB_URL . 'template/' );
define( 'APP_IMG_URL', WEB_TEMPLATE_URL . 'images/' );
define( 'WEB_PLUGIN_URL', WEB_APP_URL . 'plugin/' );
define( 'WEB_PUBLIC_URL', WEB_DIR_URL . 'public/' );
define( 'WEB_DIR_IMG', WEB_PUBLIC_URL . 'images/' );
define( 'WEB_DIR_INCLUDE', WEB_DIR_URL . FILE_DIR_NAME );
define( 'WEB_DIR_SYSTEM', WEB_APP_URL . 'system/' );
define( 'WEB_DIR_MAIL', WEB_DIR_URL . '/ressources/mail/' );

/**
 * Errors config
 */
error_reporting( E_ALL );
ini_set( 'display_errors', defined( 'DEBUG' ) && DEBUG ? 1 : 0 );
ini_set( 'log_errors', 1 );
ini_set( 'error_log', ROOT_PATH . 'error.log' );

/**
 * Set App Interface lang
 */
define( 'INTERFACE_LANG', 'fr' );

/**
 * Set App Content lang
 */
if ( ! empty( $_SESSION['APP_LANG'] ) ) {
	define( 'APP_LANG', $_SESSION['APP_LANG'] );
} else {
	define( 'APP_LANG', 'fr' );
}

/**
 * Set website lang
 */
if ( ! empty( $_COOKIE['LANG'] ) && array_key_exists( $_COOKIE['LANG'], LANGUAGES ) ) {
	define( 'LANG', $_COOKIE['LANG'] );

} else {

	if ( ! empty( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) && array_key_exists( $_SERVER['HTTP_ACCEPT_LANGUAGE'], LANGUAGES ) ) {
		define( 'LANG', substr( $_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2 ) );
	} else {
		define( 'LANG', 'fr' );
	}

	setcookie( 'LANG', LANG, strtotime( '+30 days' ), WEB_DIR, '', false, true );
}

/**
 * Defined local timezone & date format
 */
setlocale( LC_ALL, strtolower( LANG ) . '_' . strtoupper( LANG ) . '.UTF-8' );

/**
 * Defined App const & params
 */
define( 'NUM_OF_ATTEMPTS', 30 );

//Table prefix
if ( ! defined( 'TABLEPREFIX' ) ) {
	define( 'TABLEPREFIX', '' );
}

//Acces restriction to APPOE with a min role id
if ( ! defined( 'APPOE_MIN_ROLE' ) ) {
	define( 'APPOE_MIN_ROLE', 1 );
}

const APP_TABLES = array(
	TABLEPREFIX . 'appoe_users',
	TABLEPREFIX . 'appoe_menu',
	TABLEPREFIX . 'appoe_files',
	TABLEPREFIX . 'appoe_filesContent',
	TABLEPREFIX . 'appoe_categories',
	TABLEPREFIX . 'appoe_categoryRelations'
);

const CATEGORY_TYPES = array(
	'APPOE',
	'MEDIA',
	'AUTRE'
);

const PAGE_TYPES = array(
	'boutique' => 'SHOP',
	'shop'     => 'SHOP',
	'produit'  => 'SHOP',
	'product'  => 'SHOP',
	'article'  => 'ITEMGLUE',
	'news'     => 'ITEMGLUE',
	'archives' => 'ITEMGLUE',
	'blog'     => 'ITEMGLUE'
);

//Load plugin files only for a specific app filename : plugin name => [filename, filename, ] || false (without extension)
const INI_LOAD_PLUGIN_FOR_APP_FILENAME = array(
	'leaflet'        => false,
	'interactiveMap' => [ 'updateInterMapContent', 'updateInterMap' ]
);