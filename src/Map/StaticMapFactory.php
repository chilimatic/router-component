<?php
declare(strict_types=1);
namespace chilimatic\lib\Route\Map;

/**
 * Interface StaticMapFactory
 *
 * @package chilimatic\lib\Route\Map
 */
Interface StaticMapFactory
{

    /**
     * @param int $type
     * @param $config
     *
     * @return mixed
     */
    public static function make(int $type, $config);

}