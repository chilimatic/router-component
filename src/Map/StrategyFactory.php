<?php
namespace chilimatic\lib\Route\Map;

use chilimatic\lib\Route\Exception\RouteException;
use chilimatic\lib\Interfaces\IFlyWeightParser;
use chilimatic\lib\Route\Map;

class StrategyFactory implements StaticMapFactory
{

    /**
     * @param $type
     * @param $config
     * @param IFlyWeightParser $parser
     *
     * @return MapClosure|MapFunction|MapObject|mixed
     */
    public static function make($type, $config, IFlyWeightParser $parser = null)
    {
        switch ($type) {
            case Map::TYPE_O:
                return new MapObject($config, $parser);
                break;
            case Map::TYPE_F:
                //return new MFunction($config);
                break;
            case Map::TYPE_LF:
                //return new LambdaFunction($config);
                break;
            default:
                throw new RouteException("Invalid Call type" . __METHOD__);
                break;
        }
    }
}