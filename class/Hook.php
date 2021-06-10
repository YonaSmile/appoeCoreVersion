<?php

namespace App;
class Hook
{
    private static int $defaultPriority = 100;
    private static array $actions = array(
        'core_front_before_html' => array()
    );

    public static function apply($hook, $args = array())
    {
        if (!empty(self::$actions[$hook])) {
            foreach (array_sort(self::$actions[$hook], 'priority', SORT_DESC) as $f) {
                $f['function']($args);
            }
        }
        return true;
    }

    public static function add_action($hook, $function, $priority = 0)
    {
        if (!isset($function)) {
            return false;
        }

        if (!isset($priority) || !(intval($priority) > 0)) {
            $priority = self::$defaultPriority;
        }

        self::$actions[$hook][] = array(
            'function' => $function,
            'priority' => intval($priority)
        );
        return true;
    }
}