<?php

if (!function_exists('menuActive')) {
    function menuActive($routeName)
    {
        $class = 'active';
        if (is_array($routeName)) {
            foreach ($routeName as $key => $value) {
                if (request()->routeIs($value)) {
                    return $class;
                }
            }
        } elseif (request()->routeIs($routeName)) {
            return $class;
        }
    }
}
