<?php

namespace chilimatic\lib\Route;


use \chilimatic\lib\Route\Exception\RouteException;
use \chilimatic\lib\Route\Map\StrategyFactory;
use \chilimatic\lib\Route\Parser\RouteMethodAnnotationParser;
use chilimatic\lib\Route\Validator\AbstractValidator;


/**
 * @author  j
 * @package chilimatic\lib\Route
 */
class Map
{

    /**
     * prefix for a validation field
     * :NUM, :CHAR
     *
     * @var string
     */
    const VALIDATION_PREFIX = ':';

    /**
     * if it's a function that's called
     *
     * @var int
     */
    const TYPE_F = 0;

    /**
     * if it's a function that's called
     *
     * @var int
     */
    const TYPE_O = 1;

    /**
     * if it's a lambda function that's called
     *
     * @var int
     */
    const TYPE_LF = 2;

    /**
     * Default delimiter so it's a constant that can be changed
     * at one place
     *
     * @var string
     */
    const DEFAULT_URL_DELIMITER = '/';

    /**
     * path splittet per delimiter
     *
     *
     * @var array
     */
    private $urlPart = array();


    /**
     * delimiter for the urls
     *
     * @var string
     */
    protected $_delimiter = '/';

    /**
     * validate parameter
     *
     * @var bool
     */
    private $validate = false;

    /**
     * @var null
     */
    private $type = null;

    /**
     * @var null|\chilimatic\lib\Route\Map\Generic
     */
    private $strategy = null;

    /**
     * general constructor
     *
     * @param string $uri
     * @param null $callback
     * @param string $delimiter
     *
     */
    public function __construct($uri = null, $callback = null, $delimiter = '/')
    {

        if (empty($uri)) {
            return;
        }

        $this->_delimiter = (!empty($delimiter) ? $delimiter : self::DEFAULT_URL_DELIMITER);

        /**
         * inititalize the routing map
         */
        $this->init($uri, $callback);
    }

    /**
     * validates a url and adds the validator to the mapping part
     *
     * @param string $uri
     *
     * @throws RouteException
     *
     * @return boolean Ambigous \Route\Route_Validator>
     */

    private function validate($uri)
    {
        try {
            // set to false as default
            $validate = [];

            // check if there is the marker vor a special validation and or a / for complex routing
            if (strpos($uri, self::VALIDATION_PREFIX) === false && strpos($uri, $this->getDelimiter()) === false) {
                return false;
            }

            $parts = explode($this->_delimiter, $uri);
            for ($i = 0, $c = count($parts); $i < $c; $i++) {
                if (empty($parts[$i])) {
                    continue;
                } elseif ((strpos($parts[$i], self::VALIDATION_PREFIX)) === false) { // if there's no placeholder in use for a specific type
                    $this->urlPart[] = $parts[$i];
                    continue;
                }

                // this is an option to map "polymorph" [differnt types differnt classes / methods]
                $this->urlPart[] = $parts[$i];
                // position within the route to be validated
                $validate[]     = new Validator($parts[$i]);
                $this->validate = true;
            }
            unset($parts, $c, $i, $uri);
        } catch (RouteException $e) {
            throw $e;
        }

        return $validate;
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return (string)implode(self::getDelimiter(), (array)$this->urlPart);
    }

    /**
     * initlializes the mapping
     *
     * @param string $uri
     * @param mixed $callback
     *
     * @throws RouteException
     *
     * @return boolean
     */
    private function init($uri, $callback)
    {

        try {
            /**
             * check if there is a general type validation
             * can throw an exception
             */
            $this->validate($uri);

            switch (true) {
                /**
                 * check if the routing maps to an object via array or class
                 */
                case (is_array($callback) || (is_object($callback) && !is_callable($callback))) :
                    $this->setType(self::TYPE_O);
                    $this->strategy = StrategyFactory::make(
                        self::TYPE_O,
                        $callback,
                        new RouteMethodAnnotationParser()
                    );
                    break;

                /**
                 * maps a simple function
                 */
                case (is_string($callback)) :
                    $this->setType(self::TYPE_F);
                    $this->strategy = StrategyFactory::make(
                        self::TYPE_F,
                        $callback
                    );

                    if (!function_exists($callback)) {
                        throw new RouteException(sprintf(_('There is no such Function like %s'), $callback));
                    }
                    break;

                /**
                 * Closure callback
                 */
                case (is_callable($callback)) :
                    $this->setType(self::TYPE_LF);
                    $this->strategy = StrategyFactory::make(
                        self::TYPE_LF,
                        $callback
                    );
                    break;
            }
        } catch (RouteException $e) {
            throw $e;
        }

        return true;
    }

    /**
     * @param null $param
     *
     * @return mixed|null
     */
    public function call($param = null)
    {
        if (!$this->strategy) {
            return null;
        }

        return $this->getStrategy()->call($param);
    }

    /**
     * @return map\Generic|null
     */
    protected function getStrategy()
    {
        return $this->strategy;
    }

    /**
     * @param map\Generic|null $strategy
     */
    public function setStrategy($strategy)
    {
        $this->strategy = $strategy;
    }


    /**
     * @return string
     */
    public function getDelimiter()
    {
        if (empty($this->_delimiter)) {
            $this->_delimiter = self::DEFAULT_URL_DELIMITER;
        }

        return $this->_delimiter;
    }

    /**
     * @return mixed
     */
    public function getValidate()
    {
        return $this->validate;
    }

    /**
     * @param mixed $validate
     *
     * @return $this
     */
    public function setValidate(AbstractValidator $validate)
    {
        $this->validate = $validate;

        return $this;
    }


    /**
     * @return null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return array
     */
    public function getUrlPart()
    {
        return $this->urlPart;
    }

    /**
     * @param array $urlPart
     *
     * @return $this
     */
    public function setUrlPart(array $urlPart)
    {
        $this->urlPart = $urlPart;

        return $this;
    }
}