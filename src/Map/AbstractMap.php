<?php
declare(strict_types=1);
namespace chilimatic\lib\Route\Map;

use chilimatic\lib\Interfaces\IFlyWeightParser;

/**
 * Class Generic
 *
 * @package chilimatic\lib\Route\Map
 */
abstract class AbstractMap implements IMapStrategy
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
    abstract public function init();

    /**
     * @param $param
     *
     * @return mixed
     */
    abstract public function call($param = null);

}