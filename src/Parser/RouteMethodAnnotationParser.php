<?php
declare(strict_types=1);
namespace chilimatic\lib\Route\Parser;

use chilimatic\lib\Interfaces\IFlyWeightParser;

/**
 * Class RouteMethodAnnotationParser
 * @package chilimatic\lib\Route\Parser
 */
class RouteMethodAnnotationParser implements IFlyWeightParser
{
    /**
     * @var string
     */
    const TYPE_CLASS = 'class';

    /**
     * @var string
     */
    const TYPE_SCALAR = 'scalar';

    /**
     * @var string
     */
    private $pattern = '/@(\w*)[\s]*([a-zA-Z\\\\]*)/';

    /**
     * @param string $content
     *
     * @return array
     */
    public function parse(string $content) : array
    {
        $result = [];
        if (strpos($content, '@view') === false) {
            return $result;
        }

        if (preg_match_all($this->pattern, $content, $matches)) {

            for ($i = 0, $c = count($matches[0]); $i < $c; $i++) {
                $result[] = [
                    'property' => $matches[1][$i],
                    'value'    => $matches[2][$i],
                    'type'     => $this->getType($matches[2][$i])
                ];
            }

            return $result;
        }

        return $result;
    }


    /**
     * for the beginning keep it simple -> is a new object
     * or just a value
     *
     * @param string $value
     *
     * @return string
     */
    public function getType(string $value) : string
    {
        if (strpos($value, '\\') !== false && class_exists($value)) {
            return self::TYPE_CLASS;
        } else {
            return self::TYPE_SCALAR;
        }
    }

}