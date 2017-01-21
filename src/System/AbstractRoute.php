<?php
namespace chilimatic\lib\Route\System;

use chilimatic\lib\Route\Map;
use chilimatic\lib\Route\Map\MapFactory;
use chilimatic\lib\Route\System\Node\RouteNode;
use chilimatic\lib\Transformer\String\DynamicFunctionCallName;

/**
 * Class AbstractRoute
 *
 * @package chilimatic\lib\Route\System
 */
abstract class AbstractRoute
{

    const PATH_ROOT = '/';
    /**
     * Trait
     */
    use RouteTrait;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var RouteNode
     */
    protected $currentRoute;

    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        $this->setTransformer(new DynamicFunctionCallName());
        $this->setMapFactory(new MapFactory());
    }


    /**
     * @param array $urlParts
     *
     * @return mixed
     */
    abstract public function getRoute(array $urlParts = []);

    /**
     * @param $uri
     * @param $callback
     * @param string $delimiter
     *
     * @return mixed
     */
    abstract public function addRoute(string $uri, $callback, $delimiter = Map::DEFAULT_URL_DELIMITER);

}