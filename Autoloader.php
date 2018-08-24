<?php

namespace App;
class Autoloader
{
    static function register()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    static function autoload($class)
    {

        if (strpos($class, '\\')) {

            $classExplodes = explode('\\', $class);
            $classExplodesLwr = array_map('lcfirst', $classExplodes);

            $file = array_pop($classExplodesLwr);

            $class = implode('/', $classExplodesLwr);
            $class .= DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR;
            $class .= ucfirst($file) . '.php';
        }

        if (file_exists(ROOT_PATH . $class)) {
            require_once(ROOT_PATH . $class);
        }
    }
}