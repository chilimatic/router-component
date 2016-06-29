<?php
namespace chilimatic\lib\Route\Map;

use chilimatic\lib\Interfaces\IFlyWeightParser;

/**
 * Class Generic
 *
 * @package chilimatic\lib\Route\Map
 */
abstract class Generic implements IMapStragey
{

    /**
     * @var mixed
     */
    protected $config;

    /**
     * @var IFlyWeightParser
     */
    protected $parser;

    /**
     * @param mixed $config
     * @param IFlyWeightParser $parser
     *
     * @internal param $type
     */
    final public function __construct($config, IFlyWeightParser $parser = null)
    {
        $this->config = $config;
        $this->init();

        $this->parser = $parser;
    }

    /**
     * @return mixed
     */
    abstract function init();

    /**
     * @param $param
     *
     * @return mixed
     */
    abstract function call($param = null);

}