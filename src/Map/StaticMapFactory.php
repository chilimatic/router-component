<?php
namespace chilimatic\lib\Route\Map;

/**
 * Interface StaticMapFactory
 *
 * @package chilimatic\lib\Route\Map
 */
Interface StaticMapFactory
{

    /**
     * @param $type
     * @param $config
     *
     * @return mixed
     */
    public static function make($type, $config);

}