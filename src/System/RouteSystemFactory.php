<?php
namespace chilimatic\lib\Route\System;

/**
 * Class RouteSystemFactory
 *
 * @package chilimatic\lib\Route\System
 */
class RouteSystemFactory
{

    /**
     * @param string $type
     *
     * @return AbstractRoute
     */
    public static function make($type = 'Default', $param)
    {
        $className = __NAMESPACE__ . "\\{$type}Route";

        if (!class_exists($className)) {
            return null;
        }

        return new $className($param);
    }
}