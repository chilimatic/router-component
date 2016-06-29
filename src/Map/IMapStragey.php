<?php
namespace chilimatic\lib\Route\Map;
use chilimatic\lib\Interfaces\IFlyWeightParser;

/**
 * Interface MapCallInterface
 *
 * @package chilimatic\lib\Route\Map
 */
Interface IMapStragey
{

    /**
     * @param mixed $config
     * @param IFlyWeightParser $parser
     */
    public function __construct($config, IFlyWeightParser $parser = null);

    /**
     * @return mixed
     */
    public function init();


    /**
     * @param $param
     *
     * @return mixed
     */
    public function call($param);
}