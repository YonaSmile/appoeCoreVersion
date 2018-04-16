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
            $classExplodes = array_map('lcfirst', $classExplodes);

            $file = array_pop($classExplodes);

            $class = implode('/', $classExplodes);
            $class .= DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR;
            $class .= ucfirst($file) . '.php';
        }

        require_once(ROOT_PATH . $class);
    }
}