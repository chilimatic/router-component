<?php
declare(strict_types=1);
namespace chilimatic\lib\Route\Map;
use chilimatic\lib\Interfaces\IFlyWeightParser;

/**
 * Interface MapCallInterface
 *
 * @package chilimatic\lib\Route\Map
 */
Interface IMapStrategy
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