<?php
namespace chilimatic\lib\Route\Map;

use chilimatic\lib\Route\Map;

/**
 * Class MapFactory
 *
 * @package chilimatic\lib\Route\Map
 */
class MapFactory
{
    /**
     * @param string $path
     * @param mixed $callback
     * @param $delimiter
     *
     * @return Map
     */
    public function make(string $path, $callback, $delimiter)
    {
        return new Map($path, $callback, $delimiter);
    }
}